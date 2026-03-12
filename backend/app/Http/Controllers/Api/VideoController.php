<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $videos = \App\Models\Video::whereHas('campaign', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with('campaign')->orderBy('created_at', 'desc')->get();
        return response()->json($videos);
    }

    public function show(string $id, Request $request)
    {
        $video = \App\Models\Video::whereHas('campaign', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->with(['campaign', 'mediaAssets', 'postLogs', 'analytics'])->findOrFail($id);
        return response()->json($video);
    }

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            's3_url' => 'nullable|string',
            'error_message' => 'nullable|string'
        ]);
        $video = \App\Models\Video::findOrFail($id);
        $video->update($validated);
        return response()->json(['message' => 'Status updated', 'video' => $video]);
    }

    public function uploadMedia(Request $request, string $id)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            's3_path' => 'required|string',
            'campaign_id' => 'nullable|integer'
        ]);
        $video = \App\Models\Video::findOrFail($id);
        $asset = $video->mediaAssets()->create($validated);
        return response()->json(['message' => 'Media asset recorded', 'asset' => $asset]);
    }
}
