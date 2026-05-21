<?php

namespace App\Repositories;

use App\Services\SipenaService;
use App\Models\LogPencarianSlip;
use App\Models\ApiLog;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * SipenaRepository - Repository untuk akses data SIPENA
 * 
 * Pattern: Repository untuk memisahkan logika bisnis dari akses data.
 */
class SipenaRepository
{
    protected SipenaService $service;

    public function __construct(SipenaService $service)
    {
        $this->service = $service;
    }

    /**
     * Search slip gaji dengan logging
     */
    public function searchSlipGaji(
        string $nip,
        int $bulan,
        int $tahun,
        ?string $unitKerja = null,
        ?string $tujuanUnduh = null,
        ?int $userId = null
    ): array {
        $startTime = microtime(true);
        $logId = null;

        try {
            $result = $this->service->searchSlipGaji($nip, $bulan, $tahun, $unitKerja, $tujuanUnduh);

            $executionTime = (microtime(true) - $startTime) * 1000;

            $this->logSearch(
                $nip,
                $bulan,
                $tahun,
                $unitKerja,
                $tujuanUnduh,
                $userId,
                $result,
                $executionTime
            );

            $this->logApiCall('search_slip_gaji', 'GET', [
                'nip' => $nip,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ], $result, $executionTime);

            return $result;
        } catch (\Exception $e) {
            $executionTime = (microtime(true) - $startTime) * 1000;

            $this->logSearch(
                $nip,
                $bulan,
                $tahun,
                $unitKerja,
                $tujuanUnduh,
                $userId,
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                $executionTime,
                $e->getMessage()
            );

            $this->logApiCall('search_slip_gaji', 'GET', [
                'nip' => $nip,
                'bulan' => $bulan,
                'tahun' => $tahun,
            ], [
                'success' => false,
                'message' => $e->getMessage(),
            ], $executionTime, $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'SEARCH_ERROR',
            ];
        }
    }

    /**
     * Download slip gaji
     */
    public function downloadSlip(string $slipId, string $tujuanUnduh, ?int $userId = null): array
    {
        $startTime = microtime(true);

        try {
            $result = $this->service->downloadSlip($slipId, $tujuanUnduh);

            $executionTime = (microtime(true) - $startTime) * 1000;

            $this->logApiCall('download_slip', 'GET', [
                'id' => $slipId,
                'tujuan_unduh' => $tujuanUnduh,
            ], $result, $executionTime);

            return $result;
        } catch (\Exception $e) {
            $executionTime = (microtime(true) - $startTime) * 1000;

            $this->logApiCall('download_slip', 'GET', [
                'id' => $slipId,
                'tujuan_unduh' => $tujuanUnduh,
            ], [
                'success' => false,
                'message' => $e->getMessage(),
            ], $executionTime, $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'DOWNLOAD_ERROR',
            ];
        }
    }

    /**
     * Get unit kerja
     */
    public function getUnitKerja(bool $forceRefresh = false): array
    {
        return $this->service->getUnitKerja($forceRefresh);
    }

    /**
     * Get tujuan unduh
     */
    public function getTujuanUnduh(bool $forceRefresh = false): array
    {
        return $this->service->getTujuanUnduh($forceRefresh);
    }

    /**
     * Log pencarian slip
     */
    protected function logSearch(
        string $nip,
        int $bulan,
        int $tahun,
        ?string $unitKerja,
        ?string $tujuanUnduh,
        ?int $userId,
        array $result,
        float $executionTime,
        ?string $errorMessage = null
    ): void {
        try {
            LogPencarianSlip::create([
                'user_id' => $userId,
                'nip' => $nip,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'unit_kerja' => $unitKerja,
                'tujuan_unduh' => $tujuanUnduh,
                'status' => $result['success'] ? 'success' : 'failed',
                'response_data' => json_encode($result),
                'error_message' => $errorMessage,
                'execution_time_ms' => round($executionTime, 2),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log search: ' . $e->getMessage());
        }
    }

    /**
     * Log API call
     */
    protected function logApiCall(
        string $endpoint,
        string $method,
        array $params,
        ?array $response,
        float $executionTime,
        ?string $errorMessage = null
    ): void {
        try {
            ApiLog::create([
                'endpoint' => $endpoint,
                'method' => $method,
                'request_params' => json_encode($params),
                'request_headers' => json_encode(['X-API-Key' => '***']),
                'response_status' => $response['success'] ?? false ? 200 : 500,
                'response_body' => json_encode($response),
                'error_message' => $errorMessage,
                'execution_time_ms' => round($executionTime, 2),
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log API call: ' . $e->getMessage());
        }
    }

    /**
     * Clear cache
     */
    public function clearCache(): void
    {
        $this->service->clearCache();
    }

    /**
     * Get search history
     */
    public function getSearchHistory(?int $userId = null, int $limit = 50): array
    {
        $query = LogPencarianSlip::query()
            ->orderBy('created_at', 'desc');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->limit($limit)->get()->toArray();
    }

    /**
     * Get API statistics
     */
    public function getStatistics(): array
    {
        $totalSearches = LogPencarianSlip::count();
        $successfulSearches = LogPencarianSlip::where('status', 'success')->count();
        $failedSearches = LogPencarianSlip::where('status', 'failed')->count();
        
        $avgExecutionTime = LogPencarianSlip::avg('execution_time_ms') ?? 0;
        
        $recentLogs = ApiLog::orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->toArray();

        return [
            'total_searches' => $totalSearches,
            'successful_searches' => $successfulSearches,
            'failed_searches' => $failedSearches,
            'success_rate' => $totalSearches > 0 ? round(($successfulSearches / $totalSearches) * 100, 2) : 0,
            'avg_execution_time_ms' => round($avgExecutionTime, 2),
            'recent_logs' => $recentLogs,
        ];
    }
}
