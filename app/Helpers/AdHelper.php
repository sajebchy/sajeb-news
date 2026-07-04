<?php

namespace App\Helpers;

use App\Models\Advertisement;

class AdHelper
{
    /** Track whether the click-tracker script has been printed this request */
    private static bool $scriptPrinted = false;

    /**
     * Return the JS click-tracker snippet (only once per page).
     */
    private static function clickTrackerScript(string $baseUrl): string
    {
        if (self::$scriptPrinted) {
            return '';
        }
        self::$scriptPrinted = true;

        // Strip the ad-specific part; the function builds the URL dynamically
        $apiBase = url('/api/ads');

        return <<<JS
<script>
function adTrackClick(e, adId, dest) {
    var token = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = token ? token.getAttribute('content') : '';
    // Use sendBeacon for reliability (fires even as page navigates away)
    var url = '{$apiBase}/' + adId + '/click';
    var data = JSON.stringify({ placement: document.location.pathname });
    if (navigator.sendBeacon) {
        var blob = new Blob([data], { type: 'application/json' });
        navigator.sendBeacon(url, blob);
    } else {
        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: data,
            keepalive: true
        }).catch(function(){});
    }
}
</script>
JS;
    }

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

        $trackUrl   = url('/api/ads/' . $ad->id . '/click');
        $destUrl    = $ad->ad_url ?: '#';
        $altText    = e($ad->alt_text ?? $ad->name);
        $onclickJs  = "adTrackClick(event,{$ad->id},'" . addslashes($destUrl) . "')";

        $html  = '<div class="ad-wrapper" style="margin: 15px auto; text-align: center; width: 100%;">';

        if ($ad->image_url) {
            $imageUrl = str_starts_with($ad->image_url, 'http') ? $ad->image_url : asset($ad->image_url);
            $html .= '<div style="margin: 5px 0; text-align: center;">';
            $html .= '<a href="' . $destUrl . '" target="_blank" rel="noopener nofollow" style="display: inline-block;" onclick="' . $onclickJs . '">';
            $html .= '<img src="' . $imageUrl . '" alt="' . $altText . '" style="max-width: 100%; height: auto; border-radius: 4px; display: block; margin: 0 auto;">';
            $html .= '</a></div>';
        } elseif ($ad->code) {
            $html .= '<div style="margin: 10px auto; text-align: center;" onclick="' . $onclickJs . '">';
            $html .= $ad->code;
            $html .= '</div>';
        } else {
            $html .= '<div style="background: #f0f0f0; padding: 15px; border-radius: 4px; text-align: center;">';
            $html .= '<a href="' . $destUrl . '" target="_blank" rel="noopener nofollow" style="color: #0066cc; text-decoration: none; font-weight: 500;" onclick="' . $onclickJs . '">';
            $html .= e($ad->name);
            $html .= '</a></div>';
        }

        $html .= '</div>';

        // Inject tracker script once per page
        $html .= self::clickTrackerScript($trackUrl);
        
        return $html;
    }
}
