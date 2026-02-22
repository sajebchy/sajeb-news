@extends('public.layout')

@section('title', $news->meta_title ?? $news->title)
@section('description', $news->meta_description ?? $news->excerpt)
@section('canonical', route('news.show', $news->slug))

@section('og_title', $news->meta_title ?? $news->title)
@section('og_description', $news->og_description ?? $news->excerpt)
@section('og_image', $news->og_image ?? ($news->featured_image ?? ''))
@section('og_url', route('news.show', $news->slug))

@section('twitter_title', $news->meta_title ?? $news->title)
@section('twitter_description', $news->og_description ?? $news->excerpt)
@section('twitter_image', $news->og_image ?? ($news->featured_image ?? ''))

@section('schema')
    @php
        $schemaSettings = \App\Models\SchemaSetting::getInstance();
    @endphp
    
    <!-- NewsArticle Schema (auto-generated for non-Fact-Checker categories) -->
    @if($schemaSettings->enable_news_article_schema && !$news->category->is_fact_checker)
        <script type="application/ld+json">
        {!! json_encode(\App\Services\SchemaGeneratorService::newsArticleSchema($news)) !!}
        </script>
    @endif
    
    <!-- VideoObject Schema (auto-generated for embedded videos) -->
    @if($schemaSettings->enable_video_object_schema && \App\Services\SchemaGeneratorService::hasVideos($news))
        @foreach(\App\Services\SchemaGeneratorService::extractVideoSchemasFromContent($news) as $videoSchema)
        <script type="application/ld+json">
        {!! json_encode($videoSchema) !!}
        </script>
        @endforeach
    @endif
    
    <!-- ClaimReview Schema (if applicable) -->
    @if($news->is_claim_review && $schemaSettings->enable_claim_review_schema)
        <script type="application/ld+json">
        {!! json_encode(\App\Services\SchemaGeneratorService::newsClaimReviewSchema($news)) !!}
        </script>
    @endif
    
    <!-- Breadcrumb Schema -->
    @if($schemaSettings->enable_breadcrumb_schema)
        <script type="application/ld+json">
        {!! json_encode(\App\Services\SchemaGeneratorService::breadcrumbSchema([
            ['name' => 'হোম', 'url' => route('home')],
            ['name' => $news->category->name, 'url' => route('category.show', $news->category->slug)],
            ['name' => $news->title, 'url' => route('news.show', $news->slug)]
        ])) !!}
        </script>
    @endif
    
    <!-- Author Person Schema -->
    @if($schemaSettings->enable_person_schema && $news->author)
        <script type="application/ld+json">
        {!! json_encode(\App\Services\SchemaGeneratorService::personSchema($news->author)) !!}
        </script>
    @endif
@endsection

@section('content')
<style>
    /* Breadcrumb */
    .breadcrumb-section {
        background: #f8f9fa;
        padding: 15px 0;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 30px;
    }

    .breadcrumb {
        margin-bottom: 0;
        padding: 0;
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        gap: 0;
    }

    .breadcrumb li {
        display: flex;
        align-items: center;
    }

    .breadcrumb li a {
        color: #667eea;
        text-decoration: none;
        transition: all 0.3s ease;
        padding: 8px 12px;
    }

    .breadcrumb li a:hover {
        color: #764ba2;
    }

    .breadcrumb li.active a {
        color: #333;
        font-weight: 600;
        cursor: default;
        padding: 8px 12px;
    }

    .breadcrumb li:not(:last-child)::after {
        content: "›";
        color: #999;
        font-size: 1.3rem;
        margin: 0 5px;
        font-weight: 300;
    }

    @media (max-width: 768px) {
        .breadcrumb {
            font-size: 0.9rem;
        }

        .breadcrumb li a,
        .breadcrumb li.active a {
            padding: 5px 8px;
        }

        .breadcrumb li:not(:last-child)::after {
            margin: 0 3px;
        }
    }

    /* Hero Banner */
    .news-hero {
        display: none;
    }

    .news-hero-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        filter: brightness(0.6);
        z-index: 1;
    }

    .news-hero-content {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 60px 30px;
        color: white;
    }

    .news-category-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        width: fit-content;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .news-title-hero {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .news-meta-hero {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        font-size: 14px;
        opacity: 0.95;
    }

    .news-meta-hero-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Main Content Area */
    .news-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
        position: relative;
        z-index: 3;
    }

    .news-main-section {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-top: 30px;
    }

    .news-body-wrapper {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 40px;
        padding: 40px;
    }

    .article-content {
        font-size: 1.05rem;
        line-height: 1.8;
        color: #333;
    }

    .article-featured-image {
        float: left;
        width: 40%;
        margin-right: 25px;
        margin-bottom: 20px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .article-featured-image img {
        width: 100%;
        height: auto;
        display: block;
        border-radius: 10px;
    }

    .article-featured-image-caption {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.4;
        padding: 12px;
        background: #f9f9f9;
        border-radius: 0 0 10px 10px;
        text-align: center;
        font-weight: 500;
    }

    .article-content p {
        margin-bottom: 18px;
        text-align: justify;
    }

    .article-content h2 {
        margin-top: 35px;
        margin-bottom: 18px;
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a1a1a;
        border-bottom: 3px solid #667eea;
        padding-bottom: 10px;
    }

    .article-content h3 {
        margin-top: 28px;
        margin-bottom: 15px;
        font-size: 1.4rem;
        font-weight: 700;
        color: #333;
    }

    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 25px 0;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .article-content blockquote {
        border-left: 5px solid #667eea;
        padding-left: 20px;
        margin: 25px 0;
        font-size: 1.1rem;
        font-style: italic;
        color: #555;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
    }

    .article-content ul, .article-content ol {
        margin-left: 25px;
        margin-bottom: 18px;
    }

    .article-content li {
        margin-bottom: 10px;
        line-height: 1.6;
    }

    .article-content code {
        background: #f4f4f4;
        padding: 3px 8px;
        border-radius: 4px;
        font-family: 'Monaco', monospace;
        font-size: 0.9rem;
        color: #d63384;
    }

    .article-content pre {
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 20px;
        border-radius: 8px;
        overflow-x: auto;
        margin: 20px 0;
    }

    .article-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 25px 0;
        border-radius: 8px;
        overflow: hidden;
    }

    .article-content table th {
        background: #667eea;
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: 600;
    }

    .article-content table td {
        border: 1px solid #e0e0e0;
        padding: 12px;
    }

    .article-content table tr:nth-child(even) {
        background: #f9f9f9;
    }

    /* Author Section */
    .author-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 12px;
        color: white;
        margin-bottom: 30px;
    }

    .author-info {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .author-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.3);
    }

    .author-details h5 {
        margin-bottom: 5px;
        font-weight: 700;
    }

    .author-details small {
        opacity: 0.9;
        display: block;
    }

    /* Share Buttons */
    .share-section {
        padding: 25px 0;
        border-top: 2px solid #e0e0e0;
        border-bottom: 2px solid #e0e0e0;
        margin: 30px 0;
    }

    .share-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 10px;
    }

    .share-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px;
        border-radius: 8px;
        text-decoration: none;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .share-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .share-facebook { background: #1877F2; }
    .share-twitter { background: #1DA1F2; }
    .share-whatsapp { background: #25D366; }
    .share-linkedin { background: #0A66C2; }
    .share-copy { background: #6c757d; }

    /* Tags */
    .tags-section {
        margin-bottom: 30px;
    }

    .tag {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        text-decoration: none;
        font-size: 0.9rem;
        margin-right: 8px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .tag:hover {
        background: #764ba2;
        transform: scale(1.05);
    }

    /* Sidebar */
    .sidebar-widget {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }

    .widget-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 18px;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .widget-body {
        padding: 0;
    }

    .related-item {
        display: flex;
        gap: 12px;
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
        transition: background 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .related-item:hover {
        background: #f9f9f9;
    }

    .related-item:last-child {
        border-bottom: none;
    }

    .related-thumb {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .related-content h6 {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: #1a1a1a;
        line-height: 1.3;
    }

    .related-meta {
        font-size: 0.8rem;
        color: #999;
    }

    /* Latest News List */
    .news-list-item {
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .news-list-item:hover {
        background: #f9f9f9;
        padding-left: 20px;
    }

    .news-list-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a1a1a;
        line-height: 1.4;
        flex-grow: 1;
    }

    .news-list-badge {
        background: #667eea;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-left: 10px;
    }

    /* Category List */
    .category-item {
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
        text-decoration: none;
        color: inherit;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .category-item:hover {
        background: #f9f9f9;
        padding-left: 20px;
    }

    .category-name {
        font-weight: 600;
        color: #333;
    }

    .category-count {
        background: #667eea;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Push Notification Section */
    .push-notification-section {
        display: grid;
        grid-template-columns: 1fr 200px;
        gap: 30px;
        align-items: center;
    }

    /* Comment Form Responsive */
    .comment-login-buttons {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 10px !important;
    }

    .comment-form-actions {
        display: flex !important;
        gap: 10px !important;
        flex-wrap: wrap !important;
    }

    .comment-form-actions button,
    .comment-form-actions a {
        flex: 1 1 auto !important;
        min-width: 120px !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .article-featured-image {
            float: none;
            width: 100%;
            margin-right: 0;
            margin-bottom: 25px;
        }

        .news-body-wrapper {
            grid-template-columns: 1fr;
            padding: 20px;
            gap: 30px;
        }

        .news-title-hero {
            font-size: 1.8rem;
        }

        .news-hero {
            height: 350px;
        }

        .news-hero-content {
            padding: 40px 20px;
        }

        .push-notification-section {
            grid-template-columns: 1fr;
            gap: 20px;
            align-items: stretch;
        }

        .push-notification-section .push-btn {
            width: 100%;
            padding: 15px 20px !important;
        }

        /* Comment Form Mobile */
        .comment-login-buttons {
            grid-template-columns: 1fr !important;
        }

        .comment-login-buttons a {
            padding: 12px 15px !important;
            font-size: 0.9rem !important;
        }

        .comment-form-actions {
            flex-direction: column !important;
        }

        .comment-form-actions button {
            width: 100% !important;
            padding: 12px 15px !important;
            font-size: 0.9rem !important;
        }

        .form-control {
            font-size: 16px !important;
        }
    }
</style>


<!-- Breadcrumb Section -->
<div class="breadcrumb-section">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
        <ol class="breadcrumb">
            <li>
                <a href="{{ route('home') }}" title="হোম পৃষ্ঠা">
                    <i class="fas fa-home" aria-hidden="true"></i> হোম
                </a>
            </li>
            <li>
                <a href="{{ route('category.show', $news->category->slug) }}" title="{{ $news->category->name }}">
                    {{ $news->category->name }}
                </a>
            </li>
            <li class="active">
                <a href="{{ route('news.show', $news->slug) }}" title="{{ $news->title }}">
                    {{ substr($news->title, 0, 50) }}{{ strlen($news->title) > 50 ? '...' : '' }}
                </a>
            </li>
        </ol>
    </div>
</div>

<!-- Main Content Container -->
<div class="news-container">
    <div class="news-main-section">
        <div class="news-body-wrapper">
            <!-- Main Content -->
            <main>
                <!-- Article Content with Featured Image -->
                <article class="article-content">
                    <!-- Featured Image (Left Side) -->
                    @if($news->featured_image)
                    <div class="article-featured-image">
                        <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}" title="{{ $news->title }}">
                        <div class="article-featured-image-caption">
                            {{ $news->title }}
                        </div>
                    </div>
                    @endif

                    {!! $news->content !!}
                </article>

                <!-- Google AdSense Between Articles Ad -->
                @php
                    $adService = new \App\Services\AdService();
                    $betweenArticlesAdCode = $adService->getBetweenArticlesAdCode();
                @endphp
                @if($betweenArticlesAdCode && $adService->showBetweenArticlesAds())
                <div style="my-4; padding: 20px 0; text-align: center; background: #f5f5f5; margin: 30px 0; border-radius: 8px;">
                    {!! $betweenArticlesAdCode !!}
                </div>
                @endif

                <!-- Tags -->
                @if($news->tags && $news->tags->count() > 0)
                <div class="tags-section">
                    <strong style="display: block; margin-bottom: 15px; font-size: 1.1rem;">ট্যাগ:</strong>
                    @foreach($news->tags as $tag)
                    <a href="{{ route('tag.show', $tag->slug) }}" class="tag">
                        #{{ $tag->name }}
                    </a>
                    @endforeach
                </div>
                @endif

                <!-- Share Section -->
                <div class="share-section">
                    <h6 style="margin-bottom: 15px; font-weight: 700;">এই নিউজ শেয়ার করুন:</h6>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('news.show', $news->slug) }}" 
                           class="share-btn share-facebook" target="_blank" rel="noopener" title="Facebook">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ route('news.show', $news->slug) }}&text={{ urlencode($news->title) }}" 
                           class="share-btn share-twitter" target="_blank" rel="noopener" title="Twitter">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text={{ route('news.show', $news->slug) }}" 
                           class="share-btn share-whatsapp" target="_blank" rel="noopener" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ route('news.show', $news->slug) }}" 
                           class="share-btn share-linkedin" target="_blank" rel="noopener" title="LinkedIn">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                        <button class="share-btn share-copy" onclick="copyToClipboard('{{ route('news.show', $news->slug) }}')" title="Copy Link">
                            <i class="fas fa-link"></i> কপি
                        </button>
                    </div>
                </div>

                <!-- Author Card -->
                @if($news->author)
                <div class="author-card">
                    <div class="author-info">
                        @if($news->author->avatar)
                        <a href="{{ route('author.show', $news->author->id) }}" style="text-decoration: none;">
                            <img src="{{ asset('storage/' . $news->author->avatar) }}" alt="{{ $news->author->name }}" class="author-avatar">
                        </a>
                        @else
                        <a href="{{ route('author.show', $news->author->id) }}" style="text-decoration: none;">
                            <div class="author-avatar" style="background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; cursor: pointer;">
                                {{ strtoupper(substr($news->author->name, 0, 1)) }}
                            </div>
                        </a>
                        @endif
                        <div class="author-details">
                            <h5 style="margin: 0;">
                                <a href="{{ route('author.show', $news->author->id) }}" style="color: white; text-decoration: none; cursor: pointer; transition: all 0.3s;">
                                    {{ $news->author->name }}
                                </a>
                            </h5>
                            <small>লেখক</small>
                            @php
                                $articleCount = $news->author->newsArticles()->published()->count();
                            @endphp
                            <small style="display: block; margin-top: 5px; opacity: 0.9;">📝 {{ $articleCount }} টি আর্টিকেল</small>
                            @if($news->author->bio)
                            <small>{{ substr($news->author->bio, 0, 100) }}{{ strlen($news->author->bio) > 100 ? '...' : '' }}</small>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </main>

            <!-- Sidebar -->
            <aside>
                <!-- Related News -->
                @if($related && $related->count() > 0)
                <div class="sidebar-widget">
                    <div class="widget-header">
                        <i class="fas fa-link"></i> সম্পর্কিত খবর
                    </div>
                    <div class="widget-body">
                        @foreach($related->take(5) as $item)
                        <a href="{{ route('news.show', $item->slug) }}" class="related-item">
                            @if($item->featured_image)
                            <img src="{{ asset('storage/' . $item->featured_image) }}" alt="{{ $item->title }}" class="related-thumb">
                            @endif
                            <div class="related-content">
                                <h6>{{ substr($item->title, 0, 45) }}{{ strlen($item->title) > 45 ? '...' : '' }}</h6>
                                <div class="related-meta">{{ $item->published_at?->format('d M Y') }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Latest News -->
                <div class="sidebar-widget">
                    <div class="widget-header">
                        <i class="fas fa-fire"></i> সর্বশেষ খবর
                    </div>
                    <div class="widget-body">
                        @foreach(\App\Models\News::where('status', 'published')->latest('published_at')->limit(6)->get() as $item)
                        <a href="{{ route('news.show', $item->slug) }}" class="news-list-item">
                            <span class="news-list-title">{{ substr($item->title, 0, 35) }}{{ strlen($item->title) > 35 ? '...' : '' }}</span>
                            <span class="news-list-badge">{{ $item->views }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Categories -->
                <div class="sidebar-widget">
                    <div class="widget-header">
                        <i class="fas fa-list"></i> ক্যাটেগরি
                    </div>
                    <div class="widget-body">
                        @foreach(\App\Models\Category::with('news')->limit(8)->get() as $category)
                        <a href="{{ route('category.show', $category->slug) }}" class="category-item">
                            <span class="category-name">{{ $category->name }}</span>
                            <span class="category-count">{{ $category->news->count() }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- Comments Section - Below main container -->
<div style="max-width: 1200px; margin: 60px auto; padding: 0 15px;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08); padding: 40px;">
        <h2 style="margin-bottom: 30px; font-size: 1.8rem; font-weight: 700; color: #1a1a1a;">
            <i class="fas fa-comments"></i> মন্তব্য
        </h2>

        <!-- Success/Error Messages -->
        @if(session('comment_success'))
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('comment_success') }}</span>
        </div>
        @endif

        @if(session('comment_error'))
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 10px; align-items: center;">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('comment_error') }}</span>
        </div>
        @endif

        <!-- Comment Form -->
        @if(!auth()->check())
        <div style="background: #e7f3ff; border-left: 4px solid #2196F3; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <p style="margin-bottom: 15px; color: #1565c0; font-weight: 500;">
                <i class="fas fa-info-circle"></i> মন্তব্য করতে হলে প্রথমে লগইন করুন।
            </p>
            <div class="comment-login-buttons" style="gap: 10px;">
                <a href="{{ route('login') }}" class="btn btn-primary" style="text-decoration: none; padding: 10px 15px;">
                    <i class="fas fa-user"></i> ইমেইল দিয়ে লগইন
                </a>
                <a href="{{ route('auth.facebook') }}" class="btn" style="background: #1877F2; color: white; text-decoration: none; padding: 10px 15px; border-radius: 6px; text-align: center; font-weight: 600;">
                    <i class="fab fa-facebook"></i> Facebook দিয়ে লগইন
                </a>
            </div>
        </div>
        @else
        <div style="background: #f9f9f9; padding: 25px; border-radius: 8px; margin-bottom: 40px;">
            <h6 style="margin-bottom: 15px; font-weight: 600;">আপনার মন্তব্য শেয়ার করুন</h6>
            <form method="POST" action="{{ route('news.comments.store', $news->slug) }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 1rem;">মন্তব্য *</label>
                    <textarea 
                        name="comment_text" 
                        id="comment_text"
                        class="form-control @error('comment_text') is-invalid @enderror" 
                        rows="5"
                        placeholder="আপনার মন্তব্য লিখুন..."
                        required
                        style="border-radius: 8px; border: 1px solid #ddd; padding: 12px; font-size: 1rem; width: 100%; box-sizing: border-box; resize: vertical; min-height: 120px;">{{ old('comment_text') }}</textarea>
                    @error('comment_text')
                    <div class="invalid-feedback" style="display: block; color: #dc3545; font-size: 0.875rem; margin-top: 5px;">
                        <i class="fas fa-times-circle"></i> {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="submit" id="submitBtn" class="btn btn-primary" style="padding: 10px 30px; font-weight: 600; white-space: nowrap; border: none; cursor: pointer;">
                        <i class="fas fa-paper-plane"></i> মন্তব্য প্রকাশ করুন
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" style="padding: 10px 30px; font-weight: 600; white-space: nowrap; border: 1px solid #ddd; background: white; cursor: pointer;">
                        <i class="fas fa-redo"></i> পরিষ্কার
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Comments List -->
        <div>
            <h5 style="margin-bottom: 20px; font-weight: 700;">মন্তব্যসমূহ</h5>
            @forelse($news->comments()->where('approved', true)->latest()->get() as $comment)
            <div style="display: flex; gap: 15px; padding: 20px; border-bottom: 1px solid #e0e0e0;">
                @if($comment->user && $comment->user->avatar)
                <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="{{ $comment->user->name }}" 
                     style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                @else
                <div style="width: 45px; height: 45px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">
                    {{ $comment->user ? strtoupper(substr($comment->user->name, 0, 1)) : 'A' }}
                </div>
                @endif
                <div style="flex-grow: 1;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <strong style="color: #1a1a1a;">{{ $comment->user?->name ?? 'অজানা ব্যবহারকারী' }}</strong>
                        <small style="color: #999;">{{ $comment->created_at?->diffForHumans() ?? 'সম্প্রতি' }}</small>
                    </div>
                    <p style="margin: 0; color: #555; line-height: 1.6;">{{ $comment->comment_text }}</p>
                </div>
            </div>
            @empty
            <div style="background: #e8f5e9; border-left: 4px solid #4caf50; padding: 20px; border-radius: 8px; color: #2e7d32;">
                <i class="fas fa-info-circle"></i> এখনও কোনো মন্তব্য নেই। প্রথম মন্তব্যকারী হন!
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Push Notification Section -->
<div style="max-width: 1200px; margin: 60px auto; padding: 0 15px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);">
        <div class="push-notification-section">
            <div>
                <h4 style="margin-bottom: 10px; font-size: 1.3rem; font-weight: 700;">
                    <i class="fas fa-bell"></i> পুশ নোটিফিকেশন চালু করুন
                </h4>
                <p style="margin: 0; opacity: 0.95;">
                    "{{ $news->category->name }}" ক্যাটেগরির সব নতুন খবর সরাসরি আপনার ডিভাইসে পান। কখনও কোনো গুরুত্বপূর্ণ খবর মিস করবেন না।
                </p>
            </div>
            <button class="push-subscribe-btn push-btn" style="background: white; color: #667eea; border: none; padding: 12px 25px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                <i class="fas fa-bell"></i> চালু করুন
            </button>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('লিঙ্ক কপি হয়েছে!');
        }).catch(err => {
            console.error('কপি করতে ব্যর্থ:', err);
        });
    }

    // Push Notification Handler
    document.addEventListener('DOMContentLoaded', function() {
        const subscribeBtn = document.querySelector('.push-subscribe-btn');
        if (subscribeBtn) {
            const checkManager = setInterval(function() {
                if (window.PushNotificationManager) {
                    clearInterval(checkManager);
                    const manager = new PushNotificationManager();
                    
                    if (!manager.isSupported()) {
                        subscribeBtn.textContent = '⛔ সাপোর্ট নেই';
                        subscribeBtn.disabled = true;
                        return;
                    }

                    manager.isEnabled().then(enabled => {
                        if (enabled) {
                            subscribeBtn.innerHTML = '<i class="fas fa-check-circle"></i> ✓ সক্ষম';
                            subscribeBtn.style.background = '#4caf50';
                            subscribeBtn.disabled = true;
                        }
                    });

                    subscribeBtn.addEventListener('click', async function() {
                        subscribeBtn.disabled = true;
                        subscribeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> অপেক্ষা...';

                        try {
                            const result = await manager.subscribe();
                            if (result.success) {
                                subscribeBtn.innerHTML = '<i class="fas fa-check-circle"></i> ✓ সক্ষম';
                                subscribeBtn.style.background = '#4caf50';
                            } else {
                                alert(result.message || 'সাবস্ক্রিপশন ব্যর্থ');
                                subscribeBtn.disabled = false;
                                subscribeBtn.innerHTML = '<i class="fas fa-bell"></i> চালু করুন';
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            subscribeBtn.disabled = false;
                            subscribeBtn.innerHTML = '<i class="fas fa-bell"></i> চালু করুন';
                        }
                    });
                }
            }, 100);

            setTimeout(() => clearInterval(checkManager), 2000);
        }
    });
</script>

@endsection
