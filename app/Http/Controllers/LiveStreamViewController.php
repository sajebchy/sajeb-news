<?php

namespace App\Http\Controllers;

use App\Models\LiveStream;

class LiveStreamViewController extends Controller
{
    /**
     * Display the live stream.
     */
    public function watch(LiveStream $stream)
    {
        // Check visibility
        if ($stream->visibility === 'private') {
            if (!auth()->check() || (auth()->id() !== $stream->user_id && !auth()->user()->hasRole('admin'))) {
                abort(403, 'This stream is private.');
            }
        }

        // Increment view count
        $stream->increment('view_count');

        return view('public.live-stream.watch', [
            'stream' => $stream,
        ]);
    }

    /**
     * Display all live streams.
     */
    public function index()
    {
        $liveStreams = LiveStream::where('status', 'live')
            ->where('visibility', 'public')
            ->latest('started_at')
            ->paginate(12);

        $upcomingStreams = LiveStream::where('status', 'pending')
            ->where('visibility', 'public')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at')
            ->limit(6)
            ->get();

        $featuredStreams = LiveStream::whereIn('status', ['live', 'ended'])
            ->where('visibility', 'public')
            ->where('is_featured', true)
            ->latest('started_at')
            ->limit(4)
            ->get();

        return view('public.live-stream.index', [
            'live_streams' => $liveStreams,
            'upcoming_streams' => $upcomingStreams,
            'featured_streams' => $featuredStreams,
        ]);
    }

    /**
     * Get live chat messages (API endpoint)
     */
    public function chatMessages(LiveStream $stream)
    {
        // This can be extended for real-time chat functionality
        return response()->json([
            'messages' => [],
        ]);
    }
}
