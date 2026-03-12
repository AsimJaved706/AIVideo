<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Campaign;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    // Basic scheduler to check active campaigns
    $campaigns = Campaign::where('status', 'Ready')->get();
    foreach ($campaigns as $campaign) {
        // Logic to dispatch PublishVideoJob for pending videos
        $video = $campaign->videos()->where('status', 'Ready')->first();
        if ($video) {
            \App\Jobs\PublishVideoJob::dispatch($video);
        }
    }
})->hourly();
