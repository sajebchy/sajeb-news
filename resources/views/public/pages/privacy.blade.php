@extends('public.layout')

@section('title', $metaTags['title'] ?? 'গোপনীয়তা নীতি - Sajeb NEWS')
@section('description', $metaTags['description'] ?? '')
@section('keywords', $metaTags['keywords'] ?? '')
@section('canonical', $metaTags['canonical'] ?? '')
@section('og_title', $metaTags['og_title'] ?? '')
@section('og_description', $metaTags['og_description'] ?? '')
@section('og_url', $metaTags['og_url'] ?? '')

@section('content')
<main class="main-content py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">হোম</a></li>
                        <li class="breadcrumb-item active">গোপনীয়তা নীতি</li>
                    </ol>
                </nav>

                <!-- Page Header -->
                <div class="page-header mb-5 text-center">
                    <h1 class="page-title mb-3">গোপনীয়তা নীতি</h1>
                    <p class="text-muted small">সর্বশেষ আপডেট: {{ now()->format('d F, Y') }}</p>
                </div>

                <!-- Privacy Policy Content -->
                <article class="policy-content">
                    <section class="mb-5">
                        <h2 class="section-title mb-3">১. ভূমিকা</h2>
                        <p>Sajeb NEWS আপনার ব্যক্তিগত তথ্যের গোপনীয়তা এবং সুরক্ষার প্রতি প্রতিশ্রুতিবদ্ধ। এই নীতি ব্যাখ্যা করে যে আমরা কীভাবে আপনার তথ্য সংগ্রহ, ব্যবহার এবং সুরক্ষিত করি।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">২. আমরা কী তথ্য সংগ্রহ করি?</h2>
                        <p>আমরা নিম্নলিখিত তথ্য সংগ্রহ করতে পারি:</p>
                        <ul>
                            <li><strong>ব্যক্তিগত তথ্য:</strong> নাম, ইমেইল, ফোন নম্বর (যখন আপনি যোগাযোগ ফর্ম পূরণ করেন)</li>
                            <li><strong>ব্যবহারকারীর ডেটা:</strong> IP ঠিকানা, ব্রাউজার ধরন, দেখা পৃষ্ঠাগুলি</li>
                            <li><strong>কুকি এবং ট্র্যাকিং:</strong> আপনার পছন্দ এবং অভিজ্ঞতা উন্নত করতে</li>
                            <li><strong>বিশ্লেষণ ডেটা:</strong> Google Analytics এর মাধ্যমে আপনার দর্শন আচরণ</li>
                        </ul>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৩. তথ্য ব্যবহার</h2>
                        <p>আমরা সংগৃহীত তথ্য নিম্নলিখিত উদ্দেশ্যে ব্যবহার করি:</p>
                        <ul>
                            <li>যোগাযোগের প্রতিক্রিয়া প্রদান করতে</li>
                            <li>আমাদের সেবা উন্নত করতে</li>
                            <li>আপনাকে খবর এবং আপডেট পাঠাতে (আপনার অনুমতি সাপেক্ষে)</li>
                            <li>আইনি বাধ্যবাধকতা মেনে চলতে</li>
                            <li>জালিয়াতি প্রতিরোধ এবং নিরাপত্তা নিশ্চিত করতে</li>
                        </ul>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৪. ডেটা সুরক্ষা</h2>
                        <p>আমরা আপনার ব্যক্তিগত তথ্য সুরক্ষিত করতে নিম্নলিখিত ব্যবস্থা গ্রহণ করি:</p>
                        <ul>
                            <li>এনক্রিপশন প্রযুক্তি ব্যবহার (SSL/TLS)</li>
                            <li>নিরাপদ সার্ভার এবং ডেটাবেস</li>
                            <li>নিয়মিত নিরাপত্তা অডিট</li>
                            <li>সীমিত প্রবেশাধিকার নীতি</li>
                            <li>আধুনিক ফায়ারওয়াল প্রযুক্তি</li>
                        </ul>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৫. তৃতীয় পক্ষ শেয়ারিং</h2>
                        <p>আমরা আপনার ব্যক্তিগত তথ্য তৃতীয় পক্ষের সাথে শেয়ার করি না, যদি না:</p>
                        <ul>
                            <li>এটি আইন দ্বারা প্রয়োজন হয়</li>
                            <li>আপনার স্পষ্ট সম্মতি রয়েছে</li>
                            <li>এটি আমাদের সেবা প্রদানের জন্য প্রয়োজনীয় (যেমন, পেমেন্ট প্রসেসর)</li>
                        </ul>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৬. কুকি নীতি</h2>
                        <p>আমরা কুকি ব্যবহার করি আপনার অভিজ্ঞতা উন্নত করতে। আপনি আপনার ব্রাউজার সেটিংসে কুকি অক্ষম করতে পারেন।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৭. আপনার অধিকার</h2>
                        <p>আপনার নিম্নলিখিত অধিকার রয়েছে:</p>
                        <ul>
                            <li>আপনার তথ্য অ্যাক্সেস করার অধিকার</li>
                            <li>তথ্য সংশোধন বা আপডেটের অধিকার</li>
                            <li>তথ্য মুছে ফেলার অধিকার</li>
                            <li>আমাদের সাথে যোগাযোগ করে এই অধিকারগুলি অনুশীলন করতে পারেন</li>
                        </ul>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৮. শিশুদের গোপনীয়তা</h2>
                        <p>আমাদের সেবা ১৮ বছরের নিচের শিশুদের জন্য ডিজাইন করা হয়নি। আমরা জেনেশুনে ১৮ বছরের নিচে শিশুদের তথ্য সংগ্রহ করি না।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৯. তৃতীয় পক্ষের লিঙ্ক</h2>
                        <p>আমাদের সাইটে তৃতীয় পক্ষের ওয়েবসাইটের লিঙ্ক থাকতে পারে। আমরা তাদের গোপনীয়তা নীতির জন্য দায়বদ্ধ নই। আপনার প্রবেশের আগে তাদের নীতি পড়ুন।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">১০. নীতি পরিবর্তন</h2>
                        <p>আমরা যেকোনো সময় এই গোপনীয়তা নীতি আপডেট করতে পারি। এই পৃষ্ঠায় আপডেট তারিখ পরীক্ষা করুন।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">১১. যোগাযোগ</h2>
                        <p>এই গোপনীয়তা নীতি সম্পর্কে প্রশ্ন থাকলে, আমাদের <a href="{{ route('contact') }}">যোগাযোগ পৃষ্ঠা</a> থেকে আমাদের সাথে যোগাযোগ করুন।</p>
                    </section>
                </article>
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
    font-size: 1.3rem;
    font-weight: 600;
    color: #333;
    border-left: 4px solid #007bff;
    padding-left: 15px;
    margin-left: -15px;
}

.policy-content p {
    line-height: 1.8;
    color: #555;
    margin-bottom: 1rem;
}

.policy-content ul {
    padding-left: 20px;
}

.policy-content li {
    line-height: 1.8;
    color: #555;
    margin-bottom: 0.5rem;
}

.policy-content a {
    color: #007bff;
    text-decoration: none;
}

.policy-content a:hover {
    text-decoration: underline;
}
</style>
@endsection
