<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Services\AdService;
use Illuminate\Http\Request;

class AdController extends Controller
{
    protected $adService;

    public function __construct(AdService $adService)
    {
        $this->adService = $adService;
    }

    /**
     * Get ads for a specific placement
     */
    public function getByPlacement(Request $request, $placement)
    {
        $device = $request->query('device', 'desktop');
        $limit = $request->query('limit', 3);

        $ads = $this->adService->getAdsByPlacement($placement, $device, (int)$limit);

        return response()->json([
            'success' => true,
            'placement' => $placement,
            'device' => $device,
            'count' => $ads->count(),
            'ads' => $ads->map(function ($ad) {
                return [
                    'id' => $ad->id,
                    'name' => $ad->name,
                    'image_url' => $ad->image_url,
                    'ad_url' => $ad->full_url,
                    'alt_text' => $ad->alt_text ?? $ad->name,
                    'placement' => $ad->placement,
                ];
            })
        ]);
    }

    /**
     * Record ad click
     */
    public function recordClick(Request $request, Advertisement $advertisement)
    {
        try {
            $this->adService->recordClick($advertisement, [
                'placement' => $request->input('placement'),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Click recorded',
                'ad_id' => $advertisement->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error recording click',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Record ad impression
     */
    public function recordImpression(Request $request, Advertisement $advertisement)
    {
        try {
            $this->adService->recordView($advertisement);

            return response()->json([
                'success' => true,
                'message' => 'Impression recorded',
                'ad_id' => $advertisement->id,
                'views' => $advertisement->views,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error recording impression',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get random ad for placement
     */
    public function getRandomForPlacement(Request $request, $placement)
    {
        $device = $request->query('device', 'desktop');

        $ad = $this->adService->getRandomAdForPlacement($placement, $device);

        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'No ad found for this placement',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ad' => [
                'id' => $ad->id,
                'name' => $ad->name,
                'image_url' => $ad->image_url,
                'ad_url' => $ad->full_url,
                'alt_text' => $ad->alt_text ?? $ad->name,
                'placement' => $ad->placement,
            ]
        ]);
    }

    /**
     * Get ad statistics
     */
    public function getStatistics(Advertisement $advertisement)
    {
        return response()->json([
            'success' => true,
            'statistics' => $this->adService->getStatistics($advertisement),
        ]);
    }

    /**
     * Get trending ads
     */
    public function getTrending(Request $request)
    {
        $limit = $request->query('limit', 5);
        $ads = $this->adService->getTrendingAds((int)$limit);

        return response()->json([
            'success' => true,
            'count' => $ads->count(),
            'ads' => $ads->map(function ($ad) {
                return [
                    'id' => $ad->id,
                    'name' => $ad->name,
                    'image_url' => $ad->image_url,
                    'ad_url' => $ad->full_url,
                    'views' => $ad->views,
                    'clicks' => $ad->clicks,
                    'ctr' => $ad->ctr,
                ];
            })
        ]);
    }
}
