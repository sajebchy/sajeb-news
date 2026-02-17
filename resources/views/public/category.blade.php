@extends('public.layout')

@section('title', $category->meta_title ?? $category->name . ' - Sajeb NEWS')
@section('description', $category->meta_description ?? $category->description)

@section('og_title', $category->meta_title ?? $category->name)
@section('og_description', $category->meta_description ?? $category->description)
@section('og_url', route('category.show', $category->slug))

@section('schema')
    @php
        $schemaSettings = \App\Models\SchemaSetting::getInstance();
    @endphp
    
    @if($schemaSettings->enable_breadcrumb_schema)
        <script type="application/ld+json">
        {!! json_encode(\App\Services\SchemaGeneratorService::breadcrumbSchema([
            ['name' => 'হোম', 'url' => route('home')],
            ['name' => $category->name, 'url' => route('category.show', $category->slug)]
        ])) !!}
        </script>
    @endif
@endsection

@section('content')

<style>
    .category-page {
        background: #f8f8f7;
    }

    .category-header {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        padding: 50px 0;
        margin-bottom: 40px;
    }

    .category-header-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .category-header h1 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .category-header p {
        font-size: 16px;
        opacity: 0.95;
        margin-bottom: 0;
    }

    .category-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
    }

    /* Subcategories Section */
    .subcategories-section {
        margin-bottom: 48px;
    }

    .section-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 24px;
        color: #1b1b18;
    }

    .subcategories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 16px;
        margin-bottom: 40px;
    }

    .subcategory-card {
        background: white;
        border-radius: 8px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none;
        color: #1b1b18;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .subcategory-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        background: #f0f7ff;
    }

    .subcategory-icon {
        font-size: 36px;
        line-height: 1;
    }

    .subcategory-name {
        font-weight: 700;
        font-size: 15px;
        line-height: 1.3;
    }

    .subcategory-count {
        font-size: 13px;
        color: #706f6c;
        background: #f8f8f7;
        padding: 4px 12px;
        border-radius: 999px;
    }

    /* News Grid */
    .news-section {
        margin-bottom: 48px;
    }

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

    .news-card-title a {
        text-decoration: none;
        color: inherit;
    }

    .news-card-title a:hover {
        color: #3b82f6;
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
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .empty-state a:hover {
        color: #1e40af;
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
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .pagination-wrapper .active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .news-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .category-header {
            padding: 30px 0;
        }

        .category-header h1 {
            font-size: 28px;
        }

        .subcategories-grid {
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 12px;
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

<div class="category-page">
    <!-- Category Header -->
    <div class="category-header">
        <div class="category-header-content">
            <h1>{{ $category->name }}</h1>
            @if($category->description)
            <p>{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <div class="category-container">
        <!-- Sub-categories -->
        @php
            $subcategories = $category->children()->where('is_active', true)->get();
        @endphp
        
        @if($subcategories->count() > 0)
        <section class="subcategories-section">
            <h2 class="section-title">📂 সাব-বিভাগসমূহ</h2>
            <div class="subcategories-grid">
                @foreach($subcategories as $subcategory)
                <a href="{{ route('category.show', $subcategory->slug) }}" class="subcategory-card">
                    <div class="subcategory-icon">{{ $subcategory->icon ?? '📁' }}</div>
                    <div class="subcategory-name">{{ $subcategory->name }}</div>
                    <div class="subcategory-count">{{ $subcategory->news()->count() }} খবর</div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- News in Category -->
        <section class="news-section">
            <h2 class="section-title">📰 {{ $category->name }} এর সর্বশেষ খবর</h2>
            
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
                        <div class="news-card-meta" style="flex-direction: column; align-items: flex-start; gap: 8px;">
                            <span>{{ $article->published_at?->diffForHumans() ?? 'নতুন' }}</span>
                            @if($article->author)
                            <a href="{{ route('author.show', $article->author->id) }}" style="color: #706f6c; text-decoration: none; font-size: 11px; font-weight: 500;">লিখেছেন: {{ $article->author->name }}</a>
                            @endif
                            <span>👁 {{ $article->views ?? 0 }}</span>
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
                <div class="empty-state-icon">📭</div>
                <div class="empty-state-text">এই বিভাগে কোনো খবর পাওয়া যাচ্ছে না</div>
                <a href="{{ route('home') }}">← হোমপেইজে ফিরে যান</a>
            </div>
            @endif
        </section>
    </div>
</div>

@endsection
