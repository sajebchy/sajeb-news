<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveStream;
use App\Services\ImageOptimizer;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LiveStreamController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of live streams.
     */
    public function index()
    {
        $streams = LiveStream::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('admin.live-streams.index', [
            'streams' => $streams,
        ]);
    }

    /**
     * Show the form for creating a new live stream.
     */
    public function create()
    {
        return view('admin.live-streams.create', [
            'stream' => new LiveStream(),
        ]);
    }

    /**
     * Store a newly created live stream.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'category' => 'nullable|string|max:100',
            'visibility' => 'required|in:public,private,unlisted',
            'scheduled_at' => 'nullable|date|after:now',
            'embed_url' => 'required|string|max:5000',
            'thumbnail' => 'nullable|image|max:1024',
            'stream_tags' => 'nullable|string',
            'allow_comments' => 'boolean',
            'allow_chat' => 'boolean',
        ]);

        // Validate that the embed link is a supported YouTube/Facebook link
        $probe = new LiveStream(['embed_url' => $validated['embed_url']]);
        if (!$probe->getEmbedSrc()) {
            return back()->withInput()->withErrors([
                'embed_url' => 'শুধুমাত্র YouTube বা Facebook এর ভিডিও/লাইভ লিংক অথবা এমবেড কোড ব্যবহার করুন।',
            ]);
        }

        $validated['user_id'] = auth()->id();

        // Handle status: scheduled for later, otherwise go live immediately
        if ($validated['scheduled_at'] ?? null) {
            $validated['status'] = 'pending';
        } else {
            $validated['status'] = 'live';
            $validated['started_at'] = now();
        }

        // Handle thumbnail upload with optimization
        if ($request->hasFile('thumbnail')) {
            $optimizer = new ImageOptimizer();
            $validated['thumbnail'] = $optimizer->optimize(
                $request->file('thumbnail'),
                'thumbnail',
                'thumbnails/live-streams'
            );
        }

        // Handle tags
        if (!empty($validated['stream_tags'])) {
            $validated['stream_tags'] = array_map('trim', explode(',', $validated['stream_tags']));
        }

        // Create stream
        $stream = LiveStream::create($validated);

        // Log activity
        $this->logActivity('created', 'LiveStream', $stream->id, [
            'title' => $stream->title,
            'status' => $stream->status,
            'platform' => $stream->getEmbedPlatform(),
        ]);

        return redirect()
            ->route('admin.live-streams.show', $stream)
            ->with('success', 'Live stream created successfully!');
    }

    /**
     * Display the specified live stream.
     */
    public function show(LiveStream $stream)
    {
        // Check authorization
        if ($stream->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('admin.live-streams.show', [
            'stream' => $stream,
        ]);
    }

    /**
     * Show the form for editing the live stream.
     */
    public function edit(LiveStream $stream)
    {
        // Check authorization
        if ($stream->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('admin.live-streams.edit', [
            'stream' => $stream,
        ]);
    }

    /**
     * Update the specified live stream.
     */
    public function update(Request $request, LiveStream $stream)
    {
        // Check authorization
        if ($stream->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'category' => 'nullable|string|max:100',
            'visibility' => 'required|in:public,private,unlisted',
            'embed_url' => 'required|string|max:5000',
            'thumbnail' => 'nullable|image|max:1024',
            'stream_tags' => 'nullable|string',
            'allow_comments' => 'boolean',
            'allow_chat' => 'boolean',
        ]);

        // Validate that the embed link is a supported YouTube/Facebook link
        $probe = new LiveStream(['embed_url' => $validated['embed_url']]);
        if (!$probe->getEmbedSrc()) {
            return back()->withInput()->withErrors([
                'embed_url' => 'শুধুমাত্র YouTube বা Facebook এর ভিডিও/লাইভ লিংক অথবা এমবেড কোড ব্যবহার করুন।',
            ]);
        }

        // Handle thumbnail upload with optimization
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($stream->thumbnail && \Storage::disk('public')->exists($stream->thumbnail)) {
                \Storage::disk('public')->delete($stream->thumbnail);
            }
            
            $optimizer = new ImageOptimizer();
            $validated['thumbnail'] = $optimizer->optimize(
                $request->file('thumbnail'),
                'thumbnail',
                'thumbnails/live-streams'
            );
        }

        // Handle tags
        if (!empty($validated['stream_tags'])) {
            $validated['stream_tags'] = array_map('trim', explode(',', $validated['stream_tags']));
        }

        $stream->update($validated);

        // Log activity
        $this->logActivity('updated', 'LiveStream', $stream->id, [
            'title' => $stream->title,
            'status' => $stream->status,
        ]);

        return redirect()
            ->route('admin.live-streams.show', $stream)
            ->with('success', 'Live stream updated successfully!');
    }

    /**
     * Start the live stream.
     */
    public function start(LiveStream $stream)
    {
        // Check authorization
        if ($stream->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        if (!in_array($stream->status, ['draft', 'pending'])) {
            return back()->with('error', 'Stream cannot be started from current status.');
        }

        $stream->update([
            'status' => 'live',
            'started_at' => now(),
            'viewer_count' => 0,
            'peak_viewers' => 0,
        ]);

        // Log activity
        $this->logActivity('started', 'LiveStream', $stream->id, [
            'title' => $stream->title,
            'started_at' => $stream->started_at,
        ]);

        return back()->with('success', 'Live stream is now live on the website!');
    }

    /**
     * Stop the live stream.
     */
    public function stop(LiveStream $stream)
    {
        // Check authorization
        if ($stream->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        if ($stream->status !== 'live') {
            return back()->with('error', 'Stream is not currently live.');
        }

        $stream->update([
            'status' => 'ended',
            'ended_at' => now(),
            'duration_seconds' => $stream->started_at->diffInSeconds($stream->ended_at),
        ]);

        // Log activity
        $this->logActivity('stopped', 'LiveStream', $stream->id, [
            'title' => $stream->title,
            'ended_at' => $stream->ended_at,
            'duration' => $stream->getFormattedDuration(),
        ]);

        return back()->with('success', 'Live stream ended successfully!');
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(LiveStream $stream)
    {
        // Check authorization
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $stream->update([
            'is_featured' => !$stream->is_featured,
        ]);

        // Log activity
        $this->logActivity('toggled_featured', 'LiveStream', $stream->id, [
            'title' => $stream->title,
            'is_featured' => $stream->is_featured,
        ]);

        return back()->with('success', $stream->is_featured ? 'Stream featured!' : 'Stream unfeatured!');
    }

    /**
     * Delete the live stream.
     */
    public function destroy(LiveStream $stream)
    {
        // Check authorization
        if ($stream->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403);
        }

        if ($stream->status === 'live') {
            return back()->with('error', 'Cannot delete a stream that is currently live.');
        }

        $title = $stream->title;
        $stream->delete();

        // Log activity
        $this->logActivity('deleted', 'LiveStream', $stream->id, [
            'title' => $title,
        ]);

        return redirect()
            ->route('admin.live-streams.index')
            ->with('success', 'Live stream deleted successfully!');
    }
}
