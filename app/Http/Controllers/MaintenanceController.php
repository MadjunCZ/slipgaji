<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class MaintenanceController extends Controller
{
    /**
     * Cache key untuk maintenance mode status
     */
    const CACHE_KEY = 'maintenance_mode';

    /**
     * Get maintenance mode status
     */
    public function status()
    {
        $isDown = file_exists(storage_path('framework/down'));
        
        return response()->json([
            'status' => $isDown ? 'maintenance' : 'active',
            'maintenance_mode' => $isDown,
            'message' => $isDown 
                ? 'Sistem sedang dalam mode maintenance' 
                : 'Sistem berjalan normal',
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Enable maintenance mode
     */
    public function enable(Request $request)
    {
        try {
            // Validasi secret key (opsional, untuk keamanan)
            $secretKey = $request->input('secret');
            $configuredKey = config('app.maintenance_secret');
            
            if ($configuredKey && $secretKey !== $configuredKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Secret key tidak valid'
                ], 401);
            }

            // Jalankan artisan down command
            $minMinutes = $request->input('minutes', 15);
            $retry = $request->input('retry', 0);
            
            Artisan::call('down', [
                '--render' => 'errors.503',
                '--retry' => $retry,
                '--secret' => $request->input('secret'),
            ]);

            // Simpan metadata maintenance di cache
            Cache::put(self::CACHE_KEY, [
                'enabled_at' => now()->toISOString(),
                'reason' => $request->input('reason', 'Maintenance scheduled'),
                'estimated_minutes' => $minMinutes,
                'enabled_by' => $request->ip(),
            ], 60 * 60 * 24); // 24 jam

            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode berhasil diaktifkan',
                'data' => [
                    'maintenance_mode' => true,
                    'estimated_duration' => $minMinutes . ' menit',
                    'activated_at' => now()->toISOString(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan maintenance mode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Disable maintenance mode
     */
    public function disable(Request $request)
    {
        try {
            // Validasi secret key
            $secretKey = $request->input('secret');
            $configuredKey = config('app.maintenance_secret');
            
            if ($configuredKey && $secretKey !== $configuredKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Secret key tidak valid'
                ], 401);
            }

            // Jalankan artisan up command
            Artisan::call('up');

            // Hapus cache metadata
            Cache::forget(self::CACHE_KEY);

            return response()->json([
                'success' => true,
                'message' => 'Maintenance mode berhasil dinonaktifkan',
                'data' => [
                    'maintenance_mode' => false,
                    'deactivated_at' => now()->toISOString(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan maintenance mode: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get maintenance metadata
     */
    public function info()
    {
        $metadata = Cache::get(self::CACHE_KEY);
        
        if (!$metadata) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data maintenance'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $metadata
        ]);
    }
}
