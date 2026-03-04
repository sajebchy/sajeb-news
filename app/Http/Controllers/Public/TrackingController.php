<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\VisitorTrackingService;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    protected $trackingService;

    public function __construct(VisitorTrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    /**
     * Track visitor activity
     */
    public function trackVisitor(Request $request)
    {
        $validated = $request->validate([
            'news_id' => 'required|integer|exists:news,id',
            'time_spent' => 'required|integer|min:0',
            'scroll_percentage' => 'required|integer|min:0|max:100',
            'completed_reading' => 'required|boolean',
            'screen_resolution' => 'nullable|string',
        ]);

        $visitor = $this->trackingService->trackVisit($request, $validated['news_id'], $validated);

        if ($visitor) {
            return response()->json([
                'success' => true,
                'visitor_id' => $visitor->id,
                'message' => 'Visitor tracked successfully',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to track visitor',
        ], 500);
    }
}
