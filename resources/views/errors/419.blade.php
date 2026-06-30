@extends('public.layout')

@section('title', '৪১৯ - অনুপ্রবেশকারী হিসাবে অথেন্টিকেট করা হয়নি')
@section('description', 'এই পৃষ্ঠা অ্যাক্সেস করতে আপনাকে প্রথমে লগইন করতে হবে।')

@section('content')
<main class="main-content py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto text-center">
                <!-- Error Icon -->
                <div class="error-icon mb-4">
                    <i class="fas fa-lock" style="font-size: 5rem; color: #6c757d;"></i>
                </div>

                <!-- Error Code -->
                <h1 class="error-code display-1 fw-bold text-secondary mb-3">৪১৯</h1>

                <!-- Error Title -->
                <h2 class="error-title mb-3">অনুমোদন প্রয়োজন</h2>

                <!-- Error Message -->
                <p class="error-message text-muted mb-4">
                    এই পৃষ্ঠা অ্যাক্সেস করতে আপনাকে প্রথমে লগইন করতে হবে।
                </p>

                <!-- Action Buttons -->
                <div class="error-actions">
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg me-2 mb-2">
                        <i class="fas fa-sign-in-alt"></i> লগইন করুন
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg mb-2">
                        <i class="fas fa-home"></i> হোমে ফিরে যান
                    </a>
                </div>

                <!-- Help Text -->
                <div class="mt-5 pt-4 border-top">
                    <p class="text-muted small">এখনও সদস্য নন?</p>
                    <a href="{{ route('register') }}" class="btn btn-outline-success">
                        <i class="fas fa-user-plus"></i> সাইন আপ করুন
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
