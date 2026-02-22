@extends('public.layout')

@section('title', $metaTags['title'] ?? 'শর্তাবলী - Sajeb NEWS')
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
                        <li class="breadcrumb-item active">শর্তাবলী</li>
                    </ol>
                </nav>

                <!-- Page Header -->
                <div class="page-header mb-5 text-center">
                    <h1 class="page-title mb-3">শর্তাবলী ও সেবার শর্তপ্রসঙ্গ</h1>
                    <p class="text-muted small">সর্বশেষ আপডেট: {{ now()->format('d F, Y') }}</p>
                </div>

                <!-- Terms & Conditions Content -->
                <article class="terms-content">
                    <section class="mb-5">
                        <h2 class="section-title mb-3">১. গ্রহণযোগ্যতা শর্তাবলী</h2>
                        <p>এই ওয়েবসাইট ব্যবহার করে, আপনি এই শর্তাবলী মেনে চলতে সম্মত হন। যদি আপনি এই শর্তাবলী মেনে না চলতে পারেন, তবে অনুগ্রহ করে এই সাইট ব্যবহার করবেন না।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">২. ব্যবহারের লাইসেন্স</h2>
                        <p>Sajeb NEWS আপনাকে এই সাইটের সামগ্রী দেখার এবং একটি একক কপি প্রিন্ট করার অনুমতি দেয়, যদি এটি কঠোরভাবে ব্যক্তিগত। আপনি অন্যথায় নিম্নলিখিত কিছুই করতে পারবেন না:</p>
                        <ul>
                            <li>কন্টেন্ট সংশোধন বা অনুলিপি করা</li>
                            <li>বাণিজ্যিক উদ্দেশ্যে সামগ্রী ব্যবহার করা</li>
                            <li>সাইটের গঠন বা উপস্থাপনা অনুলিপি করা</li>
                            <li>সাইট স্ক্র্যাপ বা স্বয়ংক্রিয় প্রেরণার জন্য</li>
                        </ul>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৩. অস্বীকৃতি</h2>
                        <p>এই ওয়েবসাইটের সামগ্রী "যেমন আছে তেমন" প্রদান করা হয়। Sajeb NEWS নিম্নলিখিত বিষয়ের জন্য দায়বদ্ধ নয়:</p>
                        <ul>
                            <li>সামগ্রীর যথার্থতা বা সম্পূর্ণতা</li>
                            <li>কোনো ভুল বা বাদ দেওয়া তথ্য</li>
                            <li>সামগ্রীর অব্যবহার্যতা</li>
                            <li>সিস্টেম বা ডেটার ক্ষতি</li>
                        </ul>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৪. দায়বদ্ধতার সীমাবদ্ধতা</h2>
                        <p>কোনো পরিস্থিতিতেই Sajeb NEWS এর দায়বদ্ধতা ১০০ টাকার চেয়ে বেশি হবে না যা আপনি আমাদের কাছে প্রদান করতে পারেন।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৫. বুদ্ধিবৃত্তিক সম্পত্তি অধিকার</h2>
                        <p>সমস্ত সামগ্রী, ডিজাইন এবং গ্রাফিক্স Sajeb NEWS এর মালিকানাধীন বা লাইসেনসপ্রাপ্ত। কোনো অংশ বৌদ্ধিক সম্পত্তি অনুমতি ছাড়া পুনরুৎপাদন করা যায় না।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৬. ব্যবহারকারীর অবিশ্বাস্য আচরণ</h2>
                        <p>আপনি সম্মত হন যে এই সাইটটি ব্যবহার করার সময় আপনি:</p>
                        <ul>
                            <li>অননুমোদিত প্রবেশাধিকার অনুমান করবেন না</li>
                            <li>অধিকার উল্লঙ্ঘন করবেন না</li>
                            <li>আইনত অসংগত সামগ্রী পোস্ট করবেন না</li>
                            <li>ব্যবহারকারীদের বিরক্ত বা হয়রানি করবেন না</li>
                            <li>সিস্টেম নিরাপত্তা বা কার্যকারিতা বাধাগ্রস্ত করবেন না</li>
                        </ul>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৭. টার্মিনেশন</h2>
                        <p>Sajeb NEWS যেকোনো সময় আপনার অ্যাকাউন্ট বা অ্যাক্সেস বন্ধ করতে পারে যদি আপনি এই শর্তাবলী লঙ্ঘন করেন।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৮. তৃতীয় পক্ষের লিঙ্ক</h2>
                        <p>এই সাইটে তৃতীয় পক্ষের ওয়েবসাইটের লিঙ্ক থাকতে পারে। Sajeb NEWS তাদের বিষয়বস্তু বা নীতির জন্য দায়বদ্ধ নয়।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">৯. পরিবর্তন এবং আপডেট</h2>
                        <p>Sajeb NEWS যেকোনো সময় নোটিস ছাড়াই এই শর্তাবলী পরিবর্তন করার অধিকার রাখে। আমরা এই পৃষ্ঠার শীর্ষে আপডেট তারিখ আপডেট করব।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">১০. প্রাযোজ্য আইন</h2>
                        <p>এই শর্তাবলী বাংলাদেশের আইন দ্বারা পরিচালিত হয়। কোনো বিরোধ এই দেশের আদালতে সমাধান করা হবে।</p>
                    </section>

                    <section class="mb-5">
                        <h2 class="section-title mb-3">১১. যোগাযোগ</h2>
                        <p>এই শর্তাবলী সম্পর্কে প্রশ্ন থাকলে, আমাদের <a href="{{ route('contact') }}">যোগাযোগ পৃষ্ঠা</a> থেকে আমাদের সাথে যোগাযোগ করুন।</p>
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

.terms-content p {
    line-height: 1.8;
    color: #555;
    margin-bottom: 1rem;
}

.terms-content ul {
    padding-left: 20px;
}

.terms-content li {
    line-height: 1.8;
    color: #555;
    margin-bottom: 0.5rem;
}

.terms-content a {
    color: #007bff;
    text-decoration: none;
}

.terms-content a:hover {
    text-decoration: underline;
}
</style>
@endsection
