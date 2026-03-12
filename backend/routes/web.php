<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CampaignController::class, 'index'])->name('dashboard');
    Route::get('/campaigns/create', [\App\Http\Controllers\CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [\App\Http\Controllers\CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{id}', [\App\Http\Controllers\CampaignController::class, 'show'])->name('campaigns.show');
    Route::delete('/campaigns/{id}', [\App\Http\Controllers\CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::post('/campaigns/{id}/pause', [\App\Http\Controllers\CampaignController::class, 'pause'])->name('campaigns.pause');
    Route::post('/campaigns/{id}/resume', [\App\Http\Controllers\CampaignController::class, 'resume'])->name('campaigns.resume');
    Route::post('/videos/{id}/retry', [\App\Http\Controllers\CampaignController::class, 'retryVideo'])->name('videos.retry');

    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\SettingsController::class, 'store'])->name('settings.store');

    Route::get('/logs', [\App\Http\Controllers\SystemLogController::class, 'index'])->name('logs.index');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');
});
