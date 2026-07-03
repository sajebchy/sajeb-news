<?php

use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\PagesController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/category/{category:slug}', [NewsController::class, 'category'])->name('category.show');
Route::get('/tag/{tag}', [NewsController::class, 'tag'])->name('tag.show');
Route::get('/author/{author:id}', [NewsController::class, 'author'])->name('author.show');
Route::get('/search', [NewsController::class, 'search'])->name('news.search');

// Pages Routes
Route::get('/about', [PagesController::class, 'about'])->name('about');
Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
Route::post('/contact', [PagesController::class, 'storeContact'])->name('contact.store');
Route::get('/privacy-policy', [PagesController::class, 'privacy'])->name('privacy');
Route::get('/terms-and-conditions', [PagesController::class, 'terms'])->name('terms');
Route::get('/sitemap', [PagesController::class, 'sitemap'])->name('sitemap');
Route::get('/sitemap.xml', [PagesController::class, 'sitemapXml'])->name('sitemap.xml');
Route::get('/llm.txt', [PagesController::class, 'llmTxt'])->name('llm.txt');
Route::get('/robots.txt', [PagesController::class, 'robotsTxt'])->name('robots.txt');

// Live Stream Routes (Public)
Route::get('/live', [\App\Http\Controllers\LiveStreamViewController::class, 'index'])->name('live.index');
Route::get('/live/{stream:slug}', [\App\Http\Controllers\LiveStreamViewController::class, 'watch'])->name('live.watch');
Route::get('/live/{stream}/chat', [\App\Http\Controllers\LiveStreamViewController::class, 'chatMessages'])->name('live.chat');

// Live Stream Comments (Public)
Route::post('/live/{stream:slug}/comments', [\App\Http\Controllers\StreamCommentController::class, 'store'])->name('live.comments.store');
Route::get('/live/{stream:slug}/comments', [\App\Http\Controllers\StreamCommentController::class, 'getComments'])->name('live.comments.list');
Route::delete('/live/comments/{comment}', [\App\Http\Controllers\StreamCommentController::class, 'destroy'])->name('live.comments.destroy');
Route::post('/live/comments/{comment}/like', [\App\Http\Controllers\StreamCommentController::class, 'like'])->name('live.comments.like');

// Web Push Notification Routes (Public)
Route::post('/api/push/subscribe', [\App\Http\Controllers\PushNotificationController::class, 'subscribe'])->name('push.subscribe');
Route::post('/api/push/unsubscribe', [\App\Http\Controllers\PushNotificationController::class, 'unsubscribe'])->name('push.unsubscribe');
Route::post('/api/push/check', [\App\Http\Controllers\PushNotificationController::class, 'checkSubscription'])->name('push.check');
Route::get('/api/push/stats', [\App\Http\Controllers\PushNotificationController::class, 'getStats'])->name('push.stats');

// Live Stream Status Check (Public API)
Route::get('/api/live/active', function () {
    $activeLiveStream = \App\Models\LiveStream::where('status', 'live')
        ->where('visibility', 'public')
        ->where('started_at', '<=', now())
        ->where(function($query) {
            $query->whereNull('ended_at')
                  ->orWhere('ended_at', '>', now());
        })
        ->first();

    return response()->json([
        'active' => $activeLiveStream ? true : false,
        'stream' => $activeLiveStream ? [
            'id' => $activeLiveStream->id,
            'title' => $activeLiveStream->title,
            'slug' => $activeLiveStream->slug,
        ] : null
    ]);
})->name('api.live.active');

// Newsletter Routes
Route::post('/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// News Comments Routes
Route::middleware('auth')->group(function () {
    Route::post('/news/{news:slug}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('news.comments.store');
    Route::delete('/news/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('news.comments.destroy');
});

// Dashboard - Redirect to admin panel
Route::get('/dashboard', function () {
    return redirect('/admin');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified', 'no-back-history'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // News Management
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
    Route::post('/news/upload-image', [\App\Http\Controllers\Admin\NewsController::class, 'uploadImage'])->name('news.upload-image');

    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::post('categories/{category}/toggle', [\App\Http\Controllers\Admin\CategoryController::class, 'togglePublished'])->name('categories.toggle');

    // Tag Management
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class);

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::put('/users/{user}/change-password', [\App\Http\Controllers\Admin\UserController::class, 'changePassword'])->name('users.change-password');

    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/{news}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'show'])->name('analytics.show');
    Route::get('/analytics/{news}/visitor/{visitor}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'visitorDetail'])->name('analytics.visitor-detail');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update']);

    // Activity Logs
    Route::get('/activities', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activities');

    // Newsletter Subscribers
    Route::get('/newsletters', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletters.index');
    Route::delete('/newsletters/{subscriber}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletters.destroy');

    // Advertisement Management
    Route::get('/advertisements/export/csv', [\App\Http\Controllers\Admin\AdController::class, 'export'])->name('advertisements.export');
    Route::post('/advertisements/upload', [\App\Http\Controllers\Admin\AdController::class, 'uploadAdvertisementImage'])->name('upload-advertisement-image');
    Route::resource('advertisements', \App\Http\Controllers\Admin\AdController::class);
    Route::post('/advertisements/{advertisement}/toggle-status', [\App\Http\Controllers\Admin\AdController::class, 'toggleStatus'])->name('advertisements.toggle-status');
    Route::get('/advertisements/{advertisement}/statistics', [\App\Http\Controllers\Admin\AdController::class, 'statistics'])->name('advertisements.statistics');

    // Live Stream Management - Only for Admins
    Route::middleware('admin')->group(function () {
        Route::get('/live-streams', [\App\Http\Controllers\Admin\LiveStreamController::class, 'index'])->name('live-streams.index');
        Route::post('/live-streams', [\App\Http\Controllers\Admin\LiveStreamController::class, 'store'])->name('live-streams.store');
        Route::get('/live-streams/create', [\App\Http\Controllers\Admin\LiveStreamController::class, 'create'])->name('live-streams.create');
        Route::get('/live-streams/{stream}', [\App\Http\Controllers\Admin\LiveStreamController::class, 'show'])->name('live-streams.show');
        Route::put('/live-streams/{stream}', [\App\Http\Controllers\Admin\LiveStreamController::class, 'update'])->name('live-streams.update');
        Route::patch('/live-streams/{stream}', [\App\Http\Controllers\Admin\LiveStreamController::class, 'update']);
        Route::delete('/live-streams/{stream}', [\App\Http\Controllers\Admin\LiveStreamController::class, 'destroy'])->name('live-streams.destroy');
        Route::get('/live-streams/{stream}/edit', [\App\Http\Controllers\Admin\LiveStreamController::class, 'edit'])->name('live-streams.edit');
        Route::post('/live-streams/{stream}/start', [\App\Http\Controllers\Admin\LiveStreamController::class, 'start'])->name('live-streams.start');
        Route::post('/live-streams/{stream}/stop', [\App\Http\Controllers\Admin\LiveStreamController::class, 'stop'])->name('live-streams.stop');
        Route::post('/live-streams/{stream}/toggle-featured', [\App\Http\Controllers\Admin\LiveStreamController::class, 'toggleFeatured'])->name('live-streams.toggle-featured');

        // Live Stream Comments Moderation
        Route::post('/live-streams/{stream}/comments/{comment}/approve', [\App\Http\Controllers\StreamCommentController::class, 'approve'])->name('live-streams.comments.approve');
        Route::post('/live-streams/{stream}/comments/{comment}/reject', [\App\Http\Controllers\StreamCommentController::class, 'reject'])->name('live-streams.comments.reject');
        Route::post('/live-streams/{stream}/comments/{comment}/pin', [\App\Http\Controllers\StreamCommentController::class, 'pin'])->name('live-streams.comments.pin');
        Route::post('/live-streams/{stream}/comments/{comment}/unpin', [\App\Http\Controllers\StreamCommentController::class, 'unpin'])->name('live-streams.comments.unpin');
    });

    // Photo Card Maker
    Route::get('/photo-card', function () {
        $seoSettings = \App\Models\SeoSetting::first();
        return view('admin.photo-card.index', ['seoSettings' => $seoSettings]);
    })->name('photo-card.index');

    // File Manager Routes
    Route::get('/file-manager', function () {
        return view('admin.file-manager');
    })->name('file-manager.index');

    Route::prefix('file-manager')->name('file-manager.')->group(function () {
        Route::post('/upload', [\App\Http\Controllers\Admin\FileManagerController::class, 'upload'])->name('upload');
        Route::post('/delete', [\App\Http\Controllers\Admin\FileManagerController::class, 'delete'])->name('delete');
        Route::post('/rename', [\App\Http\Controllers\Admin\FileManagerController::class, 'rename'])->name('rename');
        Route::post('/create-folder', [\App\Http\Controllers\Admin\FileManagerController::class, 'createFolder'])->name('create-folder');
        Route::get('/list', [\App\Http\Controllers\Admin\FileManagerController::class, 'list'])->name('list');
    });
});

// Include authentication routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';
