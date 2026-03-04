<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsAnalytics;
use App\Models\User;
use App\Models\VisitorAnalytic;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        // Get statistics
        $totalViews = NewsAnalytics::sum('views') ?? 0;
        $totalEngagement = NewsAnalytics::sum('engagement_score') ?? 0;
        $averageReadTime = NewsAnalytics::avg('avg_time_on_page') ?? 0;
        $totalClicks = NewsAnalytics::sum('clicks') ?? 0;

        // Top performing news
        $topNews = News::with('category')
            ->latest()
            ->limit(10)
            ->get();

        // Category performance
        $categoryAnalytics = \DB::table('news')
            ->join('categories', 'news.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(news.id) as count, SUM(news.views) as total_views')
            ->groupBy('categories.id', 'categories.name')
            ->limit(10)
            ->get();

        // Recent visitor activity
        $recentVisitors = VisitorAnalytic::with('news')
            ->latest('visit_date')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', [
            'totalViews' => $totalViews,
            'totalEngagement' => $totalEngagement,
            'averageReadTime' => $averageReadTime,
            'totalClicks' => $totalClicks,
            'topNews' => $topNews,
            'categoryAnalytics' => $categoryAnalytics,
            'recentVisitors' => $recentVisitors,
        ]);
    }

    /**
     * Display detailed visitor analytics for a specific news article.
     */
    public function show(Request $request, $newsId)
    {
        $news = News::findOrFail($newsId);
        
        $query = VisitorAnalytic::where('news_id', $newsId)
            ->with(['news', 'nextNews'])
            ->latest('visit_date');

        // Search by IP or country
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('visitor_ip', 'like', "%{$search}%")
                  ->orWhere('visitor_country', 'like', "%{$search}%")
                  ->orWhere('visitor_city', 'like', "%{$search}%");
            });
        }

        // Filter by referrer source
        if ($request->filled('source')) {
            $query->where('referrer_source', $request->get('source'));
        }

        $visitors = $query->paginate(25);

        // Calculate statistics
        $avgReadingTime = $visitors->isEmpty() ? 0 : round($visitors->sum('time_spent_seconds') / $visitors->total() / 60, 1);
        $completedReading = $visitors->isEmpty() ? 0 : round(($visitors->where('completed_reading', true)->count() / $visitors->total()) * 100);
        
        // Get top referrer source
        $topSource = VisitorAnalytic::where('news_id', $newsId)
            ->selectRaw('referrer_source, COUNT(*) as count')
            ->groupBy('referrer_source')
            ->orderByDesc('count')
            ->first()
            ->referrer_source ?? 'Direct';

        return view('admin.analytics.show', [
            'news' => $news,
            'visitors' => $visitors,
            'avgReadingTime' => $avgReadingTime,
            'completedReading' => $completedReading,
            'topSource' => $topSource,
        ]);
    }

    /**
     * Display detailed information for a specific visitor
     */
    public function visitorDetail($newsId, $visitorId)
    {
        $visitor = VisitorAnalytic::with(['news', 'nextNews'])
            ->findOrFail($visitorId);

        $news = News::findOrFail($newsId);

        // Get visitor's other visits and journey
        $visitorJourney = VisitorAnalytic::where('visitor_ip', $visitor->visitor_ip)
            ->with('news')
            ->orderBy('visit_date', 'asc')
            ->get();

        return view('admin.analytics.visitor-detail', [
            'news' => $news,
            'visitor' => $visitor,
            'visitorJourney' => $visitorJourney,
        ]);
    }
}
