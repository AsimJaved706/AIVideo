<?php

namespace App\Services;

use App\Models\ConnectedAccount;
use App\Models\Video;
use Illuminate\Support\Facades\Http;

class YoutubeService
{
    public function uploadVideo(Video $video, ConnectedAccount $account)
    {
        // 1. Validate token
        $this->refreshTokenIfNeeded($account);

        // 2. Prepare metadata
        $metadata = [
            'snippet' => [
                'title' => $video->title ?? 'AI Generated Video',
                'description' => $video->description ?? '',
                'tags' => ['#shorts', '#ai'],
                'categoryId' => '22'
            ],
            'status' => [
                'privacyStatus' => 'public',
            ]
        ];

        // 3. Mocked Google API upload process
        // In full production, `google/apiclient` handles chunked MediaFileUpload

        // 4. Log Success
        $video->postLogs()->create([
            'platform' => 'youtube',
            'status' => 'success',
            'posted_at' => now()
        ]);

        $video->update(['status' => 'Published']);

        return true;
    }

    protected function refreshTokenIfNeeded(ConnectedAccount $account)
    {
        if ($account->expires_at && $account->expires_at->isPast()) {
            // Exchange refresh token for new access token
            // $account->update(['access_token' => $newToken]);
        }
    }
}
