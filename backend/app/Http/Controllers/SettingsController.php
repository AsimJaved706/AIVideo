<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        // You could fetch API keys from a user settings JSON column here
        return view('settings.index', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gemini_api_key' => 'nullable|string',
            'elevenlabs_api_key' => 'nullable|string',
            'runwayml_api_key' => 'nullable|string',
            'voice_id' => 'nullable|string|max:255',
            'voice_model' => 'required|in:eleven_multilingual_v2,eleven_turbo_v2_5',
            'voice_output_format' => 'required|in:mp3_44100_128,mp3_44100_192',
            'fallback_language' => 'required|in:en,es,fr,de,hi',
            'voice_stability' => 'required|numeric|min:0|max:1',
            'voice_similarity_boost' => 'required|numeric|min:0|max:1',
            'voice_style' => 'required|numeric|min:0|max:1',
            'voice_speaker_boost' => 'nullable|boolean',
            'script_tone' => 'required|in:natural,educational,storytelling,persuasive',
            'visual_style' => 'required|in:documentary,cinematic,ugc,commercial',
            'seed_image_style' => 'required|in:photorealistic,lifestyle,studio,dramatic',
            'image_lighting_style' => 'required|in:natural_daylight,soft_studio,golden_hour,moody',
            'image_color_palette' => 'required|in:neutral,warm,cool,high_contrast',
            'image_framing' => 'required|in:close_up,medium_shot,wide_shot',
            'image_negative_prompt' => 'nullable|string|max:1000',
            'camera_motion' => 'required|in:gentle_handheld,slow_push_in,smooth_pan,static',
            'runway_duration' => 'required|integer|in:5,10',
            'storage_preference' => 'required|in:local,s3',
        ]);

        $validated['voice_speaker_boost'] = $request->boolean('voice_speaker_boost');

        $updates = collect($validated)
            ->reject(fn ($value, $key) => in_array($key, ['gemini_api_key', 'elevenlabs_api_key', 'runwayml_api_key', 'voice_id'], true) && ($value === null || $value === ''))
            ->toArray();

        $updates['storage_preference'] = $validated['storage_preference'];

        if (count($updates) > 0) {
            $request->user()->update($updates);
        }

        return back()->with('success', 'Generation settings updated successfully.');
    }
}
