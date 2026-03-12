<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = $request->user()->campaigns()->orderBy('created_at', 'desc')->get();
        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'topic' => 'required|string',
            'niche' => 'nullable|string',
            'target_platform' => 'nullable|string',
            'video_length' => 'nullable|integer',
            'quantity' => 'required|integer|min:1|max:30',
        ]);

        $validated['status'] = 'Generating';

        // Extract quantity from the validated array so it's not passed into the mass assignment for campaign
        $quantity = $validated['quantity'];
        unset($validated['quantity']);

        $campaign = $request->user()->campaigns()->create($validated);

        for ($i = 0; $i < $quantity; $i++) {
            $video = $campaign->videos()->create(['status' => 'Generating Scripts']);
            \App\Jobs\GenerateScriptJob::dispatch($video);
        }

        return redirect()->route('campaigns.show', $campaign->id)->with('success', 'Campaign created and generation started!');
    }

    public function show($id, Request $request)
    {
        $campaign = $request->user()->campaigns()->with(['videos.mediaAssets', 'videos.postLogs'])->findOrFail($id);
        return view('campaigns.show', compact('campaign'));
    }

    public function retryVideo($id, Request $request)
    {
        $video = \App\Models\Video::whereHas('campaign', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->findOrFail($id);

        $video->update([
            'status' => 'Generating Scripts',
            'error_message' => null
        ]);

        \App\Jobs\GenerateScriptJob::dispatch($video);

        return redirect()->back()->with('success', 'Video generation restarted successfully.');
    }
}
