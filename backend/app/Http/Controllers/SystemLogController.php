<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class SystemLogController extends Controller
{
    public function index(Request $request)
    {
        // Fetch videos with errors or failed status for the authenticated user
        $failedVideos = Video::whereHas('campaign', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })
            ->where(function ($q) {
                $q->where('status', 'Failed')
                    ->orWhereNotNull('error_message');
            })
            ->with('campaign')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('logs.index', compact('failedVideos'));
    }
}
