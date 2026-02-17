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

    /* Breaking News Bar */
    .breaking-news {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 12px 0;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .breaking-news-content {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .breaking-badge {
        background: rgba(255,255,255,0.2);
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
        text-transform: uppercase;
    }

    .ticker {
        flex: 1;
        overflow: hidden;
        position: relative;
    }

    .ticker-content {
        display: flex;
        animation: scroll 40s linear infinite;
        gap: 30px;
    }

    @keyframes scroll {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }

    .ticker-content a {
        color: white;
        text-decoration: none;
        white-space: nowrap;
        padding: 0 15px;
        border-left: 1px solid rgba(255,255,255,0.2);
        transition: opacity 0.3s;
    }

    .ticker-content a:hover {
        opacity: 0.8;
    }

    /* Main Container */
    .homepage-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
    }

    /* Header Section */
    .header-section {
        background: white;
        padding: 20px 0;
        margin-bottom: 32px;
        border-bottom: 1px solid #e5e5e0;
    }

    .site-title {
        font-size: 28px;
        font-weight: 700;
        color: #1b1b18;
        margin-bottom: 8px;
    }

    .site-tagline {
        color: #706f6c;
        font-size: 14px;
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
    }

    .subscribe-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Featured News Section */
    .featured-section {
        margin-bottom: 48px;
    }

    .section-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 24px;
        color: #1b1b18;
        display: flex;
        align-items: center;
        gap: 8px;
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
        margin-right: 10px;
    }

    .trending-title {
        font-size: 13px;
        font-weight: 600;
        color: #1b1b18;
        text-decoration: none;
        display: inline-block;
        line-height: 1.3;
    }

    .trending-title:hover {
        color: #3b82f6;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 40px;
    }

    .pagination a, .pagination span {
        padding: 8px 12px;
        border: 1px solid #e5e5e0;
        border-radius: 4px;
        text-decoration: none;
        color: #1b1b18;
        transition: all 0.3s;
    }

    .pagination a:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .pagination .active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
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

        .sidebar {
            flex-direction: column;
        }

        .section-title {
            font-size: 18px;
        }

        .site-title {
            font-size: 22px;
        }
    }
</style>

<div class="homepage-container">
    <!-- Breaking News -->
    @if($breaking->count() > 0)
    <div class="breaking-news">
        <div class="breaking-news-content">
            <span class="breaking-badge">🔴 ব্রেকিং নিউজ</span>
            <div class="ticker">
                <div class="ticker-content">
                    @foreach($breaking as $news)
                    <a href="{{ route('news.show', $news->slug) }}">{{ $news->title }}</a>
                    @endforeach
                    @foreach($breaking as $news)
                    <a href="{{ route('news.show', $news->slug) }}">{{ $news->title }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="header-section">
        <h1 class="site-title">Sajeb NEWS</h1>
        <p class="site-tagline">বাংলাদেশের সর্বশেষ এবং সবচেয়ে নির্ভরযোগ্য খবর পোর্টাল</p>
    </div>

    <!-- Subscribe Banner -->
    <div class="subscribe-banner">
        <div>
            <h3>📬 নতুন খবর সরাসরি পান!</h3>
            <p>প্রতিদিন সর্বশেষ খবর সরাসরি আপনার কাছে</p>
        </div>
        <button id="push-subscribe-btn" class="subscribe-btn">সাবস্ক্রাইব করুন</button>
    </div>

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
                    <div style="width: 100%; height: 240px; background: #e5e5e0; display: flex; align-items: center; justify-content: center; color: #706f6c;">
                        কোন ছবি নেই
                    </div>
                    @endif
                    <div class="featured-card-content">
                        <span class="featured-card-category">{{ $news->category->name ?? 'খবর' }}</span>
                        <h3 class="featured-card-title">{{ $news->title }}</h3>
                        <p style="font-size: 13px; color: #706f6c; line-height: 1.4; margin-bottom: 12px;">
                            {{ substr($news->excerpt ?? $news->content, 0, 100) }}...
                        </p>
                        <div class="featured-card-meta">
                            {{ $news->published_at?->diffForHumans() ?? 'নতুন' }}
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
                        <div style="width: 100%; height: 180px; background: #e5e5e0; display: flex; align-items: center; justify-content: center; color: #706f6c;">
                            কোন ছবি
                        </div>
                        @endif
                        <div class="news-card-content">
                            <span class="news-card-category">{{ $news->category->name ?? 'খবর' }}</span>
                            <h3 class="news-card-title">{{ $news->title }}</h3>
                            <div class="news-card-time">{{ $news->published_at?->diffForHumans() ?? 'নতুন' }}</div>
                        </div>
                    </a>
                </div>
                @empty
                <div style="grid-column: 1/-1; padding: 40px 20px; text-align: center; color: #706f6c;">
                    কোনো খবর পাওয়া যাচ্ছে না
                </div>
                @endforelse
            </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none text-dark">
                                {{ substr($news->title, 0, 50) }}...
                            </a>
                        </h5>
                        <p class="card-text text-muted small">{{ $news->excerpt ? substr($news->excerpt, 0, 100) . '...' : '' }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-secondary">{{ $news->published_at?->diffForHumans() }}</small>
                            <small class="text-primary">{{ $news->category->name }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Trending News Section -->
    @if($trending->count() > 0)
    <section class="mb-5">
        <h2 class="mb-4">📈 ট্রেন্ডিং নিউজ</h2>
        <div class="row">
            <div class="col-lg-8">
                <div class="list-group">
                    @foreach($trending->take(5) as $index => $news)
                    <a href="{{ route('news.show', $news->slug) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                <h6 class="mb-1">{{ substr($news->title, 0, 60) }}...</h6>
                                <small class="text-muted">{{ $news->views }} ভিউ • {{ $news->published_at?->diffForHumans() }}</small>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">নিউজলেটার সাবস্ক্রাইব করুন</h5>
                        <p class="card-text">সর্বশেষ খবর সরাসরি আপনার ইনবক্সে পান।</p>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <input type="email" name="email" class="form-control form-control-sm" placeholder="আপনার ইমেইল" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">সাবস্ক্রাইব করুন</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Latest News Section -->
    @if($latest->count() > 0)
    <section class="mb-5">
        <h2 class="mb-4">📰 সর্বশেষ খবর</h2>
        <div class="row g-3">
            @foreach($latest as $news)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm news-card">
                    @if($news->featured_image)
                    <img src="{{ asset('storage/' . $news->featured_image) }}" class="card-img-top" alt="{{ $news->title }}" style="height: 150px; object-fit: cover;">
                    @endif
                    <div class="card-body d-flex flex-column">
                        <span class="badge bg-secondary mb-2">{{ $news->category->name }}</span>
                        <h6 class="card-title flex-grow-1">
                            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none text-dark">
                                {{ substr($news->title, 0, 40) }}...
                            </a>
                        </h6>
                        <small class="text-muted">{{ $news->published_at?->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($latest instanceof \Illuminate\Pagination\Paginator)
        <nav class="mt-4">
            {{ $latest->links() }}
        </nav>
        @endif
    </section>
    @endif
</div>

<style>
    .news-card {
        transition: transform 0.2s;
    }
    .news-card:hover {
        transform: translateY(-5px);
    }
</style>

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
                        subscribeBtn.classList.remove('btn-light');
                        subscribeBtn.classList.add('btn-success');
                        subscribeBtn.style.cursor = 'default';
                    } else {
                        showAlert('error', result.message || 'সাবস্ক্রিপশন ব্যর্থ হয়েছে');
                        subscribeBtn.disabled = false;
                        subscribeBtn.innerHTML = '<i class="fas fa-bell"></i> এখনই সক্ষম করুন';
                    }
                } catch (error) {
                    console.error('Subscribe error:', error);
                    showAlert('error', 'একটি ত্রুটি ঘটেছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।');
                    subscribeBtn.disabled = false;
                    subscribeBtn.innerHTML = '<i class="fas fa-bell"></i> এখনই সক্ষম করুন';
                }
            });
        }

        function checkIfSubscribed(manager) {
            manager.isEnabled().then(enabled => {
                if (enabled) {
                    subscribeBtn.innerHTML = '<i class="fas fa-check-circle"></i> ✓ সক্ষম করা আছে';
                    subscribeBtn.classList.remove('btn-light');
                    subscribeBtn.classList.add('btn-success');
                    subscribeBtn.disabled = true;
                    subscribeBtn.style.cursor = 'default';
                }
            });
        }

        function showAlert(type, message) {
            // Create alert element
            const alertEl = document.createElement('div');
            alertEl.className = `alert alert-${type} alert-dismissible fade show`;
            alertEl.setAttribute('role', 'alert');
            alertEl.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert at top of container
            const container = document.querySelector('.container');
            if (container) {
                container.insertBefore(alertEl, container.firstChild);
                
                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    alertEl.remove();
                }, 5000);
            }
        }
    });
</script>
@endsection
