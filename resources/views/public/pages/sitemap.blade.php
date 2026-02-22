@extends('public.layout')

@section('title', $metaTags['title'] ?? 'সাইট ম্যাপ - Sajeb NEWS')
@section('description', $metaTags['description'] ?? '')
@section('keywords', $metaTags['keywords'] ?? '')
@section('canonical', $metaTags['canonical'] ?? '')
@section('og_title', $metaTags['og_title'] ?? '')
@section('og_url', $metaTags['og_url'] ?? '')

@section('content')
<main class="main-content py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                        <li class="breadcrumb-item active">সাইট ম্যাপ</li>
                    </ol>
                </nav>

                <!-- Page Header -->
                <div class="page-header mb-5 text-center">
                    <h1 class="page-title mb-3">সাইট ম্যাপ</h1>
                    <p class="text-muted small">Sajeb NEWS এর সমস্ত পৃষ্ঠা এবং বিভাগ</p>
                </div>

                <!-- Sitemap Content -->
                <div class="sitemap-wrapper">
                    <!-- Main Pages -->
                    <div class="sitemap-section mb-5">
                        <h2 class="sitemap-title">📄 প্রধান পৃষ্ঠাগুলি</h2>
                        <ul class="sitemap-list">
                            <li><a href="{{ route('home') }}">হোম</a></li>
                            <li><a href="{{ route('about') }}">আমাদের সম্পর্কে</a></li>
                            <li><a href="{{ route('contact') }}">যোগাযোগ করুন</a></li>
                            <li><a href="{{ route('live.index') }}">লাইভ স্ট্রিম</a></li>
                            <li><a href="{{ route('privacy') }}">গোপনীয়তা নীতি</a></li>
                            <li><a href="{{ route('terms') }}">শর্তাবলী</a></li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div class="sitemap-section mb-5">
                        <h2 class="sitemap-title">📚 ক্যাটাগরি</h2>
                        @if($categories->count() > 0)
                            <ul class="sitemap-list">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('category.show', ['category' => $category->slug]) }}">
                                            {{ $category->name }}
                                        </a>
                                        <span class="category-count">({{ $category->news_count ?? 0 }} সংবাদ)</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">কোনো ক্যাটাগরি পাওয়া যায়নি</p>
                        @endif
                    </div>

                    <!-- Recent News -->
                    <div class="sitemap-section mb-5">
                        <h2 class="sitemap-title">📰 সর্বশেষ সংবাদ (প্রথম ৫০টি)</h2>
                        @if($recentNews->count() > 0)
                            <ul class="sitemap-list news-list">
                                @foreach($recentNews as $news)
                                    <li>
                                        <a href="{{ route('news.show', ['news' => $news->slug]) }}">
                                            {{ $news->title }}
                                        </a>
                                        <span class="news-date">{{ $news->published_at?->format('d M Y') ?? 'N/A' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">কোনো সংবাদ পাওয়া যায়নি</p>
                        @endif
                    </div>

                    <!-- XML Sitemap -->
                    <div class="sitemap-section mb-5">
                        <h2 class="sitemap-title">🔗 সার্চ ইঞ্জিন সাইটম্যাপ</h2>
                        <p class="mb-3">সার্চ ইঞ্জিনের জন্য XML সাইটম্যাপ:</p>
                        <a href="{{ route('sitemap.xml') }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-download"></i> sitemap.xml ডাউনলোড করুন
                        </a>
                    </div>

                    <!-- LLM File -->
                    <div class="sitemap-section mb-5">
                        <h2 class="sitemap-title">🤖 AI/LLM তথ্য ফাইল</h2>
                        <p class="mb-3">বড় ভাষা মডেলের জন্য তথ্য ফাইল:</p>
                        <a href="{{ route('llm.txt') }}" class="btn btn-secondary" target="_blank">
                            <i class="fas fa-download"></i> llm.txt ডাউনলোড করুন
                        </a>
                    </div>

                    <!-- Statistics -->
                    <div class="sitemap-section mb-5">
                        <h2 class="sitemap-title">📊 সাইট পরিসংখ্যান</h2>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="stat-card p-3 border rounded text-center">
                                    <h4 class="stat-number">{{ $recentNews->count() }}</h4>
                                    <p class="stat-label">প্রকাশিত সংবাদ</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card p-3 border rounded text-center">
                                    <h4 class="stat-number">{{ $categories->count() }}</h4>
                                    <p class="stat-label">সক্রিয় ক্যাটাগরি</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card p-3 border rounded text-center">
                                    <h4 class="stat-number">{{ now()->format('d M Y') }}</h4>
                                    <p class="stat-label">সর্বশেষ আপডেট</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="sitemap-section mb-5 bg-light p-4 rounded">
                        <h2 class="sitemap-title">❓ সাহায্য প্রয়োজন?</h2>
                        <p>কোনো কিছু খুঁজে পাচ্ছেন না? আমাদের সাহায্য করুন:</p>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('news.search') }}">সংবাদ সার্চ করুন</a></li>
                            <li><a href="{{ route('contact') }}">আমাদের সাথে যোগাযোগ করুন</a></li>
                            <li><a href="{{ route('about') }}">আমাদের সম্পর্কে আরও জানুন</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
}

.sitemap-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    border-bottom: 3px solid #007bff;
    padding-bottom: 10px;
    margin-bottom: 1.5rem;
}

.sitemap-list {
    list-style: none;
    padding: 0;
}

.sitemap-list li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.sitemap-list li:last-child {
    border-bottom: none;
}

.sitemap-list a {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.sitemap-list a:hover {
    color: #0056b3;
    text-decoration: underline;
}

.category-count {
    background: #e7f3ff;
    color: #0056b3;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    margin-left: 10px;
}

.news-list li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.news-date {
    background: #f0f0f0;
    color: #666;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    margin-left: 10px;
}

.stat-card {
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.stat-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.stat-number {
    font-size: 2rem;
    color: #007bff;
    font-weight: 700;
    margin: 0;
}

.stat-label {
    color: #666;
    margin: 0.5rem 0 0 0;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .page-title {
        font-size: 1.8rem;
    }

    .sitemap-list li {
        padding: 0.75rem 0;
    }

    .news-list li {
        flex-direction: column;
        align-items: flex-start;
    }

    .news-date {
        margin-left: 0;
        margin-top: 0.5rem;
    }
}
</style>
@endsection
