<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/worker-files/{filename}', function (string $filename) {
    abort_unless(preg_match('/^[A-Za-z0-9._-]+$/', $filename), 404);

    $workerUrl = rtrim(env('PYTHON_WORKER_URL', 'http://127.0.0.1:8001'), '/');
    $response = Http::timeout(120)->get("{$workerUrl}/files/{$filename}");

    if (!$response->successful()) {
        abort(404);
    }

    $contentType = $response->header('Content-Type') ?? 'application/octet-stream';

    return response($response->body(), 200)->header('Content-Type', $contentType);
})->name('worker.files');

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
