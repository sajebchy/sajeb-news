@extends('public.layout')

@section('title', '৫০০ - সার্ভার ত্রুটি')
@section('description', 'দুঃখিত, সার্ভারে একটি ত্রুটি ঘটেছে। দয়া করে পরে আবার চেষ্টা করুন।')

@section('content')
<main class="main-content py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <!-- Error Icon -->
                <div class="error-icon mb-4">
                    <i class="fas fa-server" style="font-size: 5rem; color: #ffc107;"></i>
                </div>

                <!-- Error Code -->
                <h1 class="error-code display-1 fw-bold text-warning mb-3">৫০০</h1>

                <!-- Error Title -->
                <h2 class="error-title mb-3">সার্ভার ত্রুটি</h2>

                <!-- Error Message -->
                <p class="error-message text-muted mb-4">
                    দুঃখিত, সার্ভারে একটি অপ্রত্যাশিত ত্রুটি ঘটেছে। 
                    আমাদের প্রযুক্তিগত দল এই সমস্যাটি সমাধানে কাজ করছে।
                </p>

                <!-- Status -->
                <div class="alert alert-info mb-4">
                    <p class="mb-0">
                        <strong>অনুগ্রহ করে কয়েক মিনিট পরে আবার চেষ্টা করুন।</strong>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="error-actions">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-2 mb-2">
                        <i class="fas fa-home"></i> হোমে ফিরে যান
                    </a>
                    <a href="javascript:location.reload()" class="btn btn-outline-primary btn-lg mb-2">
                        <i class="fas fa-redo"></i> পুনরায় লোড করুন
                    </a>
                </div>

                <!-- Contact Support -->
                <div class="mt-5 pt-4 border-top">
                    <p class="text-muted small mb-3">সমস্যা অব্যাহত থাকলে আমাদের সাথে যোগাযোগ করুন</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-envelope"></i> সাপোর্টে যোগাযোগ করুন
                    </a>
                </div>

                <!-- Related Links -->
                <div class="mt-5 pt-4">
                    <h4 class="mb-3">অন্যান্য বিকল্প</h4>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('home') }}" class="btn btn-light btn-sm w-100">হোম</a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('live.index') }}" class="btn btn-light btn-sm w-100">লাইভ স্ট্রিম</a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('news.search') }}" class="btn btn-light btn-sm w-100">সংবাদ খুঁজুন</a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('contact') }}" class="btn btn-light btn-sm w-100">যোগাযোগ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.error-icon {
    animation: shake 0.5s infinite;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
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
