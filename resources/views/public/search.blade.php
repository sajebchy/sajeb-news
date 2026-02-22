@extends('public.layout')

@section('title', 'Search Results for "' . htmlspecialchars($query) . '" - Sajeb NEWS')
@section('description', 'Search results for: ' . htmlspecialchars($query) . ' on Sajeb NEWS')
@section('canonical', route('news.search') . '?q=' . urlencode($query))

@section('schema')
    @php
        $schemaSettings = \App\Models\SchemaSetting::getInstance();
    @endphp
    
    @if($schemaSettings->enable_breadcrumb_schema)
        <script type="application/ld+json">
        {!! json_encode(\App\Services\SchemaGeneratorService::breadcrumbSchema([
            ['name' => 'হোম', 'url' => route('home')],
            ['name' => 'সার্চ', 'url' => route('news.search') . '?q=' . urlencode($query)],
            ['name' => htmlspecialchars($query), 'url' => route('news.search') . '?q=' . urlencode($query)]
        ])) !!}
        </script>
    @endif
@endsection

@section('content')

<style>
    .search-page {
        background: #f8f8f7;
    }

    .search-header {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        padding: 40px 0;
        margin-bottom: 40px;
    }

    .search-header-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .search-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .search-header .results-info {
        font-size: 16px;
        opacity: 0.95;
        margin-bottom: 0;
    }

    .search-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 16px;
    }

    /* Search Box */
    .search-box-wrapper {
        margin-bottom: 40px;
    }

    .search-box {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
    }

    .search-input-group {
        flex: 1;
        position: relative;
        display: flex;
    }

    .search-input-group input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e5e5;
        border-radius: 8px;
        font-size: 16px;
        font-family: inherit;
        transition: all 0.3s;
    }

    .search-input-group input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .search-btn {
        padding: 12px 32px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .search-btn:hover {
        background: #1e40af;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    /* Results Info */
    .results-info-box {
        background: white;
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .results-count {
        font-size: 16px;
        color: #333;
        font-weight: 500;
    }

    .results-count strong {
        color: #3b82f6;
    }

    /* News Grid */
    .search-results-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
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
        background: linear-gradient(135deg, #e5e5e5 0%, #f8f8f7 100%);
        overflow: hidden;
    }

    .news-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .news-card:hover .news-card-image img {
        transform: scale(1.05);
    }

    .news-card-body {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .news-category {
        display: inline-block;
        background: #f0f7ff;
        color: #3b82f6;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
        width: fit-content;
    }

    .news-card-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        line-height: 1.4;
        color: #333;
        text-decoration: none;
        display: block;
        transition: color 0.3s;
    }

    .news-card:hover .news-card-title {
        color: #3b82f6;
    }

    .news-card-description {
        font-size: 13px;
        color: #706f6c;
        line-height: 1.4;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-card-meta {
        font-size: 12px;
        color: #999;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }

    .news-views {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Empty State */
    .empty-result {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 8px;
        margin-bottom: 40px;
    }

    .empty-result-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.6;
    }

    .empty-result-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }

    .empty-result-text {
        font-size: 16px;
        color: #706f6c;
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .empty-result-link {
        display: inline-block;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        padding: 10px 20px;
        border: 2px solid #3b82f6;
        border-radius: 6px;
    }

    .empty-result-link:hover {
        background: #3b82f6;
        color: white;
    }

    /* Error State */
    .search-error {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        color: #92400e;
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .search-error-icon {
        font-size: 20px;
        flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .search-header h1 {
            font-size: 24px;
        }

        .search-box {
            flex-direction: column;
        }

        .search-results-grid {
            grid-template-columns: 1fr;
        }

        .results-info-box {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }
    }
</style>

<div class="search-page">
    <!-- Search Header -->
    <div class="search-header">
        <div class="search-header-content">
            <h1>🔍 সার্চ করুন</h1>
            <p class="results-info">
                @if($query)
                    "<strong>{{ htmlspecialchars($query) }}</strong>" এর জন্য ফলাফল
                @else
                    আপনার পছন্দের খবর খুঁজে বের করুন
                @endif
            </p>
        </div>
    </div>

    <!-- Search Container -->
    <div class="search-container">
        <!-- Search Box -->
        <div class="search-box-wrapper">
            <form method="GET" action="{{ route('news.search') }}" class="search-box">
                <div class="search-input-group">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="খবর সার্চ করুন..." 
                        value="{{ htmlspecialchars($query) }}"
                        required
                        autocomplete="off"
                    />
                </div>
                <button type="submit" class="search-btn">সার্চ করুন</button>
            </form>
        </div>

        <!-- Results Info -->
        @if($query && $news->count() > 0)
            <div class="results-info-box">
                <div class="results-count">
                    মোট <strong>{{ $news->count() }}</strong> টি ফলাফল পাওয়া গেছে
                </div>
            </div>
        @endif

        <!-- Search Results -->
        @if($query && $news->count() > 0)
            <div class="search-results-grid">
                @foreach($news as $article)
                    <div class="news-card">
                        @if($article->featured_image)
                            <a href="{{ route('news.show', $article->slug) }}" class="news-card-image">
                                <img 
                                    src="{{ asset('storage/' . $article->featured_image) }}" 
                                    alt="{{ htmlspecialchars($article->title) }}"
                                    loading="lazy"
                                />
                            </a>
                        @else
                            <div class="news-card-image">
                                <img 
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300'%3E%3Crect fill='%23e5e5e5' width='400' height='300'/%3E%3C/svg%3E" 
                                    alt="No image"
                                />
                            </div>
                        @endif

                        <div class="news-card-body">
                            @if($article->category)
                                <a href="{{ route('category.show', $article->category->slug) }}" class="news-category">
                                    {{ $article->category->name }}
                                </a>
                            @endif

                            <a href="{{ route('news.show', $article->slug) }}" class="news-card-title">
                                {{ htmlspecialchars(Str::limit($article->title, 60)) }}
                            </a>

                            @if($article->description)
                                <p class="news-card-description">
                                    {{ Str::limit(strip_tags($article->description), 80) }}
                                </p>
                            @endif

                            <div class="news-card-meta">
                                <span class="news-date">
                                    {{ $article->published_at?->diffForHumans() ?? 'অসম্প্রতি' }}
                                </span>
                                <span class="news-views">
                                    👁️ {{ number_format($article->views ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($query)
            <!-- No Results Found -->
            <div class="empty-result">
                <div class="empty-result-icon">🔍</div>
                <h2 class="empty-result-title">কোনো ফলাফল পাওয়া যায়নি</h2>
                <p class="empty-result-text">
                    "<strong>{{ htmlspecialchars($query) }}</strong>" অনুসন্ধানে কোনো ফলাফল পাওয়া যায়নি।<br>
                    দয়া করে অন্য কিছু অনুসন্ধান করে দেখুন অথবা আমাদের ক্যাটাগরি দেখুন।
                </p>
                <a href="{{ route('home') }}" class="empty-result-link">হোমপেজে ফিরুন</a>
            </div>
        @else
            <!-- No Search Performed Yet -->
            <div class="empty-result">
                <div class="empty-result-icon">📰</div>
                <h2 class="empty-result-title">খবর খুঁজে বের করুন</h2>
                <p class="empty-result-text">
                    উপরের সার্চ বক্সে আপনার পছন্দের খবর, ক্যাটাগরি বা লেখকের নাম লিখুন।<br>
                    আমরা আপনার জন্য সেরা ফলাফল খুঁজে বের করব।
                </p>
                <a href="{{ route('home') }}" class="empty-result-link">সর্বশেষ খবর দেখুন</a>
            </div>
        @endif
    </div>
</div>

@endsection
