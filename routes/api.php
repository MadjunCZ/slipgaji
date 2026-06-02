<?php

use App\Http\Controllers\MaintenanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Routes
|--------------------------------------------------------------------------
|
| Routes ini dapat diakses meskipun dalam mode maintenance
| untuk memungkinkan pengecekan status dan pengelolaan maintenance
|
*/

Route::prefix('maintenance')->group(function () {
    Route::get('/status', [MaintenanceController::class, 'status'])->name('maintenance.status');
    Route::get('/info', [MaintenanceController::class, 'info'])->name('maintenance.info');
    Route::post('/enable', [MaintenanceController::class, 'enable'])->name('maintenance.enable');
    Route::post('/disable', [MaintenanceController::class, 'disable'])->name('maintenance.disable');
});
