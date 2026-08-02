<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsSource;
use App\Services\NewsAggregatorService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class NewsSourceController extends Controller
{
    use LogsActivity;

    protected NewsAggregatorService $aggregator;

    public function __construct(NewsAggregatorService $aggregator)
    {
        $this->aggregator = $aggregator;
    }

    public function index()
    {
        $sources = NewsSource::withCount('items')->latest()->paginate(20);

        return view('admin.news-sources.index', compact('sources'));
    }

    public function create()
    {
        return view('admin.news-sources.create', ['source' => new NewsSource()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'site_url' => 'required|url|max:500',
            'feed_url' => 'nullable|url|max:500',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $source = NewsSource::create($validated);

        $this->logActivity('created', 'NewsSource', $source->id);

        // First fetch immediately so the source shows content right away.
        $result = $this->aggregator->fetchSource($source->fresh());

        $msg = 'সোর্স যুক্ত হয়েছে।';
        if ($result['error']) {
            $msg .= ' তবে ফিড লোডে সমস্যা হয়েছে — URL যাচাই করুন।';
        } elseif ($result['new'] > 0) {
            $msg .= " {$result['new']}টি খবর টেনে আনা হয়েছে।";
        }

        return redirect()->route('admin.news-sources.index')->with('success', $msg);
    }

    public function edit(NewsSource $news_source)
    {
        return view('admin.news-sources.create', ['source' => $news_source]);
    }

    public function update(Request $request, NewsSource $news_source)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'site_url' => 'required|url|max:500',
            'feed_url' => 'nullable|url|max:500',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $news_source->update($validated);

        $this->logActivity('updated', 'NewsSource', $news_source->id);

        return redirect()->route('admin.news-sources.index')->with('success', 'সোর্স আপডেট হয়েছে।');
    }

    public function destroy(NewsSource $news_source)
    {
        $this->logActivity('deleted', 'NewsSource', $news_source->id);
        $news_source->delete();

        return redirect()->route('admin.news-sources.index')->with('success', 'সোর্স ও এর খবরগুলো মুছে ফেলা হয়েছে।');
    }

    public function toggle(NewsSource $news_source)
    {
        $news_source->update(['is_active' => !$news_source->is_active]);

        return back()->with('success', 'সোর্সের অবস্থা পরিবর্তন হয়েছে।');
    }
}
