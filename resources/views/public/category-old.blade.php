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
    
    <!-- Breadcrumb Schema for Category Page -->
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
<div class="container mt-5">
    <!-- Category Header -->
    <div class="mb-5">
        <h1 class="display-4 fw-bold mb-3">{{ $category->name }}</h1>
        @if($category->description)
        <p class="lead text-muted">{{ $category->description }}</p>
        @endif
        <hr>
    </div>

    <!-- Push Notification Subscribe Section -->
    <section class="mb-5">
        <div class="card bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            <div class="card-body p-4 p-md-5 text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="card-title mb-2">
                            <i class="fas fa-bell"></i> {{ $category->name }} এর সব খবর পান
                        </h5>
                        <p class="card-text mb-0 opacity-90">
                            এই ক্যাটাগরির নতুন খবর সরাসরি আপনার ব্রাউজারে পান। কোনো ব্যাঘাত নেই - শুধু খবর।
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <button class="push-subscribe-btn btn btn-light btn-sm" style="font-weight: 600;">
                            <i class="fas fa-bell"></i> সক্ষম করুন
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Grid -->
    @if($news && $news->count() > 0)
    <div class="row g-4 mb-5">
        @foreach($news as $article)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card h-100 shadow-sm news-card">
                @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <span class="badge bg-primary mb-2">{{ $category->name }}</span>
                    <h5 class="card-title flex-grow-1">
                        <a href="{{ route('news.show', $article->slug) }}" class="text-decoration-none text-dark">
                            {{ substr($article->title, 0, 60) }}...
                        </a>
                    </h5>
                    <p class="card-text text-muted small">{{ $article->excerpt ? substr($article->excerpt, 0, 80) . '...' : '' }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-secondary">{{ $article->published_at?->diffForHumans() }}</small>
                        <small class="text-primary">
                            <i class="fas fa-eye"></i> {{ $article->views }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($news instanceof \Illuminate\Pagination\Paginator)
    <nav class="mt-5 mb-5">
        {{ $news->links() }}
    </nav>
    @endif
    @else
    <div class="alert alert-info" role="alert">
        <h5>এই ক্যাটেগরিতে এখনও কোন খবর নেই।</h5>
        <p>শীঘ্রই এই ক্যাটেগরিতে খবর যোগ করা হবে। অন্যান্য ক্যাটেগরি দেখুন।</p>
    </div>

    <!-- Other Categories -->
    <div class="row g-3 mt-4">
        <h3 class="mb-4">অন্যান্য ক্যাটেগরি</h3>
        @foreach(\App\Models\Category::where('id', '!=', $category->id)->limit(6)->get() as $otherCategory)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('category.show', $otherCategory->slug) }}" class="text-decoration-none text-dark">
                            {{ $otherCategory->name }}
                        </a>
                    </h5>
                    <p class="card-text text-muted small">{{ $otherCategory->description ? substr($otherCategory->description, 0, 100) . '...' : '' }}</p>
                    <span class="badge bg-secondary">{{ $otherCategory->news->count() }} খবর</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Related Categories -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">সম্পর্কিত ক্যাটেগরি</h3>
            <div class="d-flex flex-wrap gap-2">
                @foreach(\App\Models\Category::limit(8)->get() as $relatedCategory)
                <a href="{{ route('category.show', $relatedCategory->slug) }}" class="btn btn-outline-primary btn-sm">
                    {{ $relatedCategory->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .news-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.2) !important;
    }
</style>

<script>
    // Push Notification Subscribe Button Handler
    document.addEventListener('DOMContentLoaded', function() {
        const subscribeButtons = document.querySelectorAll('.push-subscribe-btn');
        
        if (subscribeButtons.length === 0) return;

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
            subscribeButtons.forEach(btn => {
                const manager = new PushNotificationManager();
                
                // Check if browser supports push notifications
                if (!manager.isSupported()) {
                    btn.innerHTML = '<i class="fas fa-ban"></i> সাপোর্ট নেই';
                    btn.disabled = true;
                    btn.classList.add('disabled');
                    return;
                }

                // Check if already subscribed
                checkIfSubscribed(manager, btn);

                // Add click handler
                btn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> অপেক্ষা...';

                    try {
                        const result = await manager.subscribe();
                        
                        if (result.success) {
                            btn.innerHTML = '<i class="fas fa-check-circle"></i> ✓ সক্ষম';
                            btn.classList.remove('btn-light');
                            btn.classList.add('btn-success');
                            btn.style.cursor = 'default';
                        } else {
                            showAlert('error', result.message || 'সাবস্ক্রিপশন ব্যর্থ');
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-bell"></i> সক্ষম করুন';
                        }
                    } catch (error) {
                        console.error('Subscribe error:', error);
                        showAlert('error', 'একটি ত্রুটি ঘটেছে।');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-bell"></i> সক্ষম করুন';
                    }
                });
            });
        }

        function checkIfSubscribed(manager, btn) {
            manager.isEnabled().then(enabled => {
                if (enabled) {
                    btn.innerHTML = '<i class="fas fa-check-circle"></i> ✓ সক্ষম';
                    btn.classList.remove('btn-light');
                    btn.classList.add('btn-success');
                    btn.disabled = true;
                    btn.style.cursor = 'default';
                }
            });
        }

        function showAlert(type, message) {
            const alertEl = document.createElement('div');
            alertEl.className = `alert alert-${type} alert-dismissible fade show`;
            alertEl.setAttribute('role', 'alert');
            alertEl.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            const container = document.querySelector('.container');
            if (container) {
                container.insertBefore(alertEl, container.firstChild);
                setTimeout(() => {
                    alertEl.remove();
                }, 5000);
            }
        }
    });
</script>
@endsection
