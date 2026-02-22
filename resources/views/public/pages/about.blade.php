@extends('public.layout')

@section('title', $metaTags['title'] ?? 'আমাদের সম্পর্কে - Sajeb NEWS')
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
                        <li class="breadcrumb-item active">আমাদের সম্পর্কে</li>
                    </ol>
                </nav>

                <!-- Page Header -->
                <div class="page-header mb-5 text-center">
                    <h1 class="page-title mb-3">আমাদের সম্পর্কে</h1>
                    <p class="text-muted small">Sajeb NEWS এর যাত্রা এবং লক্ষ্য সম্পর্কে জানুন</p>
                </div>

                <!-- About Content -->
                <article class="about-content">
                    @if($aboutContent)
                        <!-- Custom Content from Admin Settings -->
                        <div class="custom-about-content">
                            {!! $aboutContent !!}
                        </div>
                    @else
                        <!-- Default Content -->
                        <section class="mb-5">
                            <h2 class="section-title mb-3">Sajeb NEWS কী?</h2>
                            <p>Sajeb NEWS হল বাংলাদেশের একটি শীর্ষস্থানীয় অনলাইন নিউজ পোর্টাল যা দ্রুততম এবং সবচেয়ে নির্ভরযোগ্য সংবাদ প্রদান করে। আমরা রাজনীতি, খেলাধুলা, বিনোদন, প্রযুক্তি এবং আরও অনেক বিষয়ে ব্যাপক কভারেজ প্রদান করি।</p>
                            <p>আমাদের প্রধান লক্ষ্য হল বাংলাদেশের প্রতিটি কোণে নির্ভরযোগ্য এবং সময়োপযোগী তথ্য পৌঁছে দেওয়া।</p>
                        </section>

                        <section class="mb-5">
                            <h2 class="section-title mb-3">আমাদের মিশন</h2>
                            <p>আমাদের মিশন হল:</p>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>✓ সত্যতা:</strong> প্রতিটি খবরের সত্যতা যাচাই করা</li>
                                <li class="mb-2"><strong>✓ গতি:</strong> সর্বশেষ খবর সবচেয়ে আগে প্রদান করা</li>
                                <li class="mb-2"><strong>✓ স্বচ্ছতা:</strong> নৈতিক সাংবাদিকতার নীতি মেনে চলা</li>
                                <li class="mb-2"><strong>✓ বৈচিত্র্য:</strong> বিভিন্ন দৃষ্টিভঙ্গি এবং মতামত প্রকাশ করা</li>
                            </ul>
                        </section>

                        <section class="mb-5">
                            <h2 class="section-title mb-3">আমাদের বৈশিষ্ট্য</h2>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="feature-card p-3 border rounded">
                                        <h4 class="mb-2">🔄 লাইভ আপডেট</h4>
                                        <p>রিয়েল-টাইম খবর এবং লাইভ স্ট্রিম সুবিধা</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-card p-3 border rounded">
                                        <h4 class="mb-2">📱 মোবাইল অপটিমাইজড</h4>
                                        <p>সব ডিভাইসে নিরবচ্ছিন্ন অভিজ্ঞতা</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-card p-3 border rounded">
                                        <h4 class="mb-2">🔔 বিজ্ঞপ্তি সেবা</h4>
                                        <p>গুরুত্বপূর্ণ খবর সম্পর্কে তাৎক্ষণিক জানুন</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="feature-card p-3 border rounded">
                                        <h4 class="mb-2">📰 বিস্তৃত কভারেজ</h4>
                                        <p>দেশব্যাপী এবং আন্তর্জাতিক খবর</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="mb-5">
                            <h2 class="section-title mb-3">যোগাযোগ করুন</h2>
                            <p>আমাদের সাথে যোগাযোগ করতে আগ্রহী? আমাদের <a href="{{ route('contact') }}" class="text-primary">যোগাযোগ পৃষ্ঠা</a> দেখুন।</p>
                        </section>
                    @endif
                </article>

                <!-- Related Links -->
                <div class="related-links mt-5 pt-4 border-top">
                    <h3 class="mb-3">অন্যান্য পৃষ্ঠা</h3>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('contact') }}" class="text-decoration-none">যোগাযোগ করুন</a></li>
                        <li><a href="{{ route('privacy') }}" class="text-decoration-none">গোপনীয়তা নীতি</a></li>
                        <li><a href="{{ route('terms') }}" class="text-decoration-none">শর্তাবলী</a></li>
                        <li><a href="{{ route('sitemap') }}" class="text-decoration-none">সাইট ম্যাপ</a></li>
                    </ul>
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

.feature-card {
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.feature-card h4 {
    color: #333;
    font-weight: 600;
}

.about-content p {
    line-height: 1.8;
    color: #555;
    margin-bottom: 1rem;
}

/* Custom About Content Styling */
.custom-about-content {
    font-family: 'Noto Serif Bengali', serif;
    line-height: 1.8;
    color: #333;
}

.custom-about-content h1,
.custom-about-content h2,
.custom-about-content h3,
.custom-about-content h4,
.custom-about-content h5,
.custom-about-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
    color: #333;
}

.custom-about-content h2 {
    border-bottom: 3px solid #007bff;
    padding-bottom: 10px;
}

.custom-about-content p {
    margin-bottom: 1rem;
    line-height: 1.8;
}

.custom-about-content ul,
.custom-about-content ol {
    margin-bottom: 1rem;
    margin-left: 2rem;
}

.custom-about-content li {
    margin-bottom: 0.5rem;
}

.custom-about-content blockquote {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
    margin: 1rem 0;
    color: #666;
    font-style: italic;
}

.custom-about-content img {
    max-width: 100%;
    height: auto;
    margin: 1rem 0;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.custom-about-content video {
    max-width: 100%;
    height: auto;
    margin: 1rem 0;
    border-radius: 4px;
}

.custom-about-content a {
    color: #007bff;
    text-decoration: none;
}

.custom-about-content a:hover {
    text-decoration: underline;
}

.custom-about-content code {
    background: #f0f0f0;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Monaco', 'Courier New', monospace;
}

.custom-about-content pre {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 1rem;
    overflow-x: auto;
}

.custom-about-content pre code {
    background: none;
    padding: 0;
}


.about-content ul li {
    line-height: 1.8;
    color: #555;
}
</style>
@endsection
