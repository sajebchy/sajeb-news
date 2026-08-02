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
    public function index(Request $request)
    {
        // ── Period filter (applies to visitor analytics) ──
        $period = in_array($request->get('period'), ['today', 'week', 'month', 'year'], true)
            ? $request->get('period')
            : 'all';

        $startDate = match ($period) {
            'today' => now()->startOfDay(),
            'week'  => now()->subDays(7),
            'month' => now()->subDays(30),
            'year'  => now()->startOfYear(),
            default => null,
        };

        // Helper: a fresh VisitorAnalytic query with the period applied.
        $visitorQuery = fn () => VisitorAnalytic::query()
            ->when($startDate, fn ($q) => $q->where('visit_date', '>=', $startDate));

        // ── All-time totals (not period-based) ──
        $totalViews = News::sum('views') ?? 0;
        $totalNews  = News::where('status', 'published')->count();
        $totalUsers = User::count();

        // Top performing news (by views)
        $topNews = News::with('category')
            ->where('status', 'published')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // Category performance
        $categoryAnalytics = \DB::table('news')
            ->join('categories', 'news.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(news.id) as count, SUM(news.views) as total_views')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_views')
            ->limit(10)
            ->get();

        // ── Visitor analytics (period-filtered) ──
        $totalVisitors = $visitorQuery()->count();

        // Recent visitor activity
        $recentVisitors = $visitorQuery()
            ->with('news')
            ->latest('visit_date')
            ->limit(15)
            ->get();

        // Traffic source breakdown
        $sourceBreakdown = $visitorQuery()
            ->selectRaw('referrer_source, COUNT(*) as total')
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

        // Device breakdown from visitor_analytics (period-filtered)
        $deviceBreakdown = $visitorQuery()
            ->selectRaw('visitor_device, COUNT(*) as total')
            ->groupBy('visitor_device')
            ->get()
            ->keyBy('visitor_device');
        $totalDevices = $deviceBreakdown->sum('total');

        return view('admin.analytics.index', [
            'totalViews'       => $totalViews,
            'totalNews'        => $totalNews,
            'totalUsers'       => $totalUsers,
            'topNews'          => $topNews,
            'categoryAnalytics'=> $categoryAnalytics,
            'recentVisitors'   => $recentVisitors,
            'sourceBreakdown'  => $sourceBreakdown,
            'totalVisitors'    => $totalVisitors,
            'deviceBreakdown'  => $deviceBreakdown,
            'totalDevices'     => $totalDevices,
            'period'           => $period,
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
        
        // Get top referrer source (null-safe when there are no visitors yet)
        $topSource = VisitorAnalytic::where('news_id', $newsId)
            ->selectRaw('referrer_source, COUNT(*) as count')
            ->groupBy('referrer_source')
            ->orderByDesc('count')
            ->first()?->referrer_source ?? 'Direct';

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
