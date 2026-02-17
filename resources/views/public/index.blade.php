@extends('public.layout')

@section('title', 'Sajeb NEWS - বাংলাদেশী নিউজ পোর্টাল')

@section('schema')
    @php
        $schemaSettings = \App\Models\SchemaSetting::getInstance();
    @endphp
    
    <!-- Breadcrumb Schema for Homepage -->
    @if($schemaSettings->enable_breadcrumb_schema)
        <script type="application/ld+json">
        {!! json_encode(\App\Services\SchemaGeneratorService::breadcrumbSchema([
            ['name' => 'হোম', 'url' => route('home')]
        ])) !!}
        </script>
    @endif
@endsection

@section('content')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        background: #f8f8f7;
        color: #1b1b18;
    }

    /* News Ticker - At the bottom */
    .news-ticker {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: white;
        padding: 16px 0;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 100;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
    }

    .ticker-header {
        display: flex;
        align-items: center;
        gap: 16px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .ticker-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.1);
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        border-left: 3px solid #ef4444;
    }

    .ticker-play-icon {
        color: #ef4444;
        font-size: 16px;
    }

    .marquee-content {
        flex: 1;
        overflow: hidden;
        position: relative;
    }

    .marquee-text {
        display: flex;
        gap: 30px;
        animation: marquee-scroll 50s linear infinite;
        white-space: nowrap;
    }

    @keyframes marquee-scroll {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }

    .marquee-text:hover {
        animation-play-state: paused;
    }

    .ticker-item {
        display: flex;
        align-items: center;
        gap: 12px;
        color: white;
        text-decoration: none;
        padding: 0 15px;
        transition: color 0.3s;
    }

    .ticker-item:hover {
        color: #60a5fa;
    }

    .ticker-bullet {
        display: inline-block;
        color: #ef4444;
        font-weight: bold;
        font-size: 14px;
    }

    /* Main Container */
    .homepage-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
        padding-bottom: 140px;
    }

    /* Live Stream Button - Floating */
    .pulsate-wrap {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 999;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .puls-content-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pulsate {
        position: relative;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pulsate.live .ringring {
        animation: pulsate-ring 1.5s ease-out infinite;
    }

    .pulsate.offline .ringring {
        animation: blink-ring 1s ease-in-out infinite;
    }

    .ringring {
        position: absolute;
        border: 3px solid #ef4444;
        border-radius: 50%;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }

    @keyframes pulsate-ring {
        0% {
            width: 100%;
            height: 100%;
            opacity: 1;
            border-color: #ef4444;
        }
        100% {
            width: 140%;
            height: 140%;
            opacity: 0;
            border-color: rgba(239, 68, 68, 0);
        }
    }

    @keyframes blink-ring {
        0%, 49% {
            opacity: 1;
            border-color: #333333;
        }
        50%, 100% {
            opacity: 0.3;
            border-color: #555555;
        }
    }

    .circle {
        position: absolute;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .pulsate.offline .circle {
        background: #333333;
    }

    .live-text {
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        background: white;
        padding: 8px 12px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        color: #1b1b18;
        display: none;
    }

    .pulsate-wrap:hover .live-text {
        display: inline-block;
    }

    .pulsate-wrap a {
        text-decoration: none;
        color: inherit;
    }

    @media (max-width: 768px) {
        .pulsate-wrap {
            bottom: 20px;
            right: 20px;
        }

        .live-text {
            font-size: 12px;
            padding: 6px 10px;
        }

        .circle {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }

        .ringring {
            border-width: 2px;
        }
    }


    /* Subscribe Banner */
    .subscribe-banner {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        padding: 32px;
        border-radius: 12px;
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .subscribe-banner h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .subscribe-banner p {
        font-size: 14px;
        opacity: 0.95;
        margin-bottom: 0;
    }

    .subscribe-btn {
        background: white;
        color: #3b82f6;
        border: none;
        padding: 12px 28px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .subscribe-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Section Titles */
    .section-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 24px;
        color: #1b1b18;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Categories Section */
    .categories-section {
        margin-bottom: 48px;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 16px;
    }

    .category-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none;
        color: #1b1b18;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .category-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        background: #f0f7ff;
    }

    .category-icon {
        font-size: 32px;
        line-height: 1;
    }

    .category-name {
        font-weight: 700;
        font-size: 14px;
        line-height: 1.2;
    }

    .category-count {
        font-size: 12px;
        color: #706f6c;
    }

    /* Featured News Section */
    .featured-section {
        margin-bottom: 48px;
    }

    .featured-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .featured-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .featured-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }

    .featured-card-image {
        width: 100%;
        height: 240px;
        object-fit: cover;
        display: block;
    }

    .featured-card-content {
        padding: 20px;
    }

    .featured-card-category {
        display: inline-block;
        background: #eff6ff;
        color: #1e40af;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .featured-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1b1b18;
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .featured-card a {
        text-decoration: none;
        color: inherit;
    }

    .featured-card a:hover .featured-card-title {
        color: #3b82f6;
    }

    .featured-card-meta {
        font-size: 13px;
        color: #706f6c;
    }

    /* Main Content Area */
    .content-wrapper {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 32px;
        margin-bottom: 48px;
    }

    /* News Grid */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
    }

    .news-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }

    .news-card-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
    }

    .news-card-content {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .news-card-category {
        display: inline-block;
        background: #fef3c7;
        color: #92400e;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 8px;
        width: fit-content;
    }

    .news-card-title {
        font-size: 14px;
        font-weight: 600;
        color: #1b1b18;
        margin-bottom: 8px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .news-card a {
        text-decoration: none;
        color: inherit;
    }

    .news-card a:hover .news-card-title {
        color: #3b82f6;
    }

    .news-card-time {
        font-size: 12px;
        color: #706f6c;
    }

    /* Sidebar */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .sidebar-widget {
        background: white;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .widget-title {
        font-size: 16px;
        font-weight: 700;
        color: #1b1b18;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e5e5e0;
    }

    /* Categories Widget */
    .categories-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .category-item {
        padding: 12px 16px;
        background: #f8f8f7;
        border-radius: 6px;
        text-decoration: none;
        color: #1b1b18;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-item:hover {
        background: #3b82f6;
        color: white;
    }

    .category-count {
        font-size: 12px;
        opacity: 0.7;
        background: rgba(0,0,0,0.05);
        padding: 2px 8px;
        border-radius: 3px;
    }

    .category-item:hover .category-count {
        background: rgba(255,255,255,0.2);
    }

    /* Newsletter Widget */
    .newsletter-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .newsletter-input {
        padding: 12px 14px;
        border: 1px solid #e5e5e0;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
        font-family: inherit;
    }

    .newsletter-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .newsletter-btn {
        padding: 12px 14px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-family: inherit;
    }

    .newsletter-btn:hover {
        background: #1e40af;
    }

    /* Trending News in Sidebar */
    .trending-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .trending-item {
        padding-bottom: 16px;
        border-bottom: 1px solid #e5e5e0;
        display: flex;
        gap: 12px;
    }

    .trending-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .trending-number {
        display: inline-block;
        width: 28px;
        height: 28px;
        background: #3b82f6;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 28px;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }

    .trending-content {
        flex: 1;
    }

    .trending-title {
        font-size: 13px;
        font-weight: 600;
        color: #1b1b18;
        text-decoration: none;
        display: block;
        line-height: 1.3;
        margin-bottom: 4px;
    }

    .trending-title:hover {
        color: #3b82f6;
    }

    .trending-views {
        font-size: 11px;
        color: #706f6c;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 40px;
        flex-wrap: wrap;
    }

    .pagination-wrapper a, 
    .pagination-wrapper span {
        padding: 8px 12px;
        border: 1px solid #e5e5e0;
        border-radius: 4px;
        text-decoration: none;
        color: #1b1b18;
        transition: all 0.3s;
        font-size: 14px;
    }

    .pagination-wrapper a:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .pagination-wrapper .active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    /* Empty State */
    .empty-state {
        grid-column: 1/-1;
        padding: 60px 20px;
        text-align: center;
        color: #706f6c;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content-wrapper {
            grid-template-columns: 1fr;
        }

        .sidebar {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            flex-direction: row;
            gap: 24px;
        }

        .subscribe-banner {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }

        .subscribe-banner p {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .featured-grid {
            grid-template-columns: 1fr;
        }

        .categories-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 12px;
        }

        .sidebar {
            flex-direction: column;
            grid-template-columns: 1fr;
        }

        .section-title {
            font-size: 18px;
        }

        .news-grid {
            grid-template-columns: 1fr;
        }

        .subscribe-banner {
            padding: 24px;
        }

        .homepage-container {
            padding-bottom: 160px;
        }

        .news-ticker {
            padding: 12px 0;
        }

        .ticker-badge {
            font-size: 11px;
            padding: 6px 10px;
        }

        .ticker-item {
            font-size: 13px;
            padding: 0 10px;
        }
    }
</style>

<div class="homepage-container">
    <!-- Live Stream Floating Button -->
    @php
        $activeLiveStream = \App\Models\LiveStream::where('status', 'active')
            ->where('start_time', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_time')
                      ->orWhere('end_time', '>', now());
            })
            ->first();
    @endphp

    <div class="pulsate-wrap" id="liveStreamContainer">
        <a href="{{ $activeLiveStream ? route('live.watch', $activeLiveStream->slug) : route('live.index') }}" class="puls-content-wrap">
            <div class="pulsate {{ $activeLiveStream ? 'live' : 'offline' }}" id="pulsateIndicator">
                <div class="ringring"></div>
                <div class="circle">
                    @if($activeLiveStream)
                        🔴
                    @else
                        📺
                    @endif
                </div>
            </div>
            <span class="live-text" id="liveText">
                @if($activeLiveStream)
                    Live TV
                @else
                    Live
                @endif
            </span>
        </a>
    </div>

    <!-- Subscribe Banner -->
    <div class="subscribe-banner">
        <div>
            <h3>📬 নতুন খবর সরাসরি পান!</h3>
            <p>প্রতিদিন সর্বশেষ খবর সরাসরি আপনার কাছে</p>
        </div>
        <button id="push-subscribe-btn" class="subscribe-btn">সাবস্ক্রাইব করুন</button>
    </div>

    <!-- Categories Section -->
    <section class="categories-section">
        <h2 class="section-title">📂 বিভাগসমূহ</h2>
        <div class="categories-grid">
            @php
                $categories = \App\Models\Category::where('is_active', true)
                    ->whereNull('parent_id')
                    ->limit(8)
                    ->get();
            @endphp
            @foreach($categories as $cat)
            <a href="{{ route('category.show', $cat->slug) }}" class="category-card">
                <div class="category-icon">{{ $cat->icon ?? '📁' }}</div>
                <div class="category-name">{{ $cat->name }}</div>
                <div class="category-count">{{ $cat->news()->count() }} খবর</div>
            </a>
            @endforeach
        </div>
    </section>

    <!-- Featured News Section -->
    @if($featured->count() > 0)
    <section class="featured-section">
        <h2 class="section-title">⭐ প্রধান খবর</h2>
        <div class="featured-grid">
            @foreach($featured->take(3) as $news)
            <div class="featured-card">
                <a href="{{ route('news.show', $news->slug) }}">
                    @if($news->featured_image)
                    <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}" class="featured-card-image">
                    @else
                    <div style="width: 100%; height: 240px; background: #e5e5e0; display: flex; align-items: center; justify-content: center; color: #706f6c; font-size: 14px;">
                        কোন ছবি নেই
                    </div>
                    @endif
                    <div class="featured-card-content">
                        <span class="featured-card-category">{{ $news->category->name ?? 'খবর' }}</span>
                        <h3 class="featured-card-title">{{ $news->title }}</h3>
                        <p style="font-size: 13px; color: #706f6c; line-height: 1.4; margin-bottom: 12px;">
                            {{ substr($news->excerpt ?? $news->content, 0, 100) }}...
                        </p>
                        <div class="featured-card-meta" style="display: flex; justify-content: space-between; align-items: center;">
                            <span>{{ $news->published_at?->diffForHumans() ?? 'নতুন' }}</span>
                            @if($news->author)
                            <a href="{{ route('author.show', $news->author->id) }}" style="color: #706f6c; text-decoration: none; font-size: 12px; font-weight: 500;" title="লেখক">লিখেছেন: {{ $news->author->name }}</a>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Main Content with Sidebar -->
    <div class="content-wrapper">
        <!-- News Grid -->
        <section>
            <h2 class="section-title">📰 সর্বশেষ খবর</h2>
            <div class="news-grid">
                @forelse($latest as $news)
                <div class="news-card">
                    <a href="{{ route('news.show', $news->slug) }}">
                        @if($news->featured_image)
                        <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}" class="news-card-image">
                        @else
                        <div style="width: 100%; height: 180px; background: #e5e5e0; display: flex; align-items: center; justify-content: center; color: #706f6c; font-size: 13px;">
                            কোন ছবি
                        </div>
                        @endif
                        <div class="news-card-content">
                            <span class="news-card-category">{{ $news->category->name ?? 'খবর' }}</span>
                            <h3 class="news-card-title">{{ $news->title }}</h3>
                            <div style="font-size: 12px; color: #706f6c; margin-bottom: 8px;">
                                @if($news->author)
                                <a href="{{ route('author.show', $news->author->id) }}" style="color: #706f6c; text-decoration: none;" title="লেখক">{{ $news->author->name }}</a>
                                @endif
                            </div>
                            <div class="news-card-time">{{ $news->published_at?->diffForHumans() ?? 'নতুন' }}</div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="empty-state">
                    <p style="font-size: 16px;">কোনো খবর পাওয়া যাচ্ছে না</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($latest && $latest->hasPages())
            <div class="pagination-wrapper">
                {{ $latest->links() }}
            </div>
            @endif
        </section>

        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Categories Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">📂 বিভাগসমূহ</h3>
                <div class="categories-list">
                    @php
                        $categories = \App\Models\Category::withCount('news')->limit(8)->get();
                    @endphp
                    @forelse($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" class="category-item">
                        <span>{{ $category->name }}</span>
                        <span class="category-count">{{ $category->news_count }}</span>
                    </a>
                    @empty
                    <p style="color: #706f6c; font-size: 13px;">কোনো বিভাগ পাওয়া যাচ্ছে না</p>
                    @endforelse
                </div>
            </div>

            <!-- Trending Widget -->
            @if($trending && $trending->count() > 0)
            <div class="sidebar-widget">
                <h3 class="widget-title">📈 ট্রেন্ডিং নিউজ</h3>
                <div class="trending-list">
                    @foreach($trending->take(5) as $index => $news)
                    <div class="trending-item">
                        <div class="trending-number">{{ $index + 1 }}</div>
                        <div class="trending-content">
                            <a href="{{ route('news.show', $news->slug) }}" class="trending-title">{{ $news->title }}</a>
                            <div class="trending-views">{{ $news->views ?? 0 }} ভিউ</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Newsletter Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">📧 নিউজলেটার</h3>
                <p style="font-size: 13px; color: #706f6c; margin-bottom: 16px;">সর্বশেষ খবর আপনার ইনবক্সে পান</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                    @csrf
                    <input type="email" name="email" class="newsletter-input" placeholder="আপনার ইমেইল" required>
                    <button type="submit" class="newsletter-btn">সাবস্ক্রাইব করুন</button>
                </form>
            </div>
        </aside>
    </div>

    <!-- News Ticker - নিউজ টিকার সবার নিচে -->
    @php
        $latestNews = \App\Models\News::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get();
    @endphp
    
    @if($latestNews->count() > 0)
    <div class="news-ticker">
        <div class="ticker-header">
            <div class="ticker-badge">
                <span class="ticker-play-icon"><i class="fa fa-play"></i></span>
                <span>শিরোনাম</span>
            </div>
            <div class="marquee-content">
                <div class="marquee-text">
                    @foreach($latestNews as $news)
                        <a href="{{ route('news.show', $news->slug) }}" class="ticker-item">
                            <span class="ticker-bullet">●</span>
                            <span>{{ $news->title }}</span>
                        </a>
                    @endforeach
                    @foreach($latestNews as $news)
                        <a href="{{ route('news.show', $news->slug) }}" class="ticker-item">
                            <span class="ticker-bullet">●</span>
                            <span>{{ $news->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    // Push Notification Subscribe Button Handler
    document.addEventListener('DOMContentLoaded', function() {
        const subscribeBtn = document.getElementById('push-subscribe-btn');
        
        if (!subscribeBtn) return;

        // Check if manager is available
        const checkManager = setInterval(function() {
            if (window.PushNotificationManager) {
                clearInterval(checkManager);
                initPushNotifications();
            }
        }, 100);

        // Timeout after 2 seconds
        setTimeout(() => clearInterval(checkManager), 2000);

        function initPushNotifications() {
            const manager = new PushNotificationManager();
            
            // Check if browser supports push notifications
            if (!manager.isSupported()) {
                subscribeBtn.innerHTML = '<i class="fas fa-ban"></i> আপনার ব্রাউজার সাপোর্ট করে না';
                subscribeBtn.disabled = true;
                subscribeBtn.classList.add('disabled');
                return;
            }

            // Check if already subscribed
            checkIfSubscribed(manager);

            // Add click handler
            subscribeBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                subscribeBtn.disabled = true;
                subscribeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> অপেক্ষা করুন...';

                try {
                    const result = await manager.subscribe();
                    
                    if (result.success) {
                        subscribeBtn.innerHTML = '<i class="fas fa-check-circle"></i> ✓ সক্ষম হয়েছে';
                        subscribeBtn.style.background = '#10b981';
                        subscribeBtn.style.color = 'white';
                        subscribeBtn.style.cursor = 'default';
                    } else {
                        showAlert('error', result.message || 'সাবস্ক্রিপশন ব্যর্থ হয়েছে');
                        subscribeBtn.disabled = false;
                        subscribeBtn.innerHTML = 'সাবস্ক্রাইব করুন';
                    }
                } catch (error) {
                    console.error('Subscribe error:', error);
                    showAlert('error', 'একটি ত্রুটি ঘটেছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।');
                    subscribeBtn.disabled = false;
                    subscribeBtn.innerHTML = 'সাবস্ক্রাইব করুন';
                }
            });
        }

        function checkIfSubscribed(manager) {
            manager.isEnabled().then(enabled => {
                if (enabled) {
                    subscribeBtn.innerHTML = '<i class="fas fa-check-circle"></i> ✓ সক্ষম করা আছে';
                    subscribeBtn.style.background = '#10b981';
                    subscribeBtn.style.color = 'white';
                    subscribeBtn.disabled = true;
                    subscribeBtn.style.cursor = 'default';
                }
            });
        }

        function showAlert(type, message) {
            const alertEl = document.createElement('div');
            alertEl.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: ${type === 'error' ? '#ef4444' : '#10b981'};
                color: white;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                max-width: 400px;
                font-size: 14px;
            `;
            alertEl.textContent = message;
            document.body.appendChild(alertEl);
            
            setTimeout(() => {
                alertEl.style.opacity = '0';
                alertEl.style.transition = 'opacity 0.3s';
                setTimeout(() => alertEl.remove(), 300);
            }, 5000);
        }
    });

    // Live Stream Status Checker for Pulsate
    function checkLiveStreamStatus() {
        fetch('{{ route("api.live.active") }}', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const pulsateIndicator = document.getElementById('pulsateIndicator');
            const liveText = document.getElementById('liveText');
            const circles = document.querySelector('.circle');
            
            if (!pulsateIndicator) return;

            if (data.active) {
                // Activate Live
                pulsateIndicator.classList.remove('offline');
                pulsateIndicator.classList.add('live');
                
                if (circles) {
                    circles.innerHTML = '🔴';
                }
                if (liveText) {
                    liveText.textContent = 'Live TV';
                }
            } else {
                // Deactivate Live
                pulsateIndicator.classList.remove('live');
                pulsateIndicator.classList.add('offline');
                
                if (circles) {
                    circles.innerHTML = '📺';
                }
                if (liveText) {
                    liveText.textContent = 'Live';
                }
            }
        })
        .catch(error => console.log('Live stream check error:', error));
    }

    // Check live stream status on page load and then every 30 seconds
    document.addEventListener('DOMContentLoaded', function() {
        checkLiveStreamStatus();
        setInterval(checkLiveStreamStatus, 30000);
    });
</script>
@endsection
