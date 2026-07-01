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
        $totalViews = NewsAnalytics::sum('total_views') ?? 0;
        $totalEngagement = NewsAnalytics::sum('social_shares') ?? 0;
        $averageReadTime = NewsAnalytics::avg('average_time_on_page') ?? 0;
        $totalClicks = NewsAnalytics::sum('daily_views') ?? 0;

        // Top performing news (by views)
        $topNews = News::with('category')
            ->where('status', 'published')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // Total news & users for stat cards
        $totalNews  = \App\Models\News::where('status', 'published')->count();
        $totalUsers = \App\Models\User::count();

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

        // Traffic source breakdown
        $sourceBreakdown = VisitorAnalytic::selectRaw('referrer_source, COUNT(*) as total')
            ->groupBy('referrer_source')
            ->orderByDesc('total')
            ->get()
            ->map(function ($row) {
                $labels = [
                    'google'   => ['label' => 'Google', 'icon' => 'bi-google', 'color' => '#4285F4'],
                    'facebook' => ['label' => 'Facebook', 'icon' => 'bi-facebook', 'color' => '#1877F2'],
                    'twitter'  => ['label' => 'Twitter/X', 'icon' => 'bi-twitter-x', 'color' => '#000'],
                    'bing'     => ['label' => 'Bing', 'icon' => 'bi-search', 'color' => '#008272'],
                    'chatgpt'  => ['label' => 'ChatGPT', 'icon' => 'bi-chat-dots', 'color' => '#10a37f'],
                    'whatsapp' => ['label' => 'WhatsApp', 'icon' => 'bi-whatsapp', 'color' => '#25D366'],
                    'linkedin' => ['label' => 'LinkedIn', 'icon' => 'bi-linkedin', 'color' => '#0A66C2'],
                    'direct'   => ['label' => 'Direct', 'icon' => 'bi-link-45deg', 'color' => '#6c757d'],
                    'other'    => ['label' => 'Other', 'icon' => 'bi-globe', 'color' => '#adb5bd'],
                ];
                $meta = $labels[$row->referrer_source] ?? $labels['other'];
                $row->label = $meta['label'];
                $row->icon  = $meta['icon'];
                $row->color = $meta['color'];
                return $row;
            });

        $totalVisitors = $sourceBreakdown->sum('total') ?: 1;

        // Device breakdown from visitor_analytics
        $deviceBreakdown = VisitorAnalytic::selectRaw('visitor_device, COUNT(*) as total')
            ->groupBy('visitor_device')
            ->get()
            ->keyBy('visitor_device');
        $totalDevices = $deviceBreakdown->sum('total') ?: 1;

        return view('admin.analytics.index', [
            'totalViews'       => $totalViews,
            'totalEngagement'  => $totalEngagement,
            'averageReadTime'  => $averageReadTime,
            'totalClicks'      => $totalClicks,
            'totalNews'        => $totalNews,
            'totalUsers'       => $totalUsers,
            'topNews'          => $topNews,
            'categoryAnalytics'=> $categoryAnalytics,
            'recentVisitors'   => $recentVisitors,
            'sourceBreakdown'  => $sourceBreakdown,
            'totalVisitors'    => $totalVisitors,
            'deviceBreakdown'  => $deviceBreakdown,
            'totalDevices'     => $totalDevices,
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
