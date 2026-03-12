<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\VideoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('campaigns', CampaignController::class);
    Route::post('campaigns/{id}/generate', [CampaignController::class, 'generate']);

    Route::get('videos', [VideoController::class, 'index']);
    Route::get('videos/{id}', [VideoController::class, 'show']);
});

// Worker endpoints
Route::post('worker/videos/{id}/status', [VideoController::class, 'updateStatus']);
Route::post('worker/videos/{id}/media', [VideoController::class, 'uploadMedia']);
