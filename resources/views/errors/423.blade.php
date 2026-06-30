@extends('public.layout')

@section('title', '৪২৩ - অনুমতি শেষ')
@section('description', 'দুঃখিত, আপনার এই পদক্ষেপের অনুমতি নেই।')

@section('content')
<main class="main-content py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <!-- Error Icon -->
                <div class="error-icon mb-4">
                    <i class="fas fa-ban" style="font-size: 5rem; color: #dc3545;"></i>
                </div>

                <!-- Error Code -->
                <h1 class="error-code display-1 fw-bold text-danger mb-3">৪২৩</h1>

                <!-- Error Title -->
                <h2 class="error-title mb-3">অনুমতি শেষ</h2>

                <!-- Error Message -->
                <p class="error-message text-muted mb-4">
                    দুঃখিত, আপনার এই পদক্ষেপের অনুমতি নেই। এটি একটি সীমিত বৈশিষ্ট্য।
                </p>

                <!-- Action Buttons -->
                <div class="error-actions">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home"></i> হোমে ফিরে যান
                    </a>
                </div>

                <!-- Help -->
                <div class="mt-5 pt-4 border-top">
                    <p class="text-muted small">সমস্যা অব্যাহত থাকলে যোগাযোগ করুন</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-envelope"></i> সাপোর্ট
                    </a>
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

.btn-primary {
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
