<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Exceptions\SipenaException;

/**
 * SipenaService - Service untuk integrasi API SIPENA
 * 
 * Digunakan untuk mengambil data slip gaji ASN/Pegawai dari API SIPENA.
 */
class SipenaService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected int $timeout;
    protected int $retryAttempts;
    protected int $retryDelay;

    public function __construct()
    {
        $this->baseUrl = config('sipena.base_url');
        $this->apiKey = config('sipena.api_key');
        $this->timeout = config('sipena.timeout', 30);
        $this->retryAttempts = config('sipena.retry.attempts', 3);
        $this->retryDelay = config('sipena.retry.delay', 1000);
    }

    /**
     * Get HTTP client dengan konfigurasi default
     */
    protected function getClient(bool $pdf = false): \Illuminate\Http\Client\PendingRequest
    {
        return Http::retry($this->retryAttempts, $this->retryDelay)
            ->timeout($this->timeout)
            ->withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Accept' => $pdf ? 'application/pdf' : 'application/json',
                'Content-Type' => 'application/json',
            ]);
    }

    /**
     * Search slip gaji menggunakan POST
     * 
     * @param string $nip NIP Pegawai
     * @param int $bulan Bulan (1-12)
     * @param int $tahun Tahun
     * @param string|null $unitKerja Unit/Satuan Kerja
     * @param string|null $keperluan Keperluan/Tujuan Unduh
     */
    public function searchSlipGaji(
        string $nip,
        int $bulan,
        int $tahun,
        ?string $unitKerja = null,
        ?string $keperluan = null
    ): array {
        $endpoint = config('sipena.endpoints.search_slip_gaji');
        $url = $this->baseUrl . $endpoint;

        // Format bulan: YYYY-MM (string)
        $bulanFormatted = sprintf('%d-%02d', $tahun, $bulan);

        $body = [
            'nip' => $nip,
            'bulan' => $bulanFormatted,
        ];

        if ($unitKerja) {
            $body['unit_kerja'] = $unitKerja;
        }

        if ($keperluan) {
            $body['keperluan'] = $keperluan;
        }

        Log::info('SIPENA API Request [searchSlipGaji]', [
            'url' => $url,
            'body' => $body,
        ]);

        try {
            $response = $this->getClient(true)->post($url, $body);

            Log::info('SIPENA API Response [searchSlipGaji]', [
                'status' => $response->status(),
                'content_type' => $response->header('Content-Type'),
                'content_length' => strlen($response->body()),
            ]);

            // Check if response is PDF
            $contentType = $response->header('Content-Type');
            if ($contentType && str_contains($contentType, 'application/pdf')) {
                return [
                    'success' => true,
                    'data' => $response->body(),
                    'content_type' => 'application/pdf',
                    'message' => 'PDF slip gaji berhasil diambil',
                ];
            }

            // If not PDF, try to parse as JSON (error response)
            try {
                $jsonData = $response->json();
                return [
                    'success' => $jsonData['success'] ?? false,
                    'message' => $jsonData['message'] ?? 'Terjadi kesalahan',
                    'status_code' => $response->status(),
                ];
            } catch (\Exception $e) {
                // Not JSON, return generic error
                return [
                    'success' => false,
                    'message' => 'Terjadi kesalahan pada server',
                    'status_code' => $response->status(),
                ];
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Parse 404 body for API message
            $responseBody = $e->response->body();
            try {
                $jsonData = json_decode($responseBody, true);
                if (isset($jsonData['message'])) {
                    return [
                        'success' => false,
                        'message' => $jsonData['message'],
                        'status_code' => 404,
                    ];
                }
            } catch (\Exception $parseException) {
                // Ignore JSON parse error
            }
            
            return $this->handleException($e, 'search_slip_gaji');
        }
    }

    /**
     * Download slip gaji menggunakan POST
     */
    public function downloadSlip(string $slipId, string $tujuanUnduh): array
    {
        $endpoint = config('sipena.endpoints.download_slip');
        $url = $this->baseUrl . $endpoint;

        try {
            $response = $this->getClient()->post($url, [
                'id' => $slipId,
                'keperluan' => $tujuanUnduh,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'data' => $data['data'] ?? $data,
                    'message' => $data['message'] ?? 'Success',
                ];
            }

            return $this->handleError($response, 'download_slip');
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return $this->handleException($e, 'download_slip');
        }
    }

    /**
     * Get unit kerja
     */
    public function getUnitKerja(bool $forceRefresh = false): array
    {
        $cacheKey = config('sipena.cache.unit_kerja.key');
        $cacheTtl = config('sipena.cache.unit_kerja.ttl');
        $cacheEnabled = config('sipena.cache.unit_kerja.enabled');

        if ($cacheEnabled && !$forceRefresh && Cache::has($cacheKey)) {
            return [
                'success' => true,
                'data' => Cache::get($cacheKey),
                'cached' => true,
            ];
        }

        $endpoint = config('sipena.endpoints.unit_kerja');
        $url = $this->baseUrl . $endpoint;

        try {
            $response = $this->getClient()->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $unitKerjaList = $data['data'] ?? $data;

                if ($cacheEnabled) {
                    Cache::put($cacheKey, $unitKerjaList, $cacheTtl);
                }

                return [
                    'success' => true,
                    'data' => $unitKerjaList,
                    'cached' => false,
                ];
            }

            return $this->handleError($response, 'get_unit_kerja');
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return $this->handleException($e, 'get_unit_kerja');
        }
    }

    /**
     * Get tujuan unduh
     */
    public function getTujuanUnduh(bool $forceRefresh = false): array
    {
        $cacheKey = config('sipena.cache.tujuan_unduh.key');
        $cacheTtl = config('sipena.cache.tujuan_unduh.ttl');
        $cacheEnabled = config('sipena.cache.tujuan_unduh.enabled');

        if ($cacheEnabled && !$forceRefresh && Cache::has($cacheKey)) {
            return [
                'success' => true,
                'data' => Cache::get($cacheKey),
                'cached' => true,
            ];
        }

        $endpoint = config('sipena.endpoints.tujuan_unduh');
        $url = $this->baseUrl . $endpoint;

        try {
            $response = $this->getClient()->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $tujuanUnduhList = $data['data'] ?? $data;

                if ($cacheEnabled) {
                    Cache::put($cacheKey, $tujuanUnduhList, $cacheTtl);
                }

                return [
                    'success' => true,
                    'data' => $tujuanUnduhList,
                    'cached' => false,
                ];
            }

            return $this->handleError($response, 'get_tujuan_unduh');
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return $this->handleException($e, 'get_tujuan_unduh');
        }
    }

    /**
     * Clear cache
     */
    public function clearCache(): void
    {
        Cache::forget(config('sipena.cache.unit_kerja.key'));
        Cache::forget(config('sipena.cache.tujuan_unduh.key'));
    }

    /**
     * Handle HTTP error response
     */
    protected function handleError($response, string $context): array
    {
        $statusCode = $response->status();
        
        // Try to get message from API response first
        $errorMessage = config('sipena.error_messages.server_error');

        // Parse JSON response
        try {
            $responseData = $response->json();
            
            // Use API's own message if available
            if (isset($responseData['message']) && !empty($responseData['message'])) {
                $errorMessage = $responseData['message'];
            }
        } catch (\Exception $e) {
            // If not JSON, use status code based messages
            if ($statusCode === 401) {
                $errorMessage = config('sipena.error_messages.unauthorized');
            } elseif ($statusCode === 404) {
                $errorMessage = config('sipena.error_messages.not_found');
            } elseif ($statusCode === 429) {
                $errorMessage = config('sipena.error_messages.rate_limit');
            }
        }

        Log::error("SIPENA API Error [{$context}]", [
            'status' => $statusCode,
            'message' => $errorMessage,
        ]);

        return [
            'success' => false,
            'message' => $errorMessage,
            'status_code' => $statusCode,
        ];
    }

    /**
     * Handle exception
     */
    protected function handleException(\Illuminate\Http\Client\RequestException $e, string $context): array
    {
        // Try to get message from response body first
        $errorMessage = config('sipena.error_messages.connection_error');

        try {
            if ($e->response) {
                $responseBody = $e->response->body();
                $jsonData = json_decode($responseBody, true);
                if (isset($jsonData['message']) && !empty($jsonData['message'])) {
                    $errorMessage = $jsonData['message'];
                }
            }
        } catch (\Exception $parseException) {
            // Ignore parse errors
        }

        if ($e->getCode() === CURLE_OPERATION_TIMEDOUT) {
            $errorMessage = config('sipena.error_messages.timeout');
        }

        Log::error("SIPENA API Exception [{$context}]", [
            'message' => $errorMessage,
            'code' => $e->getCode(),
        ]);

        return [
            'success' => false,
            'message' => $errorMessage,
            'error' => $e->getMessage(),
        ];
    }
}
