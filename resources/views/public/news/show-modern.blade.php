@extends('public.layout')

@section('title', $news->meta_title ?? $news->title)
@section('description', $news->meta_description ?? $news->excerpt)
@section('canonical', route('news.show', $news->slug))

@section('og_title', $news->meta_title ?? $news->title)
@section('og_description', $news->og_description ?? $news->excerpt)
@section('og_image', $news->og_image ?? ($news->featured_image ?? ''))
@section('og_url', route('news.show', $news->slug))

@section('content')
<style>
    /* ===== MODERN POST DETAIL PAGE ===== */
    
    :root {
        --primary-color: #1565C0;
        --secondary-color: #af2c2c;
        --text-dark: #1a1a1a;
        --text-light: #666666;
        --border-color: #e0e0e0;
        --bg-light: #f5f5f5;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
        --shadow-lg: 0 10px 40px rgba(0, 0, 0, 0.15);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        box-sizing: border-box;
    }

    /* ===== BREADCRUMB NAVIGATION ===== */
    .breadcrumb-nav {
        background: white;
        border-bottom: 1px solid var(--border-color);
        padding: 12px 0;
        margin-bottom: 0;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .breadcrumb-nav .container {
        max-width: 900px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0 20px;
        font-size: 13px;
    }

    .breadcrumb-nav a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
    }

    .breadcrumb-nav a:hover {
        color: var(--secondary-color);
    }

    .breadcrumb-nav span {
        color: var(--text-light);
    }

    /* ===== ADVERTISEMENT BANNER ===== */
    .ad-banner {
        background: white;
        padding: 15px 0;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: center;
    }

    .ad-space {
        width: 100%;
        max-width: 728px;
        height: 90px;
        background: var(--bg-light);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 12px;
    }

    /* ===== MAIN ARTICLE CONTAINER ===== */
    .article-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    /* ===== ARTICLE HEADER ===== */
    .article-header {
        margin-bottom: 30px;
    }

    .article-category {
        display: inline-block;
        background: var(--primary-color);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        text-decoration: none;
        transition: var(--transition);
    }

    .article-category:hover {
        background: var(--secondary-color);
        color: white;
    }

    .article-title {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.3;
        color: var(--text-dark);
        margin-bottom: 20px;
        letter-spacing: -0.5px;
    }

    .article-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--bg-light);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--text-light);
    }

    .meta-author {
        font-weight: 600;
        color: var(--primary-color);
        text-decoration: none;
        transition: var(--transition);
    }

    .meta-author:hover {
        color: var(--secondary-color);
    }

    .meta-time {
        color: #999;
    }

    /* ===== FEATURED IMAGE ===== */
    .featured-image {
        width: 100%;
        margin: 30px 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }

    .featured-image img {
        width: 100%;
        height: auto;
        display: block;
        background: var(--bg-light);
    }

    .image-caption {
        background: var(--bg-light);
        padding: 14px;
        font-size: 13px;
        color: var(--text-light);
        text-align: center;
        font-style: italic;
        line-height: 1.5;
    }

    /* ===== ARTICLE CONTENT ===== */
    .article-body {
        font-size: 16px;
        line-height: 1.8;
        color: var(--text-dark);
    }

    .article-body p {
        margin-bottom: 20px;
    }

    .article-body h2 {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 30px 0 20px;
        color: var(--text-dark);
        line-height: 1.3;
    }

    .article-body h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 25px 0 15px;
        color: var(--text-dark);
    }

    .article-body ul,
    .article-body ol {
        margin: 20px 0;
        padding-left: 30px;
    }

    .article-body li {
        margin-bottom: 12px;
        line-height: 1.8;
    }

    .article-body blockquote {
        border-left: 4px solid var(--primary-color);
        padding: 20px 25px;
        margin: 25px 0;
        background: rgba(21, 101, 192, 0.05);
        font-style: italic;
        color: var(--text-dark);
        border-radius: 4px;
    }

    .article-body strong {
        font-weight: 700;
        color: var(--text-dark);
    }

    .article-body em {
        font-style: italic;
    }

    /* ===== SOCIAL SHARING ===== */
    .share-section {
        background: white;
        padding: 25px;
        border-radius: 8px;
        margin: 30px 0;
        border: 1px solid var(--border-color);
    }

    .share-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .share-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .share-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        background: white;
        color: var(--text-dark);
        transition: var(--transition);
        cursor: pointer;
    }

    .share-btn:hover {
        background: var(--bg-light);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .share-btn.facebook:hover {
        background: #1877F2;
        color: white;
        border-color: #1877F2;
    }

    .share-btn.twitter:hover {
        background: #000000;
        color: white;
        border-color: #000000;
    }

    .share-btn.whatsapp:hover {
        background: #25D366;
        color: white;
        border-color: #25D366;
    }

    .share-btn.email:hover {
        background: var(--secondary-color);
        color: white;
        border-color: var(--secondary-color);
    }

    /* ===== TAGS ===== */
    .article-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding: 25px;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        margin: 30px 0;
    }

    .tag-label {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-dark);
        margin-right: 5px;
    }

    .article-tag {
        display: inline-block;
        background: var(--bg-light);
        color: var(--primary-color);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        text-decoration: none;
        transition: var(--transition);
        border: 1px solid var(--border-color);
    }

    .article-tag:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* ===== AUTHOR BOX ===== */
    .author-box {
        background: linear-gradient(135deg, #f5f5f5 0%, #ffffff 100%);
        padding: 25px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        margin: 30px 0;
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .author-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        flex-shrink: 0;
        overflow: hidden;
    }

    .author-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .author-info h4 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 8px;
        color: var(--text-dark);
    }

    .author-info p {
        font-size: 14px;
        color: var(--text-light);
        margin: 0;
        line-height: 1.6;
    }

    /* ===== RELATED POSTS ===== */
    .related-section {
        background: white;
        padding: 40px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        margin: 40px 0;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid var(--primary-color);
    }

    .related-posts {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
    }

    .related-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
        transition: var(--transition);
        cursor: pointer;
    }

    .related-card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
        transform: translateY(-4px);
    }

    .related-card-image {
        width: 100%;
        height: 180px;
        background: var(--bg-light);
        overflow: hidden;
    }

    .related-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .related-card:hover .related-card-image img {
        transform: scale(1.05);
    }

    .related-card-body {
        padding: 15px;
    }

    .related-card-category {
        font-size: 11px;
        color: var(--primary-color);
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .related-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.4;
        margin-bottom: 8px;
        text-decoration: none;
    }

    .related-card-date {
        font-size: 12px;
        color: var(--text-light);
    }

    /* ===== COMMENTS SECTION ===== */
    .comments-section {
        background: white;
        padding: 40px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        margin: 40px 0;
    }

    .comment-form {
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: var(--transition);
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.1);
    }

    .btn-submit {
        background: var(--primary-color);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-submit:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* ===== COMMENTS LIST ===== */
    .comments-list {
        margin-top: 30px;
    }

    .comment-item {
        padding: 20px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        margin-bottom: 15px;
        background: white;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .comment-author {
        font-weight: 700;
        color: var(--text-dark);
    }

    .comment-date {
        font-size: 12px;
        color: var(--text-light);
    }

    .comment-text {
        font-size: 14px;
        color: var(--text-dark);
        line-height: 1.6;
    }

    /* ===== SIDEBAR (Desktop Only) ===== */
    .sidebar {
        display: none;
    }

    /* ===== RESPONSIVE DESIGN ===== */
    @media (max-width: 768px) {
        .article-title {
            font-size: 1.6rem;
        }

        .article-meta {
            gap: 10px;
        }

        .meta-item {
            font-size: 13px;
        }

        .article-body {
            font-size: 15px;
        }

        .share-buttons {
            gap: 8px;
        }

        .share-btn {
            flex: 1;
            min-width: 70px;
            padding: 8px 12px;
            font-size: 12px;
        }

        .author-box {
            flex-direction: column;
            gap: 15px;
        }

        .author-avatar {
            width: 60px;
            height: 60px;
        }

        .related-posts {
            grid-template-columns: 1fr;
        }

        .article-container {
            padding: 20px 15px;
        }

        .comments-section,
        .related-section,
        .article-tags,
        .share-section {
            padding: 20px;
        }

        .section-title {
            font-size: 1.3rem;
        }
    }

    @media (max-width: 480px) {
        .article-title {
            font-size: 1.3rem;
        }

        .breadcrumb-nav .container {
            font-size: 11px;
            gap: 4px;
        }

        .article-category {
            font-size: 10px;
            padding: 5px 10px;
        }

        .share-btn {
            padding: 8px 10px;
            font-size: 11px;
        }

        .article-tags {
            flex-direction: column;
        }

        .section-title {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
    }

</style>

<!-- Breadcrumb Navigation -->
<div class="breadcrumb-nav">
    <div class="container">
        <a href="{{ route('home') }}">হোম</a>
        <span>/</span>
        <a href="{{ route('category.show', $news->category->slug) }}">{{ $news->category->name }}</a>
        <span>/</span>
        <span>{{ $news->title }}</span>
    </div>
</div>

<!-- Advertisement Banner -->
<div class="ad-banner">
    <div class="ad-space">
        <!-- Ad Space 728x90 -->
    </div>
</div>

<!-- Main Article Container -->
<div class="article-container">
    <!-- Article Header -->
    <div class="article-header">
        <a href="{{ route('category.show', $news->category->slug) }}" class="article-category">
            {{ $news->category->name }}
        </a>
        
        <h1 class="article-title">{{ $news->title }}</h1>
        
        <div class="article-meta">
            <div class="meta-item">
                @if($news->author)
                    <strong>লেখক:</strong>
                    <a href="{{ route('author.show', $news->author->id) }}" class="meta-author">
                        {{ $news->author->name }}
                    </a>
                @endif
            </div>
            <div class="meta-item meta-time">
                📅 {{ $news->published_at?->format('d F Y, H:i') ?? 'অপ্রকাশিত' }}
            </div>
            <div class="meta-item">
                👁️ {{ $news->views ?? 0 }} ভিউ
            </div>
        </div>
    </div>

    <!-- Featured Image -->
    @if($news->featured_image)
    <div class="featured-image">
        <img 
            src="{{ asset('storage/' . $news->featured_image) }}" 
            alt="{{ $news->title }}"
            loading="lazy"
        >
        @if($news->featured_image_caption)
        <div class="image-caption">
            {{ $news->featured_image_caption }}
        </div>
        @endif
    </div>
    @endif

    <!-- Social Share Buttons -->
    <div class="share-section">
        <div class="share-title">এই নিবন্ধটি শেয়ার করুন:</div>
        <div class="share-buttons">
            <a href="https://www.facebook.com/sharer.php?u={{ urlencode(route('news.show', $news->slug)) }}" 
               class="share-btn facebook" target="_blank">
                📘 ফেসবুক
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($news->title . ' ' . route('news.show', $news->slug)) }}" 
               class="share-btn twitter" target="_blank">
                𝕏 টুইটার
            </a>
            <a href="https://api.whatsapp.com/send?text={{ urlencode($news->title . ' ' . route('news.show', $news->slug)) }}" 
               class="share-btn whatsapp" target="_blank">
                💬 হোয়াটসঅ্যাপ
            </a>
            <a href="mailto:?subject={{ urlencode($news->title) }}&body={{ urlencode(route('news.show', $news->slug)) }}" 
               class="share-btn email">
                ✉️ ইমেইল
            </a>
        </div>
    </div>

    <!-- Article Body -->
    <article class="article-body" id="articleContent">
        {!! $news->content !!}
    </article>

    <!-- Article Middle Ad (Injected via JS) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const article = document.getElementById('articleContent');
            const paragraphs = article.querySelectorAll('p');
            
            // Add ad after 2nd paragraph
            if (paragraphs.length > 2) {
                const adDiv2nd = document.createElement('div');
                adDiv2nd.className = 'ad-article-2nd-paragraph';
                adDiv2nd.style.margin = '30px 0';
                adDiv2nd.innerHTML = `
                    <p style="font-size: 12px; color: #999; text-align: center;">বিজ্ঞাপন</p>
                    <div style="text-align: center;">
                        @php
                            $ad2ndPara = \App\Helpers\AdHelper::getRandomAdByPlacement('article_2nd_paragraph');
                        @endphp
                        @if($ad2ndPara)
                            {!! \App\Helpers\AdHelper::renderAd($ad2ndPara) !!}
                        @endif
                    </div>
                `;
                paragraphs[2].parentNode.insertBefore(adDiv2nd, paragraphs[2].nextSibling);
            }
            
            // Add ad in middle
            if (paragraphs.length > Math.floor(paragraphs.length / 2)) {
                const middleIndex = Math.floor(paragraphs.length / 2);
                const adDivMiddle = document.createElement('div');
                adDivMiddle.className = 'ad-article-middle';
                adDivMiddle.style.margin = '30px 0';
                adDivMiddle.innerHTML = `
                    <p style="font-size: 12px; color: #999; text-align: center;">বিজ্ঞাপন</p>
                    <div style="text-align: center;">
                        @php
                            $adMiddle = \App\Helpers\AdHelper::getRandomAdByPlacement('article_middle');
                        @endphp
                        @if($adMiddle)
                            {!! \App\Helpers\AdHelper::renderAd($adMiddle) !!}
                        @endif
                    </div>
                `;
                paragraphs[middleIndex].parentNode.insertBefore(adDivMiddle, paragraphs[middleIndex].nextSibling);
            }
        });
    </script>

    <!-- Tags Section -->
    @if($news->tags && count($news->tags) > 0)
    <div class="article-tags">
        <span class="tag-label">ট্যাগ:</span>
        @foreach($news->tags as $tag)
        <a href="{{ route('tag.show', $tag->slug) }}" class="article-tag">
            {{ $tag->name }}
        </a>
        @endforeach
    </div>
    @endif

    <!-- Author Box -->
    @if($news->author)
    <div class="author-box">
        <div class="author-avatar">
            @if($news->author->avatar)
                <img src="{{ asset($news->author->avatar) }}" alt="{{ $news->author->name }}">
            @else
                {{ substr($news->author->name, 0, 1) }}
            @endif
        </div>
        <div class="author-info">
            <h4>{{ $news->author->name }}</h4>
            <p>{{ $news->author->bio ?? 'এই লেখক সম্পর্কে আরও জানুন।' }}</p>
        </div>
    </div>
    @endif

    <!-- Article Conclusion Ad (Before Comments) -->
    <div style="margin: 40px 0;">
        <x-ad-placement placement="article_conclusion" random="true" class="ad-before-conclusion" />
    </div>

    <!-- Below Article Content Ad -->
    <div style="margin: 40px 0 60px 0;">
        <x-ad-placement placement="below_article" random="true" class="ad-below-article" />
    </div>

    <!-- Related Posts Section -->
    @if($related && count($related) > 0)
    <section class="related-section">
        <h2 class="section-title">সম্পর্কিত নিবন্ধসমূহ</h2>
        <div class="related-posts">
            @foreach($related->take(3) as $relatedPost)
            <a href="{{ route('news.show', $relatedPost->slug) }}" class="related-card">
                <div class="related-card-image">
                    <img 
                        src="{{ $relatedPost->featured_image ? asset('storage/' . $relatedPost->featured_image) : asset('images/placeholder.png') }}" 
                        alt="{{ $relatedPost->title }}"
                        loading="lazy"
                    >
                </div>
                <div class="related-card-body">
                    <div class="related-card-category">{{ $relatedPost->category->name }}</div>
                    <h3 class="related-card-title">{{ $relatedPost->title }}</h3>
                    <div class="related-card-date">
                        {{ $relatedPost->published_at?->format('d M Y') ?? 'বিভাগ' }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Comments Section -->
    @if($news->comments_enabled ?? true)
    <section class="comments-section">
        <h2 class="section-title">মন্তব্য</h2>

        <!-- Comment Form -->
        <div class="comment-form">
            <h3 style="margin-bottom: 20px;">আপনার মন্তব্য যোগ করুন</h3>
            <form method="POST" action="{{ route('news.comments.store', $news->slug) }}">
                @csrf
                <input type="hidden" name="news_id" value="{{ $news->id }}">
                
                <div class="form-group">
                    <label for="name">নাম *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        required
                        placeholder="আপনার নাম"
                    >
                </div>

                <div class="form-group">
                    <label for="email">ইমেইল *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        placeholder="আপনার ইমেইল"
                    >
                </div>

                <div class="form-group">
                    <label for="comment">মন্তব্য *</label>
                    <textarea 
                        id="comment" 
                        name="content" 
                        rows="5" 
                        required
                        placeholder="আপনার মন্তব্য লিখুন..."
                    ></textarea>
                </div>

                <button type="submit" class="btn-submit">মন্তব্য পোস্ট করুন</button>
            </form>
        </div>

        <!-- Comments List -->
        @if($news->approvedComments && count($news->approvedComments) > 0)
        <div class="comments-list">
            <h3 style="margin-bottom: 20px;">সমস্ত মন্তব্য</h3>
            @foreach($news->approvedComments as $comment)
            <div class="comment-item">
                <div class="comment-header">
                    <span class="comment-author">{{ $comment->name }}</span>
                    <span class="comment-date">{{ $comment->created_at?->format('d M Y H:i') }}</span>
                </div>
                <p class="comment-text">{{ $comment->content }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </section>
    @endif

</div>

@endsection
