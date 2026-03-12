<?php

namespace App\Services;

use App\Models\ConnectedAccount;
use App\Models\Video;
use Illuminate\Support\Facades\Http;

class TikTokService
{
    public function uploadVideo(Video $video, ConnectedAccount $account)
    {
        $this->refreshTokenIfNeeded($account);

        // Mocked TikTok Content Posting API process
        // Step 1: Initialize upload /video/upload/
        // Step 2: Upload chunks
        // Step 3: Publish with title and #hashtags

        $video->postLogs()->create([
            'platform' => 'tiktok',
            'status' => 'success',
            'posted_at' => now()
        ]);

        $video->update(['status' => 'Published']);

        return true;
    }

    protected function refreshTokenIfNeeded(ConnectedAccount $account)
    {
        if ($account->expires_at && $account->expires_at->isPast()) {
            // refresh
        }
    }
}
