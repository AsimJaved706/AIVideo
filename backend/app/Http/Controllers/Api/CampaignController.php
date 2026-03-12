<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = $request->user()->campaigns()->orderBy('created_at', 'desc')->get();
        return response()->json($campaigns);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'topic' => 'required|string',
            'niche' => 'nullable|string',
            'language' => 'nullable|string',
            'video_length' => 'nullable|integer',
            'voice_style' => 'nullable|string',
            'target_platform' => 'nullable|string',
            'video_style' => 'nullable|string',
            'posting_schedule' => 'nullable|string'
        ]);

        $campaign = $request->user()->campaigns()->create($validated);
        return response()->json($campaign, 201);
    }

    public function show(string $id, Request $request)
    {
        $campaign = $request->user()->campaigns()->with('videos')->findOrFail($id);
        return response()->json($campaign);
    }

    public function update(Request $request, string $id)
    {
        $campaign = $request->user()->campaigns()->findOrFail($id);
        $campaign->update($request->all());
        return response()->json($campaign);
    }

    public function destroy(string $id, Request $request)
    {
        $campaign = $request->user()->campaigns()->findOrFail($id);
        $campaign->delete();
        return response()->noContent();
    }

    public function generate(string $id, Request $request)
    {
        $campaign = $request->user()->campaigns()->findOrFail($id);
        $video = $campaign->videos()->create(['status' => 'Generating Scripts']);
        $campaign->update(['status' => 'Generating']);
        \App\Jobs\GenerateScriptJob::dispatch($video);
        return response()->json(['message' => 'Generation started', 'video' => $video]);
    }
}
