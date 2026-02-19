<?php

namespace App\Services;

use App\Models\Advertisement;
use Illuminate\Pagination\Paginator;

class AdService
{
    /**
     * Get ads for a specific placement
     */
    public function getAdsByPlacement(string $placement, string $device = 'desktop', int $limit = null)
    {
        $query = Advertisement::forPlacement($placement)
            ->forDevice($device)
            ->orderBy('display_order', 'asc');

        if ($limit) {
            return $query->limit($limit)->get();
        }

        return $query->get();
    }

    /**
     * Get random ad for a placement
     */
    public function getRandomAdForPlacement(string $placement, string $device = 'desktop')
    {
        return Advertisement::forPlacement($placement)
            ->forDevice($device)
            ->inRandomOrder()
            ->first();
    }

    /**
     * Record an ad view/impression
     */
    public function recordView(Advertisement $ad)
    {
        $ad->recordView();
        
        // Log activity
        \Log::info('Ad viewed', [
            'ad_id' => $ad->id,
            'ad_name' => $ad->name,
            'placement' => $ad->placement,
        ]);
    }

    /**
     * Record an ad click with tracking
     */
    public function recordClick(Advertisement $ad, array $trackingData = [])
    {
        $ad->recordClick();

        // Log the click
        \Log::info('Ad clicked', array_merge([
            'ad_id' => $ad->id,
            'ad_name' => $ad->name,
            'placement' => $ad->placement,
        ], $trackingData));
    }

    /**
     * Get ad statistics
     */
    public function getStatistics(Advertisement $ad)
    {
        return [
            'id' => $ad->id,
            'name' => $ad->name,
            'views' => $ad->views,
            'clicks' => $ad->clicks,
            'ctr' => $ad->ctr,
            'placement' => $ad->placement,
            'is_active' => $ad->is_active,
        ];
    }

    /**
     * Check if ad has reached daily limits
     */
    public function hasReachedDailyLimit(Advertisement $ad): bool
    {
        if (!$ad->daily_impression_limit) {
            return false;
        }

        // Get impressions for today
        $today = now()->startOfDay();
        $todayViews = \DB::table('advertisements')
            ->where('id', $ad->id)
            ->where('updated_at', '>=', $today)
            ->value('views');

        return $todayViews >= $ad->daily_impression_limit;
    }

    /**
     * Check if ad has reached click limit
     */
    public function hasReachedClickLimit(Advertisement $ad): bool
    {
        if (!$ad->max_clicks_per_day) {
            return false;
        }

        // Get clicks for today
        $today = now()->startOfDay();
        $todayClicks = \DB::table('advertisements')
            ->where('id', $ad->id)
            ->where('updated_at', '>=', $today)
            ->value('clicks');

        return $todayClicks >= $ad->max_clicks_per_day;
    }

    /**
     * Calculate estimated cost
     */
    public function calculateCost(Advertisement $ad): float
    {
        $cost = 0;

        if ($ad->cpc_amount) {
            $cost += $ad->clicks * $ad->cpc_amount;
        }

        if ($ad->cpm_amount) {
            $cost += ($ad->views / 1000) * $ad->cpm_amount;
        }

        return round($cost, 2);
    }

    /**
     * Get all available placements
     */
    public function getAvailablePlacements()
    {
        return [
            'within_news' => 'Within News Article',
            'homepage_banner' => 'Homepage Banner',
            'homepage_popup' => 'Homepage Popup',
            'homepage_header' => 'Homepage Header',
            'homepage_footer' => 'Homepage Footer',
            'category_page' => 'Category Page',
            'sidebar' => 'Sidebar',
            'between_comments' => 'Between Comments',
        ];
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats()
    {
        return [
            'total_ads' => Advertisement::count(),
            'active_ads' => Advertisement::active()->count(),
            'total_views' => Advertisement::sum('views') ?? 0,
            'total_clicks' => Advertisement::sum('clicks') ?? 0,
            'total_spent' => Advertisement::sum('total_spent') ?? 0,
        ];
    }

    /**
     * Get trending ads
     */
    public function getTrendingAds(int $limit = 5)
    {
        return Advertisement::active()
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Filter ads by categories
     */
    public function getAdsByCategories(array $categoryIds, string $placement = null, int $limit = null)
    {
        $query = Advertisement::active()
            ->where(function ($q) use ($categoryIds) {
                $q->whereJsonContains('target_categories', $categoryIds);
                foreach ($categoryIds as $catId) {
                    $q->orWhereJsonContains('target_categories', $catId);
                }
            });

        if ($placement) {
            $query->where('placement', $placement);
        }

        if ($limit) {
            return $query->limit($limit)->get();
        }

        return $query->get();
    }

    /**
     * Get tracked URL for ad
     */
    public function getTrackedUrl(Advertisement $ad, array $additionalParams = []): string
    {
        return $ad->buildTrackedUrl($additionalParams);
    }
}
