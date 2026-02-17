<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use App\Models\NewsletterSubscriber;
use App\Models\ActivityLog;
use App\Models\LiveStream;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Get statistics
        $totalNews = News::count();
        $totalViews = News::sum('views') ?? 0;
        $totalUsers = User::count();
        $newsletterSubscribers = NewsletterSubscriber::where('is_verified', true)->count();

        // Get recent news
        $recentNews = News::with(['category', 'author'])
            ->latest()
            ->limit(5)
            ->get();

        // Get recent activities
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Get recent live streams
        $liveStreams = LiveStream::where('user_id', auth()->id())
            ->orWhere(function ($query) {
                // Admins can see all streams
                if (auth()->user() && auth()->user()->is_admin) {
                    return $query;
                }
            })
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalNews' => $totalNews,
            'totalViews' => $totalViews,
            'totalUsers' => $totalUsers,
            'newsletterSubscribers' => $newsletterSubscribers,
            'recentNews' => $recentNews,
            'recentActivities' => $recentActivities,
            'liveStreams' => $liveStreams,
        ]);
    }
}
