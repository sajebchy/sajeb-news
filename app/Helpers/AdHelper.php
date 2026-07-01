<?php

namespace App\Helpers;

use App\Models\Advertisement;

class AdHelper
{
    /**
     * Get active ads for a specific placement
     */
    public static function getAdsByPlacement($placement)
    {
        return Advertisement::where('placement', $placement)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->orderBy('display_order', 'asc')
            ->get();
    }

    /**
     * Get random active ad for a specific placement
     */
    public static function getRandomAdByPlacement($placement)
    {
        return Advertisement::where('placement', $placement)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->inRandomOrder()
            ->first();
    }

    /**
     * Check if ads exist for placement
     */
    public static function hasAdsByPlacement($placement)
    {
        return Advertisement::where('placement', $placement)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->exists();
    }

    /**
     * Get paginated ads for between news listings
     */
    public static function getAdsBetweenNews($itemsPerAd = 5)
    {
        return Advertisement::where('placement', 'between_news_listings')
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->orderBy('display_order', 'asc')
            ->get();
    }

    /**
     * Render ad HTML
     */
    public static function renderAd($ad)
    {
        if (!$ad) return '';

        $html = '<div class="ad-wrapper" style="margin: 15px auto; text-align: center; width: 100%;">';
        $html .= '<p style="font-size: 12px; color: #999; margin: 5px 0; text-align: center;">বিজ্ঞাপন</p>';

        if ($ad->image_url) {
            $imageUrl = str_starts_with($ad->image_url, 'http') ? $ad->image_url : asset($ad->image_url);
            $html .= '<div style="margin: 5px 0; text-align: center;">';
            $html .= '<a href="' . ($ad->ad_url ?: '#') . '" target="_blank" rel="noopener" style="display: inline-block;">';
            $html .= '<img src="' . $imageUrl . '" alt="' . ($ad->alt_text ?? $ad->name) . '" style="max-width: 100%; height: auto; border-radius: 4px; display: block; margin: 0 auto;">';
            $html .= '</a></div>';
        } elseif ($ad->code) {
            $html .= '<div style="margin: 10px auto; text-align: center;">';
            $html .= $ad->code;
            $html .= '</div>';
        } else {
            $html .= '<div style="background: #f0f0f0; padding: 15px; border-radius: 4px; text-align: center;">';
            $html .= '<a href="' . $ad->ad_url . '" target="_blank" rel="noopener" style="color: #0066cc; text-decoration: none; font-weight: 500;">';
            $html .= $ad->name;
            $html .= '</a></div>';
        }

        $html .= '</div>';
        
        return $html;
    }
}
