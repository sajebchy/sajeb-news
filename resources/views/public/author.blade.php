@extends('public.layout')

@section('title', $author->name . ' - লেখক - Sajeb NEWS')
@section('description', $author->bio ?? $author->name . ' এর লেখা সব আর্টিকেল দেখুন')
@section('canonical', route('author.show', $author->id))

@section('content')

<style>
    .author-page {
        background: #f8f8f7;
    }

    .author-header {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        color: white;
        padding: 60px 0;
        margin-bottom: 40px;
    }

    .author-header-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
        display: flex;
        align-items: center;
        gap: 32px;
    }

    .author-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: white;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        overflow: hidden;
    }

    .author-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .author-info h1 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .author-bio {
        font-size: 16px;
        opacity: 0.95;
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .author-stats {
        display: flex;
        gap: 32px;
        font-size: 14px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .stat-number {
        font-weight: 700;
        font-size: 18px;
    }

    .container-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .section-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 24px;
        color: #1b1b18;
    }

    /* News Grid */
    .news-grid {
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
        object-fit: cover;
        display: block;
    }

    .news-card-content {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .news-card-badge {
        display: inline-block;
        background: #ede9fe;
        color: #6d28d9;
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

    .news-card-title a {
        text-decoration: none;
        color: inherit;
    }

    .news-card-title a:hover {
        color: #8b5cf6;
    }

    .news-card-excerpt {
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
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #706f6c;
    }

    .empty-state-icon {
        font-size: 56px;
        margin-bottom: 16px;
        opacity: 0.6;
    }

    .empty-state-text {
        font-size: 18px;
        margin-bottom: 24px;
        font-weight: 500;
    }

    .empty-state a {
        color: #8b5cf6;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .empty-state a:hover {
        color: #6d28d9;
        text-decoration: underline;
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
        background: #8b5cf6;
        color: white;
        border-color: #8b5cf6;
    }

    .pagination-wrapper .active {
        background: #8b5cf6;
        color: white;
        border-color: #8b5cf6;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .news-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .author-header {
            padding: 40px 0;
        }

        .author-header-content {
            flex-direction: column;
            text-align: center;
            gap: 24px;
        }

        .author-info h1 {
            font-size: 28px;
        }

        .author-stats {
            justify-content: center;
            gap: 20px;
        }

        .news-grid {
            grid-template-columns: 1fr;
        }

        .empty-state {
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 48px;
        }
    }
</style>

<div class="author-page">
    <!-- Author Header -->
    <div class="author-header">
        <div class="author-header-content">
            <div class="author-avatar">
                @if($author->avatar)
                    <img src="{{ asset('storage/' . $author->avatar) }}" alt="{{ $author->name }}">
                @else
                    {{ substr($author->name, 0, 1) }}
                @endif
            </div>
            <div class="author-info">
                <h1>{{ $author->name }}</h1>
                @if($author->bio)
                <p class="author-bio">{{ $author->bio }}</p>
                @endif
                <div class="author-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $author->newsArticles()->published()->count() }}</span>
                        <span>প্রকাশিত আর্টিকেল</span>
                    </div>
                    @php
                        $totalViews = $author->newsArticles()->published()->sum('views');
                    @endphp
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($totalViews) }}</span>
                        <span>মোট ভিউ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-content">
        <!-- Articles -->
        <section>
            <h2 class="section-title">📰 {{ $author->name }} এর আর্টিকেলসমূহ</h2>
            
            @if($news && $news->count() > 0)
            <div class="news-grid">
                @foreach($news as $article)
                <div class="news-card">
                    <a href="{{ route('news.show', $article->slug) }}">
                        @if($article->featured_image)
                        <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="news-card-image">
                        @else
                        <div style="width: 100%; height: 180px; background: #e5e5e0; display: flex; align-items: center; justify-content: center; color: #706f6c;">
                            কোন ছবি
                        </div>
                        @endif
                    </a>
                    <div class="news-card-content">
                        @if($article->category)
                        <a href="{{ route('category.show', $article->category->slug) }}">
                            <div class="news-card-badge">{{ $article->category->name }}</div>
                        </a>
                        @endif
                        <div class="news-card-title">
                            <a href="{{ route('news.show', $article->slug) }}">{{ $article->title }}</a>
                        </div>
                        @if($article->excerpt)
                        <p class="news-card-excerpt">{{ substr($article->excerpt, 0, 80) }}...</p>
                        @endif
                        <div class="news-card-meta" style="flex-direction: column; align-items: flex-start; gap: 8px; border-top: 1px solid #e5e5e0; padding-top: 8px;">
                            <span style="font-size: 12px;">{{ $article->published_at?->format('d M Y') }}</span>
                            <span style="font-size: 12px;">👁 {{ number_format($article->views ?? 0) }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($news->hasPages())
            <div class="pagination-wrapper">
                {{ $news->links() }}
            </div>
            @endif
            @else
            <div class="empty-state">
                <div class="empty-state-icon">📝</div>
                <div class="empty-state-text">এই লেখক এখনো কোনো আর্টিকেল প্রকাশ করেননি</div>
                <a href="{{ route('home') }}">← হোমপেইজে ফিরে যান</a>
            </div>
            @endif
        </section>
    </div>
</div>

@endsection
