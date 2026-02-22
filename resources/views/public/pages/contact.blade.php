@extends('public.layout')

@section('title', $metaTags['title'] ?? 'যোগাযোগ করুন - Sajeb NEWS')
@section('description', $metaTags['description'] ?? '')
@section('keywords', $metaTags['keywords'] ?? '')
@section('canonical', $metaTags['canonical'] ?? '')
@section('og_title', $metaTags['og_title'] ?? '')
@section('og_description', $metaTags['og_description'] ?? '')
@section('og_url', $metaTags['og_url'] ?? '')

@push('schema')
<script type="application/ld+json">
{!! json_encode($schema) !!}
</script>
@endpush

@section('content')
<main class="main-content py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                        <li class="breadcrumb-item active">যোগাযোগ করুন</li>
                    </ol>
                </nav>

                <!-- Page Header -->
                <div class="page-header mb-5 text-center">
                    <h1 class="page-title mb-3">যোগাযোগ করুন</h1>
                    <p class="text-muted small">আমাদের সাথে যোগাযোগ করতে নিচের ফর্মটি পূরণ করুন</p>
                </div>

                <!-- Success/Error Messages -->
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Contact Form -->
                <div class="contact-form-container bg-light p-5 rounded">
                    <form action="{{ route('contact.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">নাম <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">ইমেইল <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">ফোন নম্বর</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">বিষয় <span class="text-danger">*</span></label>
                            <select class="form-select @error('subject') is-invalid @enderror" 
                                    id="subject" name="subject" required>
                                <option value="">বিষয় নির্বাচন করুন</option>
                                <option value="বিজ্ঞাপন" {{ old('subject') == 'বিজ্ঞাপন' ? 'selected' : '' }}>বিজ্ঞাপন</option>
                                <option value="অংশীদারিত্ব" {{ old('subject') == 'অংশীদারিত্ব' ? 'selected' : '' }}>অংশীদারিত্ব</option>
                                <option value="প্রযুক্তিগত সমস্যা" {{ old('subject') == 'প্রযুক্তিগত সমস্যা' ? 'selected' : '' }}>প্রযুক্তিগত সমস্যা</option>
                                <option value="মতামত/পরামর্শ" {{ old('subject') == 'মতামত/পরামর্শ' ? 'selected' : '' }}>মতামত/পরামর্শ</option>
                                <option value="অন্যান্য" {{ old('subject') == 'অন্যান্য' ? 'selected' : '' }}>অন্যান্য</option>
                            </select>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">বার্তা <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                            <small class="form-text text-muted">কমপক্ষে ১০ এবং সর্বোচ্চ ২০০০ শব্দ</small>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">বার্তা পাঠান</button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="contact-info mt-5">
                    <h2 class="section-title mb-4">আমাদের সাথে যোগাযোগ করুন</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="info-card p-4 border rounded">
                                <h4 class="mb-2">📧 ইমেইল</h4>
                                <p><a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card p-4 border rounded">
                                <h4 class="mb-2">📱 সোশ্যাল মিডিয়া</h4>
                                <p>
                                    <a href="https://facebook.com/sajebnews" target="_blank">Facebook</a> | 
                                    <a href="https://twitter.com/sajebnews" target="_blank">Twitter</a> | 
                                    <a href="https://youtube.com/@sajebnews" target="_blank">YouTube</a>
                                </p>
                            </div>
                        </div>
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

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    border-bottom: 3px solid #007bff;
    padding-bottom: 10px;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.info-card {
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.info-card h4 {
    color: #333;
    font-weight: 600;
}

.info-card a {
    color: #007bff;
    text-decoration: none;
}

.info-card a:hover {
    text-decoration: underline;
}
</style>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
@endsection
