<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class MaintenanceMode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:mode 
                            {action : The action to perform (status, enable, disable)}
                            {--minutes=15 : Estimated minutes until maintenance is complete}
                            {--reason= : Reason for maintenance}
                            {--secret= : Secret key for maintenance bypass}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage application maintenance mode';

    /**
     * Cache key for maintenance metadata
     */
    const CACHE_KEY = 'maintenance_mode';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'status':
                $this->showStatus();
                break;
            case 'enable':
                $this->enableMaintenance();
                break;
            case 'disable':
                $this->disableMaintenance();
                break;
            default:
                $this->error('Invalid action. Use: status, enable, or disable');
                return 1;
        }

        return 0;
    }

    /**
     * Show current maintenance status
     */
    protected function showStatus()
    {
        $isDown = file_exists(storage_path('framework/down'));
        $metadata = Cache::get(self::CACHE_KEY);

        $this->newLine();
        $this->info('═══════════════════════════════════════════════');
        $this->info('         MAINTENANCE MODE STATUS');
        $this->info('═══════════════════════════════════════════════');
        $this->newLine();

        if ($isDown) {
            $this->error('  ⚠️  MAINTENANCE MODE: ENABLED');
        } else {
            $this->info('  ✅ MAINTENANCE MODE: DISABLED');
        }

        $this->newLine();

        if ($metadata) {
            $this->line('  📋 Information:');
            $this->line('     • Enabled at: ' . ($metadata['enabled_at'] ?? 'N/A'));
            $this->line('     • Reason: ' . ($metadata['reason'] ?? 'N/A'));
            $this->line('     • Estimated: ' . ($metadata['estimated_minutes'] ?? 'N/A') . ' minutes');
            $this->line('     • By: ' . ($metadata['enabled_by'] ?? 'N/A'));
        }

        $this->newLine();
        $this->info('═══════════════════════════════════════════════');
        $this->newLine();

        // Show usage instructions
        $this->line('  Usage Commands:');
        $this->line('    php artisan maintenance:mode status');
        $this->line('    php artisan maintenance:mode enable --minutes=30 --reason="Update sistem"');
        $this->line('    php artisan maintenance:mode disable');
        $this->newLine();
    }

    /**
     * Enable maintenance mode
     */
    protected function enableMaintenance()
    {
        $this->info('Enabling maintenance mode...');

        // Check if already in maintenance
        if (file_exists(storage_path('framework/down'))) {
            $this->warn('Maintenance mode is already enabled!');
            return;
        }

        $minutes = $this->option('minutes');
        $reason = $this->option('reason') ?: 'Scheduled maintenance';
        $secret = $this->option('secret');

        // Run Laravel down command with custom 503 view
        $this->info('Executing: php artisan down');
        
        Artisan::call('down', [
            '--render' => 'errors.503',
            '--retry' => 60,
            '--secret' => $secret,
        ]);

        // Store metadata
        Cache::put(self::CACHE_KEY, [
            'enabled_at' => now()->toISOString(),
            'reason' => $reason,
            'estimated_minutes' => $minutes,
            'enabled_by' => 'console',
        ], 60 * 60 * 24); // 24 hours

        $this->newLine();
        $this->info('═══════════════════════════════════════════════');
        $this->info('  ✅ MAINTENANCE MODE ENABLED SUCCESSFULLY');
        $this->info('═══════════════════════════════════════════════');
        $this->newLine();

        if ($secret) {
            $this->warn('  🔐 Bypass Secret: ' . $secret);
            $this->line('     Access the application using the secret URL:');
            $this->line('     ' . url('/') . '/' . $secret);
        }

        $this->newLine();
        $this->line('  ⏰ Estimated completion: ' . $minutes . ' minutes');
        $this->newLine();
    }

    /**
     * Disable maintenance mode
     */
    protected function disableMaintenance()
    {
        $this->info('Disabling maintenance mode...');

        // Check if not in maintenance
        if (!file_exists(storage_path('framework/down'))) {
            $this->warn('Maintenance mode is already disabled!');
            return;
        }

        // Run Laravel up command
        $this->info('Executing: php artisan up');
        Artisan::call('up');

        // Clear metadata cache
        Cache::forget(self::CACHE_KEY);

        $this->newLine();
        $this->info('═══════════════════════════════════════════════');
        $this->info('  ✅ MAINTENANCE MODE DISABLED SUCCESSFULLY');
        $this->info('═══════════════════════════════════════════════');
        $this->newLine();
        $this->line('  🌐 Application is now accessible at:');
        $this->line('     ' . url('/'));
        $this->newLine();
    }
}
