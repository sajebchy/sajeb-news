<?php

namespace App\Services;

use App\Models\News;
use App\Models\NewsAnalytics;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get news performance data
     */
    public function getNewsPerformance(News $news)
    {
        return NewsAnalytics::where('news_id', $news->id)
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Get trending news for period
     */
    public function getTrendingNewsByPeriod($days = 7)
    {
        return NewsAnalytics::select('news_id', DB::raw('SUM(daily_views) as total_views'))
            ->where('date', '>=', now()->subDays($days)->toDateString())
            ->groupBy('news_id')
            ->orderBy('total_views', 'desc')
            ->with('news')
            ->limit(10)
            ->get();
    }

    /**
     * Get category analytics
     */
    public function getCategoryAnalytics($days = 30)
    {
        return DB::table('news_analytics')
            ->select(
                'categories.name',
                'categories.id',
                DB::raw('SUM(news_analytics.daily_views) as total_views'),
                DB::raw('COUNT(DISTINCT news_analytics.news_id) as total_articles')
            )
            ->join('news', 'news_analytics.news_id', '=', 'news.id')
            ->join('categories', 'news.category_id', '=', 'categories.id')
            ->where('news_analytics.date', '>=', now()->subDays($days)->toDateString())
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_views', 'desc')
            ->get();
    }

    /**
     * Get daily views chart data
     */
    public function getDailyViewsChart($news, $days = 30)
    {
        $analytics = NewsAnalytics::where('news_id', $news->id)
            ->where('date', '>=', now()->subDays($days)->toDateString())
            ->orderBy('date', 'asc')
            ->get();

        return [
            'labels' => $analytics->pluck('date')->map(fn($date) => $date->format('d M')),
            'data' => $analytics->pluck('daily_views'),
        ];
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        $today = now()->toDateString();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        return [
            'today' => [
                'views' => NewsAnalytics::where('date', $today)->sum('daily_views'),
                'articles' => News::published()->whereDate('published_at', $today)->count(),
            ],
            'this_month' => [
                'views' => NewsAnalytics::where('date', '>=', $thisMonth)->sum('daily_views'),
                'articles' => News::published()->where('published_at', '>=', $thisMonth)->count(),
            ],
            'last_month' => [
                'views' => NewsAnalytics::where('date', '>=', $lastMonth)
                    ->where('date', '<', $thisMonth)
                    ->sum('daily_views'),
                'articles' => News::published()
                    ->where('published_at', '>=', $lastMonth)
                    ->where('published_at', '<', $thisMonth)
                    ->count(),
            ],
            'all_time_views' => News::sum('views'),
            'avg_daily_views' => round(NewsAnalytics::avg('daily_views'), 2),
        ];
    }

    /**
     * Get engagement metrics
     */
    public function getEngagementMetrics($news)
    {
        $analytics = NewsAnalytics::where('news_id', $news->id)
            ->selectRaw('SUM(daily_views) as total_views')
            ->selectRaw('AVG(average_time_on_page) as avg_time')
            ->selectRaw('AVG(bounce_rate) as avg_bounce')
            ->selectRaw('SUM(social_shares) as total_shares')
            ->first();

        return [
            'total_views' => $analytics->total_views ?? 0,
            'avg_time_on_page' => round($analytics->avg_time ?? 0, 2),
            'bounce_rate' => round($analytics->avg_bounce ?? 0, 2),
            'social_shares' => $analytics->total_shares ?? 0,
            'engagement_rate' => round(
                (($analytics->total_shares ?? 0) / max($analytics->total_views ?? 1, 1)) * 100,
                2
            ),
        ];
    }

    /**
     * Track page view
     */
    public function trackPageView($news, $data = [])
    {
        $date = now()->toDateString();
        
        $analytics = NewsAnalytics::where('news_id', $news->id)
            ->where('date', $date)
            ->first();

        if ($analytics) {
            $analytics->update([
                'daily_views' => $analytics->daily_views + 1,
                'total_views' => $news->views + 1,
            ]);
        } else {
            NewsAnalytics::create([
                'news_id' => $news->id,
                'daily_views' => 1,
                'total_views' => $news->views + 1,
                'date' => $date,
                'average_time_on_page' => $data['time_on_page'] ?? 0,
                'scroll_depth' => $data['scroll_depth'] ?? 0,
                'bounce_rate' => $data['bounce_rate'] ?? 0,
            ]);
        }
    }
}
