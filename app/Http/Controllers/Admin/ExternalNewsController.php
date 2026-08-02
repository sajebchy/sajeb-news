<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExternalNewsItem;
use App\Models\NewsSource;
use App\Services\NewsAggregatorService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ExternalNewsController extends Controller
{
    protected NewsAggregatorService $aggregator;

    public function __construct(NewsAggregatorService $aggregator)
    {
        $this->aggregator = $aggregator;
    }

    /**
     * List pulled external news. Fetches on visit when sources are stale.
     */
    public function index(Request $request)
    {
        // Guard: if the aggregator tables haven't been migrated on this
        // environment yet, show a clear setup notice instead of a 500.
        if (!Schema::hasTable('news_sources') || !Schema::hasTable('external_news_items')) {
            return view('admin.external-news.index', [
                'items' => new LengthAwarePaginator([], 0, 15),
                'sources' => collect(),
                'unreadCount' => 0,
                'setupNeeded' => true,
            ]);
        }

        // Fetch-on-visit: if any active source is stale (never fetched or >15 min old).
        try {
            $stale = NewsSource::active()
                ->where(function ($q) {
                    $q->whereNull('last_fetched_at')
                      ->orWhere('last_fetched_at', '<', now()->subMinutes(15));
                })
                ->exists();

            if ($stale && !$request->boolean('nofetch')) {
                $this->aggregator->fetchAllActive();
            }
        } catch (\Throwable $e) {
            Log::error('External news fetch-on-visit failed: ' . $e->getMessage());
        }

        $query = ExternalNewsItem::with('source')->latest('published_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('keywords', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($sourceId = $request->input('source')) {
            $query->where('news_source_id', $sourceId);
        }

        if ($request->boolean('unread')) {
            $query->where('is_read', false);
        }

        $items = $query->paginate(15)->appends($request->only(['search', 'source', 'unread']));

        $sources = NewsSource::orderBy('name')->get();
        $unreadCount = ExternalNewsItem::where('is_read', false)->count();

        return view('admin.external-news.index', compact('items', 'sources', 'unreadCount'));
    }

    /**
     * View a single item's full content.
     */
    public function show(ExternalNewsItem $external_news)
    {
        if (!$external_news->is_read) {
            $external_news->update(['is_read' => true]);
        }

        $external_news->load('source');

        return view('admin.external-news.show', ['item' => $external_news]);
    }

    /**
     * Force a fetch of all active sources.
     */
    public function refresh()
    {
        $result = $this->aggregator->fetchAllActive();

        $msg = $result['new'] > 0
            ? "{$result['new']}টি নতুন খবর পাওয়া গেছে।"
            : 'নতুন কোনো খবর নেই।';

        if ($result['errors'] > 0) {
            $msg .= " ({$result['errors']}টি সোর্সে সমস্যা হয়েছে — সোর্স ম্যানেজ দেখুন।)";
        }

        return redirect()->route('admin.external-news.index', ['nofetch' => 1])
            ->with('success', $msg);
    }

    public function markRead(ExternalNewsItem $external_news)
    {
        $external_news->update(['is_read' => true]);

        return back()->with('success', 'পঠিত হিসেবে চিহ্নিত করা হয়েছে।');
    }

    public function destroy(ExternalNewsItem $external_news)
    {
        $external_news->delete();

        return back()->with('success', 'খবরটি মুছে ফেলা হয়েছে।');
    }
}
