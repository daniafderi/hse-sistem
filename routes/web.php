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
use App\Http\Controllers\NotificationController;
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
    Route::get('/tool/laporan', [ToolController::class, 'export'])->name('tool.laporan');
    Route::get('/tool/export', [ToolController::class, 'download'])->name('tool.export');
    Route::post('/stock-transactions', [ToolStockHistoryController::class, 'store'])
    ->name('stock-transactions.store');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notification-all', [NotificationController::class, 'index'])->name('notify.all');
    Route::resource('/user', UserController::class)->middleware('can:isHseAdmin');
    Route::post('/user/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword')->middleware('can:isHseAdmin');
    Route::prefix('/safety-patrol')->group(function () {
        Route::resource('/project', ProjectSafetyController::class);
        Route::get('/project/{id}/export', [ProjectSafetyController::class, 'exportCsv'])->name('project.export.single');
        Route::resource('/daily-report', DailySafetyPatrolController::class)->parameters([
        'daily-report' => 'dailySafetyPatrol'
    ]);
        Route::post(
            '/daily-report/{report}/validate',
            [ValidationSafetyPatrolController::class, 'store']
        )->name('daily-report.validate');
    });
    Route::prefix('/tool')->group(function () {
        Route::resource('tools', ToolController::class);
        Route::resource('loans', LoanController::class)->except(['edit', 'update']);
        // route untuk proses pengembalian (POST)
        Route::post('loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
        Route::post(
            '/tools/{tool}/validate',
            [ToolController::class, 'validation']
        )->name('tools.validate')->middleware('can:isSupervisor');
    });
    Route::get('/my-activity', [ActivityController::class, 'index'])->name('activity.index');
    Route::resource('/safety-briefing', SafetyBriefingController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
