<?php

use App\Http\Controllers\DailySafetyPatrolController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectSafetyController;
use App\Http\Controllers\SafetyBriefingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanExportController;
use App\Http\Controllers\ToolStockHistoryController;
use App\Http\Controllers\ValidationSafetyPatrolController;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/download/template/{file}', [SafetyBriefingController::class, 'download'])
->where('file', '.*')
->name('download.template');

Route::middleware('auth')->group(function () {
    Route::get('/laporan/export', [LaporanExportController::class, 'export'])->name('laporan.export');
    Route::get('/export/laporan', [LaporanExportController::class, 'index'])->name('export.index');
    Route::post('/stock-transactions', [ToolStockHistoryController::class, 'store'])
    ->name('stock-transactions.store');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/user', UserController::class);
    Route::prefix('/safety-patrol')->group(function () {
        Route::resource('/project', ProjectSafetyController::class);
        Route::get('/project/{id}/export', [ProjectSafetyController::class, 'exportCsv'])->name('project.export.single');
        Route::resource('/daily-report', DailySafetyPatrolController::class);
        Route::post(
            '/daily-report/{report}/validate',
            [ValidationSafetyPatrolController::class, 'store']
        )->name('daily-report.validate');
    });
    Route::prefix('/tool')->group(function () {
        Route::resource('tools', ToolController::class);
        Route::resource('loans', LoanController::class)->except(['edit', 'update', 'destroy']);
        // route untuk proses pengembalian (POST)
        Route::post('loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
    });
    Route::get('/my-activity', [ActivityController::class, 'index'])->name('activity.index');
    Route::resource('/safety-briefing', SafetyBriefingController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
