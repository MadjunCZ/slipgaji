<?php

namespace App\Http\Controllers;

use App\Repositories\SipenaRepository;
use App\Models\LogPencarianSlip;
use App\Models\ApiLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RiwayatPencarianExport;

class SlipGajiController extends Controller
{
    protected SipenaRepository $repository;

    public function __construct(SipenaRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display the main slip gaji search page
     */
    public function index(Request $request)
    {
        $data = [
            'title' => 'Cari Slip Gaji',
            'darkMode' => $request->cookie('dark_mode', false),
        ];

        // Get unit kerja from database
        try {
            $data['unitKerja'] = \DB::table('unit_kerja')
                ->where('aktif', true)
                ->orderBy('urutan')
                ->orderBy('nama')
                ->get(['kode', 'nama'])
                ->map(function($item) {
                    return (array) $item;
                })
                ->toArray();
        } catch (\Exception $e) {
            $data['unitKerja'] = [];
            Log::warning('Failed to load unit kerja from DB: ' . $e->getMessage());
        }

        // Get keperluan from database
        try {
            $data['keperluan'] = \DB::table('keperluan')
                ->orderBy('id')
                ->get(['id', 'nama'])
                ->map(function($item) {
                    return (array) $item;
                })
                ->toArray();
        } catch (\Exception $e) {
            $data['keperluan'] = [];
            Log::warning('Failed to load keperluan from DB: ' . $e->getMessage());
        }

        return view('slip-gaji.index', $data);
    }

    /**
     * Search slip gaji via AJAX
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|numeric|digits_between:9,18',
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'unit_kerja' => 'nullable|string|max:100',
            'tujuan_unduh' => 'nullable|string|max:100',
        ], [
            'nip.required' => 'NIP wajib diisi',
            'nip.numeric' => 'NIP harus berupa angka',
            'nip.digits_between' => 'NIP harus 9-18 digit',
            'bulan.required' => 'Bulan wajib dipilih',
            'bulan.between' => 'Bulan tidak valid',
            'tahun.required' => 'Tahun wajib dipilih',
            'tahun.min' => 'Tahun tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->repository->searchSlipGaji(
                $request->nip,
                $request->bulan,
                $request->tahun,
                $request->unit_kerja,
                $request->tujuan_unduh,
                auth()->id()
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Pencarian gagal',
                    'error_code' => $result['error_code'] ?? 'UNKNOWN',
                ], 400);
            }

            // Check if response is PDF (from JSON body with content_type)
            if (isset($result['content_type']) && $result['content_type'] === 'application/pdf') {
                // $result['data'] contains: {success, filename, content_type, document}
                $apiData = $result['data'];
                $pdfBase64 = $apiData['document'];
                $pdfData = base64_decode($pdfBase64);
                $filename = $apiData['filename'] ?? 'slip_gaji_' . $request->nip . '_' . $request->tahun . sprintf('%02d', $request->bulan) . '.pdf';
                
                Log::info('Using filename from API', ['filename' => $filename]);
                
                return response($pdfData)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . rawurlencode($filename) . '"')
                    ->header('Content-Length', strlen($pdfData));
            }

            // Handle new API response format with filename and document (base64 PDF)
            $data = $result['data'] ?? [];
            if (isset($data['filename']) && isset($data['document'])) {
                $pdfBase64 = $data['document'];
                $pdfData = base64_decode($pdfBase64);
                $filename = $data['filename']; // Use filename from API response
                
                return response($pdfData)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                    ->header('Content-Length', strlen($pdfData));
            }

            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'Pencarian berhasil',
                'data' => $result['data'] ?? [],
            ]);
        } catch (\Exception $e) {
            Log::error('Search slip gaji error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Download slip gaji as PDF
     */
    public function download(Request $request, string $slipId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tujuan_unduh' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->repository->downloadSlip(
                $slipId,
                $request->tujuan_unduh,
                auth()->id()
            );

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Download gagal',
                    'error_code' => $result['error_code'] ?? 'UNKNOWN',
                ], 400);
            }

            // Handle PDF response (from JSON with content_type)
            if (isset($result['content_type']) && $result['content_type'] === 'application/pdf') {
                $apiData = $result['data'];
                
                // Check if data is array with document/filename
                if (is_array($apiData) && isset($apiData['document']) && isset($apiData['filename'])) {
                    $pdfBase64 = $apiData['document'];
                    $pdfData = base64_decode($pdfBase64);
                    $filename = $apiData['filename'];
                    
                    Log::info('Download using filename from API', ['filename' => $filename]);
                    
                    // Store temp file
                    $tempPath = storage_path('app/temp/' . $filename);
                    if (!is_dir(dirname($tempPath))) {
                        mkdir(dirname($tempPath), 0755, true);
                    }
                    file_put_contents($tempPath, $pdfData);

                    return response()->json([
                        'success' => true,
                        'message' => 'Download siap',
                        'download_url' => route('slip-gaji.download.file', ['filename' => $filename]),
                        'filename' => $filename,
                    ]);
                }
                
                // Raw PDF body
                $pdfData = is_string($apiData) ? $apiData : json_encode($apiData);
                $filename = 'slip_gaji_' . $slipId . '_' . date('Ymd_His') . '.pdf';
                
                // Store temp file
                $tempPath = storage_path('app/temp/' . $filename);
                if (!is_dir(dirname($tempPath))) {
                    mkdir(dirname($tempPath), 0755, true);
                }
                file_put_contents($tempPath, $pdfData);

                return response()->json([
                    'success' => true,
                    'message' => 'Download siap',
                    'download_url' => route('slip-gaji.download.file', ['filename' => $filename]),
                    'filename' => $filename,
                ]);
            }

            // Handle different response types (base64 PDF, URL, or direct data)
            $data = $result['data'] ?? [];
            // Handle new API response format with filename and document (base64 PDF)
            if (isset($data['filename']) && isset($data['document'])) {
                $pdfBase64 = $data['document'];
                $pdfData = base64_decode($pdfBase64);
                $filename = $data['filename']; // Use filename from API response
                
                // Store temp file
                $tempPath = storage_path('app/temp/' . $filename);
                if (!is_dir(dirname($tempPath))) {
                    mkdir(dirname($tempPath), 0755, true);
                }
                file_put_contents($tempPath, $pdfData);

                return response()->json([
                    'success' => true,
                    'message' => 'Download siap',
                    'download_url' => route('slip-gaji.download.file', ['filename' => $filename]),
                    'filename' => $filename,
                ]);
            }

            if (isset($data['pdf_base64']) || isset($data['data'])) {
                $pdfBase64 = $data['pdf_base64'] ?? $data['data'];
                $pdfData = base64_decode($pdfBase64);
                
                $filename = 'slip_gaji_' . $slipId . '_' . date('Ymd_His') . '.pdf';
                
                // Store temp file
                $tempPath = storage_path('app/temp/' . $filename);
                if (!is_dir(dirname($tempPath))) {
                    mkdir(dirname($tempPath), 0755, true);
                }
                file_put_contents($tempPath, $pdfData);

                return response()->json([
                    'success' => true,
                    'message' => 'Download siap',
                    'download_url' => route('slip-gaji.download.file', ['filename' => $filename]),
                    'filename' => $filename,
                ]);
            }

            // If API returns URL
            if (isset($data['url'])) {
                return response()->json([
                    'success' => true,
                    'message' => 'Download siap',
                    'download_url' => $data['url'],
                    'filename' => 'slip_gaji_' . $slipId . '.pdf',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Download slip gaji error', [
                'slip_id' => $slipId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat download',
            ], 500);
        }
    }

    /**
     * Serve the downloaded file
     */
    public function downloadFile(string $filename): Response
    {
        $filePath = storage_path('app/temp/' . $filename);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $content = file_get_contents($filePath);
        
        // Cleanup temp file
        unlink($filePath);

        return response($content)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Length', strlen($content));
    }

    /**
     * Get unit kerja options
     */
    public function getUnitKerja(Request $request): JsonResponse
    {
        try {
            $forceRefresh = $request->boolean('refresh', false);
            $result = $this->repository->getUnitKerja($forceRefresh);

            return response()->json([
                'success' => true,
                'data' => $result['data'] ?? [],
                'cached' => $result['cached'] ?? false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data unit kerja',
            ], 500);
        }
    }

    /**
     * Get tujuan unduh options
     */
    public function getTujuanUnduh(Request $request): JsonResponse
    {
        try {
            $forceRefresh = $request->boolean('refresh', false);
            $result = $this->repository->getTujuanUnduh($forceRefresh);

            return response()->json([
                'success' => true,
                'data' => $result['data'] ?? [],
                'cached' => $result['cached'] ?? false,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data tujuan unduh',
            ], 500);
        }
    }

    /**
     * Get search history for current user
     */
    public function riwayat(Request $request): JsonResponse
    {
        $limit = $request->integer('limit', 50);
        $result = $this->repository->getRiwayatPencarian(auth()->id(), $limit);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Get statistics dashboard
     */
    public function statistik(Request $request): JsonResponse
    {
        $this->authorize('admin');

        $startDate = $request->string('start_date');
        $endDate = $request->string('end_date');

        $result = $this->repository->getStatistik(
            $startDate ?: null,
            $endDate ?: null
        );

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Display statistics page
     */
    public function statistikPage(Request $request)
    {
        $this->authorize('admin');

        $data = [
            'title' => 'Statistik Penggunaan API',
            'darkMode' => $request->cookie('dark_mode', false),
        ];

        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $data['statistik'] = $this->repository->getStatistik($startDate, $endDate);
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        return view('slip-gaji.statistik', $data);
    }

    /**
     * Display API logs page
     */
    public function apiLogs(Request $request)
    {
        $this->authorize('admin');

        $data = [
            'title' => 'Log API SIPENA',
            'darkMode' => $request->cookie('dark_mode', false),
        ];

        $filters = $request->only(['endpoint', 'method', 'status_code', 'date_from', 'date_to']);
        $perPage = $request->integer('per_page', 20);

        $data['logs'] = $this->repository->getApiLogs($filters, $perPage);
        $data['filters'] = $filters;

        return view('slip-gaji.api-logs', $data);
    }

    /**
     * Export search history to Excel
     */
    public function exportExcel(Request $request)
    {
        $this->authorize('admin');

        $filters = $request->only(['user_id', 'nip', 'status', 'date_from', 'date_to']);

        try {
            return Excel::download(
                new RiwayatPencarianExport($filters),
                'riwayat_pencarian_slip_' . date('Ymd_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            Log::error('Export Excel error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal export ke Excel');
        }
    }

    /**
     * Export search history to PDF
     */
    public function exportPdf(Request $request)
    {
        $this->authorize('admin');

        $filters = $request->only(['user_id', 'nip', 'status', 'date_from', 'date_to']);

        try {
            $data = $this->repository->exportRiwayat($filters, 100);

            $pdf = Pdf::loadView('slip-gaji.exports.pdf', [
                'data' => $data,
                'title' => 'Riwayat Pencarian Slip Gaji',
                'exportDate' => now()->format('d/m/Y H:i'),
            ]);

            return $pdf->download('riwayat_pencarian_slip_' . date('Ymd_His') . '.pdf');
        } catch (\Exception $e) {
            Log::error('Export PDF error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal export ke PDF');
        }
    }

    /**
     * Toggle dark mode
     */
    public function toggleDarkMode(Request $request): JsonResponse
    {
        $isDark = $request->boolean('dark', false);
        
        $cookie = cookie()->forever('dark_mode', $isDark ? '1' : '0');

        return response()->json([
            'success' => true,
            'dark_mode' => $isDark,
        ])->cookie($cookie);
    }

    /**
     * Clear all cached data
     */
    public function clearCache(): JsonResponse
    {
        $this->authorize('admin');

        try {
            \Illuminate\Support\Facades\Cache::flush();
            
            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal hapus cache',
            ], 500);
        }
    }
}
