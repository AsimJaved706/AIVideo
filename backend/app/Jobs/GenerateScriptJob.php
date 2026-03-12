<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Video;

class GenerateScriptJob implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function handle(): void
    {
        try {
            $payload = [
                'video_id' => $this->video->id,
                'campaign_id' => $this->video->campaign_id,
                'topic' => $this->video->campaign->topic,
                'video_length' => $this->video->campaign->video_length,
                'storage_preference' => $this->video->campaign->user->storage_preference ?? 'local',
                'gemini_api_key' => $this->video->campaign->user->gemini_api_key ?? '',
                'elevenlabs_api_key' => $this->video->campaign->user->elevenlabs_api_key ?? '',
                'runwayml_api_key' => $this->video->campaign->user->runwayml_api_key ?? '',
                'voice_id' => $this->video->campaign->user->voice_id ?: '21m00Tcm4TlvDq8ikWAM',
                'voice_model' => $this->video->campaign->user->voice_model ?? 'eleven_multilingual_v2',
                'voice_output_format' => $this->video->campaign->user->voice_output_format ?? 'mp3_44100_128',
                'fallback_language' => $this->video->campaign->user->fallback_language ?? 'en',
                'voice_stability' => $this->video->campaign->user->voice_stability ?? 0.35,
                'voice_similarity_boost' => $this->video->campaign->user->voice_similarity_boost ?? 0.80,
                'voice_style' => $this->video->campaign->user->voice_style ?? 0.15,
                'voice_speaker_boost' => $this->video->campaign->user->voice_speaker_boost ?? true,
                'script_tone' => $this->video->campaign->user->script_tone ?? 'natural',
                'visual_style' => $this->video->campaign->user->visual_style ?? 'documentary',
                'seed_image_style' => $this->video->campaign->user->seed_image_style ?? 'photorealistic',
                'image_lighting_style' => $this->video->campaign->user->image_lighting_style ?? 'natural_daylight',
                'image_color_palette' => $this->video->campaign->user->image_color_palette ?? 'neutral',
                'image_framing' => $this->video->campaign->user->image_framing ?? 'medium_shot',
                'image_negative_prompt' => $this->video->campaign->user->image_negative_prompt ?? '',
                'camera_motion' => $this->video->campaign->user->camera_motion ?? 'gentle_handheld',
                'runway_duration' => $this->video->campaign->user->runway_duration ?? 5,
            ];

            $workerUrl = rtrim(env('PYTHON_WORKER_URL', 'http://127.0.0.1:8001'), '/');

            Log::info('Dispatching generation request to python worker', [
                'video_id' => $this->video->id,
                'campaign_id' => $this->video->campaign_id,
                'worker_url' => $workerUrl,
            ]);

            $response = Http::timeout(30)
                ->connectTimeout(10)
                ->retry(2, 500)
                ->post("{$workerUrl}/generate", $payload);

            if (!$response->successful()) {
                throw new \Exception("Failed to start generation on worker: " . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error('GenerateScriptJob failed', [
                'video_id' => $this->video->id,
                'campaign_id' => $this->video->campaign_id,
                'error' => $e->getMessage(),
            ]);

            $this->video->update([
                'status' => 'Failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
