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

    /* 70-30 Grid Section */
    .grid-70-30-section {
        margin-bottom: 48px;
    }

    .grid-70-30-wrapper {
        display: grid;
        grid-template-columns: 70% 30%;
        gap: 24px;
    }

    .grid-70-col {
        width: 100%;
    }

    .grid-30-col {
        width: 100%;
    }

    .grid-70-content {
        background: white;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .grid-30-content {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .grid-70-placeholder {
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f8f7;
        border-radius: 8px;
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

        /* 70-30 Grid Responsive */
        .grid-70-30-wrapper {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .grid-70-col,
        .grid-30-col {
            width: 100%;
        }

        .grid-70-placeholder {
            min-height: 200px;
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

    <!-- 70-30 Grid Section -->
    <section class="grid-70-30-section">
        <div class="grid-70-30-wrapper">
            <!-- 70% Left Column -->
            <div class="grid-70-col">
                <div class="grid-70-content">
                    <h2 class="section-title">📌 বিশেষ বিভাগ</h2>
                    <p style="color: #706f6c; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                        এই বিভাগে আমরা আপনার জন্য সবচেয়ে গুরুত্বপূর্ণ এবং প্রাসঙ্গিক খবরগুলি নিয়ে আসি। 
                        সর্বশেষ আপডেট এবং বিশ্লেষণ পেতে আমাদের সাথে থাকুন।
                    </p>
                    <div class="grid-70-placeholder" style="background: white; border-radius: 8px; padding: 40px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                        <p style="color: #706f6c; font-size: 14px;">এই স্থানে আপনার কন্টেন্ট যোগ করা হবে</p>
                    </div>
                </div>
            </div>

            <!-- 30% Right Column -->
            <div class="grid-30-col">
                <div class="grid-30-content">
                    <h2 class="section-title">🔥 জনপ্রিয়</h2>
                    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden;">
                        @php
                            $popularNews = \App\Models\News::where('status', 'published')
                                ->orderBy('views', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        @forelse($popularNews as $index => $news)
                        <a href="{{ route('news.show', $news->slug) }}" style="display: block; padding: 16px; border-bottom: 1px solid #e5e5e0; text-decoration: none; transition: all 0.3s; color: #1b1b18;">
                            <div style="display: flex; gap: 12px; align-items: flex-start;">
                                <div style="background: #3b82f6; color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0;">{{ $index + 1 }}</div>
                                <div style="flex: 1;">
                                    <h4 style="font-size: 13px; font-weight: 600; line-height: 1.4; margin-bottom: 4px;">{{ $news->title }}</h4>
                                    <span style="font-size: 11px; color: #706f6c;">👁️ {{ $news->views ?? 0 }} ভিউ</span>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div style="padding: 20px; text-align: center; color: #706f6c;">
                            <p>কোনো খবর পাওয়া যাচ্ছে না</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Homepage Top Ad (After Featured News) -->
    <div class="container" style="margin: 40px 0;">
        <div style="background: white; padding: 20px; border-radius: 8px; text-align: center;">
            <x-ad-placement placement="homepage_top" random="true" class="ad-homepage-top" />
        </div>
    </div>

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

            <!-- Google AdSense Sidebar Ad -->
            @php
                $adService = new \App\Services\AdService();
                $sidebarAdCode = $adService->getSidebarAdCode();
            @endphp
            @if($sidebarAdCode && $adService->showSidebarAds())
            <div class="sidebar-widget" style="background: #f5f5f5; padding: 16px; border-radius: 8px; text-align: center;">
                {!! $sidebarAdCode !!}
            </div>
            @endif
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

                        this.subscribeBtn.innerHTML = 'সাবস্ক্রাইব করুন';
                    }
                } catch (error) {
                    console.error('Subscribe error:', error);
                    this.showAlert('error', 'একটি ত্রুটি ঘটেছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।');
                    this.subscribeBtn.disabled = false;
                    this.subscribeBtn.innerHTML = 'সাবস্ক্রাইব করুন';
                }
            });
        }

        checkIfSubscribed(manager) {
            manager.isEnabled().then(enabled => {
                if (enabled) {
                    this.subscribeBtn.innerHTML = '<i class="fas fa-check-circle"></i> সক্ষম করা আছে';
                    this.subscribeBtn.style.background = '#10b981';
                    this.subscribeBtn.style.color = 'white';
                    this.subscribeBtn.disabled = true;
                    this.floatingBtn.style.opacity = '0.5';
                    this.floatingBtn.disabled = true;
                }
            });
        }

        showAlert(type, message) {
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
                z-index: 10000;
                max-width: 400px;
                font-size: 14px;
                animation: slideDown 0.3s ease-out;
            `;
            alertEl.textContent = message;
            document.body.appendChild(alertEl);
            
            setTimeout(() => {
                alertEl.style.opacity = '0';
                alertEl.style.transition = 'opacity 0.3s';
                setTimeout(() => alertEl.remove(), 300);
            }, 5000);
        }
    }

    // Initialize modal manager when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        new SubscribeModalManager();
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
