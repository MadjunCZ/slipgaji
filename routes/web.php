<?php

use App\Http\Controllers\SlipGajiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Slip Gaji Routes
Route::get('/', [SlipGajiController::class, 'index'])->name('slip-gaji.index');
Route::prefix('slipgaji')->name('slip-gaji.')->group(function () {
    // Main page
    Route::get('/', [SlipGajiController::class, 'index'])->name('index');
    
    // API endpoints
    Route::post('/search', [SlipGajiController::class, 'search'])->name('search');
    Route::post('/download/{slipId}', [SlipGajiController::class, 'download'])->name('download');
    Route::get('/download/file/{filename}', [SlipGajiController::class, 'downloadFile'])->name('download.file');
    Route::get('/unit-kerja', [SlipGajiController::class, 'getUnitKerja'])->name('unit-kerja');
    Route::get('/tujuan-unduh', [SlipGajiController::class, 'getTujuanUnduh'])->name('tujuan-unduh');
    Route::get('/riwayat', [SlipGajiController::class, 'riwayat'])->name('riwayat');
    
    // Dark mode toggle
    Route::post('/dark-mode', [SlipGajiController::class, 'toggleDarkMode'])->name('dark-mode');
    
    // Admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/statistik', [SlipGajiController::class, 'statistikPage'])->name('statistik');
        Route::get('/statistik/data', [SlipGajiController::class, 'statistik'])->name('statistik.data');
        Route::get('/api-logs', [SlipGajiController::class, 'apiLogs'])->name('api-logs');
        Route::get('/export/excel', [SlipGajiController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [SlipGajiController::class, 'exportPdf'])->name('export.pdf');
        Route::post('/clear-cache', [SlipGajiController::class, 'clearCache'])->name('clear-cache');
    });
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'Slip Gaji API',
        'timestamp' => now()->toISOString(),
    ]);
});
