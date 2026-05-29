<?php

namespace App\Console\Commands;

use App\Services\SipenaService;
use Illuminate\Console\Command;

class TestSipenaApi extends Command
{
    protected $signature = 'sipena:test 
        {--nip= : NIP untuk test}
        {--bulan= : Bulan dalam format YYYY-MM, contoh: 2026-05}
        {--unit_kerja= : Unit/Satuan Kerja}
        {--keperluan= : Keperluan/Tujuan}';
    
    protected $description = 'Test koneksi dan functionality API SIPENA';

    public function handle(SipenaService $service): int
    {
        $this->info('===========================================');
        $this->info('        SIPENA API Test Command');
        $this->info('===========================================');
        $this->newLine();

        $baseUrl = config('sipena.base_url');
        $apiKey = config('sipena.api_key');
        $endpoints = config('sipena.endpoints');

        // Test 1: Check API Key
        $this->info('1. Checking API Key...');
        if (empty($apiKey) || $apiKey === 'your_api_key_here') {
            $this->error('   API Key belum dikonfigurasi!');
            $this->error('   Silakan edit file .env dan isi SIPENA_API_KEY');
            return Command::FAILURE;
        }
        $this->info('   ✓ API Key configured: ' . substr($apiKey, 0, 10) . '...');
        $this->newLine();

        // Test 2: Test getUnitKerja (GET)
        $endpoint = $endpoints['unit_kerja'];
        $url = $baseUrl . $endpoint;
        
        $this->info('2. Testing getUnitKerja [GET]...');
        $this->info('   Endpoint: ' . $endpoint);
        $this->info('   Full URL: ' . $url);
        $this->newLine();
        $this->info('   CURL Command:');
        $this->line('   ' . $this->generateCurlGet($url, $apiKey));
        $this->newLine();

        try {
            $result = $service->getUnitKerja();
            if ($result['success']) {
                $count = is_array($result['data']) ? count($result['data']) : 0;
                $this->info("   ✓ Success! Found {$count} unit kerja");
                if (isset($result['cached']) && $result['cached']) {
                    $this->warn('   (Data dari cache)');
                }
            } else {
                $this->warn('   ⚠ API returned: ' . ($result['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            $this->error('   ✗ Error: ' . $e->getMessage());
        }
        $this->newLine();

        // Test 3: Test getTujuanUnduh (GET)
        $endpoint = $endpoints['tujuan_unduh'];
        $url = $baseUrl . $endpoint;
        
        $this->info('3. Testing getTujuanUnduh [GET]...');
        $this->info('   Endpoint: ' . $endpoint);
        $this->info('   Full URL: ' . $url);
        $this->newLine();
        $this->info('   CURL Command:');
        $this->line('   ' . $this->generateCurlGet($url, $apiKey));
        $this->newLine();

        try {
            $result = $service->getTujuanUnduh();
            if ($result['success']) {
                $count = is_array($result['data']) ? count($result['data']) : 0;
                $this->info("   ✓ Success! Found {$count} tujuan unduh");
                if (isset($result['cached']) && $result['cached']) {
                    $this->warn('   (Data dari cache)');
                }
            } else {
                $this->warn('   ⚠ API returned: ' . ($result['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            $this->error('   ✗ Error: ' . $e->getMessage());
        }
        $this->newLine();

        // Test 4: Test searchSlipGaji (POST)
        $nip = $this->option('nip');
        $bulanOption = $this->option('bulan');
        
        if ($nip && $bulanOption) {
            // Parse bulan (format: YYYY-MM)
            $parts = explode('-', $bulanOption);
            if (count($parts) !== 2) {
                $this->error('   Format bulan salah! Gunakan format: YYYY-MM (contoh: 2026-05)');
                return Command::FAILURE;
            }
            $tahun = (int)$parts[0];
            $bulan = (int)$parts[1];

            $unitKerja = $this->option('unit_kerja');
            $keperluan = $this->option('keperluan');

            $endpoint = $endpoints['search_slip_gaji'];
            $url = $baseUrl . $endpoint;

            // Build body sesuai format API
            $body = [
                'nip' => $nip,
                'bulan' => $bulanOption,
            ];
            
            if ($unitKerja) {
                $body['unit_kerja'] = $unitKerja;
            }
            
            if ($keperluan) {
                $body['keperluan'] = $keperluan;
            }

            $bodyJson = json_encode($body, JSON_PRETTY_PRINT);

            $this->info("4. Testing searchSlipGaji [POST]...");
            $this->info("   Endpoint: " . $endpoint);
            $this->info("   NIP: {$nip}");
            $this->info("   Bulan: {$bulanOption}");
            if ($unitKerja) {
                $this->info("   Unit Kerja: {$unitKerja}");
            }
            if ($keperluan) {
                $this->info("   Keperluan: {$keperluan}");
            }
            $this->newLine();
            $this->info('   CURL Command:');
            $this->line('   ' . $this->generateCurlPost($url, $bodyJson, $apiKey));
            $this->newLine();

            try {
                $result = $service->searchSlipGaji($nip, $bulan, $tahun, $unitKerja, $keperluan);
                if ($result['success']) {
                    $data = $result['data'];
                    
                    // Check for new API response format with filename and document
                    if (isset($data['filename']) && isset($data['document'])) {
                        $this->info("   ✓ Success! New API format received");
                        $this->info("   Filename: " . $data['filename']);
                        $this->info("   Content-Type: " . ($data['content_type'] ?? 'application/pdf'));
                        $docLength = strlen($data['document'] ?? '');
                        $this->info("   Document size: " . number_format($docLength) . " bytes");
                    } elseif (is_array($data) && count($data) > 0) {
                        $this->info("   ✓ Success! Found " . count($data) . " slip gaji");
                        foreach (array_slice($data, 0, 3) as $slip) {
                            $nama = $slip['nama'] ?? $slip['name'] ?? '-';
                            $this->info("     - {$nama}");
                        }
                        if (count($data) > 3) {
                            $this->info("     ... and " . (count($data) - 3) . " more");
                        }
                    } else {
                        $this->warn('   ⚠ No data found for this NIP/period');
                    }
                } else {
                    $this->warn('   ⚠ API returned: ' . ($result['message'] ?? 'Unknown error'));
                }
            } catch (\Exception $e) {
                $this->error('   ✗ Error: ' . $e->getMessage());
            }
            $this->newLine();
        } else {
            $this->info('4. Skip searchSlipGaji test (but displaying example)');
            $this->info('   ');
            $this->info('   Contoh penggunaan:');
            $this->line('   php artisan sipena:test --nip=198609012019031008 --bulan=2026-05 --unit_kerja=BKD --keperluan="API"');
            $this->newLine();
            
            // Tampilkan contoh CURL
            $endpoint = $endpoints['search_slip_gaji'];
            $url = $baseUrl . $endpoint;
            $exampleBody = json_encode([
                'nip' => '198609012019031008',
                'bulan' => '2026-05',
                'unit_kerja' => 'BKD',
                'keperluan' => 'API',
            ], JSON_PRETTY_PRINT);
            
            $this->info('   Contoh CURL Command:');
            $this->line('   ' . $this->generateCurlPost($url, $exampleBody, $apiKey));
            $this->newLine();
        }

        // Summary
        $this->info('===========================================');
        $this->info('        Test Complete');
        $this->info('===========================================');
        $this->newLine();

        $this->info('Configuration:');
        $this->info('  Base URL: ' . $baseUrl);
        $this->info('  Timeout: ' . config('sipena.timeout') . 's');
        $this->info('  Retry: ' . config('sipena.retry.attempts') . ' attempts');
        $this->newLine();

        return Command::SUCCESS;
    }

    /**
     * Generate curl command for GET request
     */
    protected function generateCurlGet(string $url, string $apiKey): string
    {
        return "curl -X GET \"{$url}\" -H \"X-API-KEY: {$apiKey}\" -H \"Accept: application/json\"";
    }

    /**
     * Generate curl command for POST request
     */
    protected function generateCurlPost(string $url, string $body, string $apiKey): string
    {
        return "curl -X POST \"{$url}\" -H \"X-API-KEY: {$apiKey}\" -H \"Content-Type: application/json\" -d '{$body}'";
    }
}
