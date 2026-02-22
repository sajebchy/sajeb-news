@extends('public.layout')

@section('title', '৪০৪ - পৃষ্ঠা পাওয়া যায়নি')
@section('description', 'দুঃখিত, আপনি যে পৃষ্ঠাটি খুঁজছেন তা পাওয়া যায়নি। Sajeb NEWS এ ফিরে যান।')

@section('content')
<main class="main-content py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <!-- Error Icon -->
                <div class="error-icon mb-4">
                    <i class="fas fa-exclamation-triangle" style="font-size: 5rem; color: #dc3545;"></i>
                </div>

                <!-- Error Code -->
                <h1 class="error-code display-1 fw-bold text-danger mb-3">৪০৪</h1>

                <!-- Error Title -->
                <h2 class="error-title mb-3">পৃষ্ঠা পাওয়া যায়নি</h2>

                <!-- Error Message -->
                <p class="error-message text-muted mb-4">
                    দুঃখিত, আপনি যে পৃষ্ঠাটি খুঁজছেন তা আর পাওয়া যায় না বা কখনো ছিল না। 
                    এটি সম্ভব যে আপনি ভুল URL টাইপ করেছেন বা লিঙ্কটি পরিবর্তিত হয়েছে।
                </p>

                <!-- Action Buttons -->
                <div class="error-actions">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-2 mb-2">
                        <i class="fas fa-home"></i> হোমে ফিরে যান
                    </a>
                    <a href="{{ route('news.search') }}" class="btn btn-outline-primary btn-lg mb-2">
                        <i class="fas fa-search"></i> সংবাদ খুঁজুন
                    </a>
                </div>

                <!-- Google Search Suggestion -->
                <div class="mt-5 pt-4 border-top">
                    <p class="text-muted small mb-3">আপনি Google এ খুঁজতে চান?</p>
                    <form action="https://www.google.com/search" method="GET" target="_blank" class="d-flex">
                        <input type="hidden" name="q" value="site:{{ parse_url(config('app.url'), PHP_URL_HOST) }}">
                        <input type="text" name="q" class="form-control" placeholder="খুঁজুন..." required>
                        <button type="submit" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Related Links -->
                <div class="mt-5 pt-4">
                    <h4 class="mb-3">জনপ্রিয় পৃষ্ঠা</h4>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('home') }}" class="btn btn-light btn-sm w-100">হোম</a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('about') }}" class="btn btn-light btn-sm w-100">সম্পর্কে</a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('contact') }}" class="btn btn-light btn-sm w-100">যোগাযোগ</a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('live.index') }}" class="btn btn-light btn-sm w-100">লাইভ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.error-icon {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

.error-code {
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.error-title {
    font-size: 2rem;
    font-weight: 600;
    color: #333;
}

.error-message {
    font-size: 1.1rem;
    line-height: 1.6;
}

.error-actions {
    margin-top: 2rem;
}

.btn-primary, .btn-outline-primary {
    border-radius: 50px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .error-code {
        font-size: 3rem;
    }

    .error-title {
        font-size: 1.5rem;
    }

    .error-icon i {
        font-size: 3rem !important;
    }
}
</style>
@endsection
