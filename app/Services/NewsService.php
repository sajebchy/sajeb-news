<?php

namespace App\Services;

use App\Models\News;
use App\Models\Category;
use App\Models\NewsAnalytics;
use Illuminate\Pagination\Paginator;

class NewsService
{
    /**
     * Get published news with pagination
     */
    public function getPublishedNews($perPage = 15)
    {
        return News::published()
            ->with('category', 'author')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get featured news
     */
    public function getFeaturedNews($limit = 5)
    {
        return News::featured()
            ->with('category', 'author')
            ->limit($limit)
            ->get();
    }

    /**
     * Get breaking news
     */
    public function getBreakingNews($limit = 3)
    {
        return News::breakingNews()
            ->with('category', 'author')
            ->limit($limit)
            ->get();
    }

    /**
     * Get news by category
     */
    public function getNewsByCategory(Category $category, $perPage = 15)
    {
        return News::published()
            ->where('category_id', $category->id)
            ->with('category', 'author')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get news by tag
     */
    public function getNewsByTag($tag, $perPage = 15)
    {
        return News::published()
            ->withAllTags([$tag])
            ->with('category', 'author')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Search news
     */
    public function searchNews($query, $perPage = 15)
    {
        return News::published()
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->with('category', 'author')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get related news
     */
    public function getRelatedNews(News $news, $limit = 5)
    {
        return News::published()
            ->where('id', '!=', $news->id)
            ->where('category_id', $news->category_id)
            ->with('category', 'author')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get trending news
     */
    public function getTrendingNews($days = 7, $limit = 10)
    {
        return News::published()
            ->whereDate('published_at', '>=', now()->subDays($days))
            ->with('category', 'author')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Increment news views
     */
    public function incrementViews(News $news)
    {
        $news->increment('views');
        
        // Update analytics
        $date = now()->toDateString();
        $analytics = NewsAnalytics::where('news_id', $news->id)
            ->where('date', $date)
            ->first();

        if ($analytics) {
            $analytics->increment('daily_views');
        } else {
            NewsAnalytics::create([
                'news_id' => $news->id,
                'daily_views' => 1,
                'total_views' => $news->views,
                'date' => $date,
            ]);
        }
    }

    /**
     * Archive old news
     */
    public function archiveOldNews($days = 90)
    {
        return News::where('status', 'published')
            ->where('published_at', '<', now()->subDays($days))
            ->update(['status' => 'archived']);
    }

    /**
     * Publish scheduled news
     */
    public function publishScheduledNews()
    {
        return News::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->update([
                'status' => 'published',
                'published_at' => now(),
            ]);
    }

    /**
     * Get news statistics
     */
    public function getStatistics()
    {
        return [
            'total_published' => News::published()->count(),
            'total_drafts' => News::where('status', 'draft')->count(),
            'total_scheduled' => News::where('status', 'scheduled')->count(),
            'total_views' => News::sum('views'),
            'avg_views' => round(News::published()->avg('views'), 2),
            'total_categories' => Category::where('is_active', true)->count(),
        ];
    }
}
