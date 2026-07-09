@extends('public.layout')

@section('title', 'সম্পাদকীয় নীতিমালা - ' . ($seoSettings?->site_name ?: 'সজীব নিউজ'))
@section('meta_description', 'সজীব নিউজের সম্পাদকীয় নীতিমালা — তথ্যের যাচাই, সংশোধন, এবং নৈতিকতা সম্পর্কিত আমাদের নীতি।')
@section('canonical', route('editorial-policy'))

@push('scripts')
@php
  $__breadSch = app(\App\Services\SeoService::class)->getBreadcrumbSchema(['সম্পাদকীয় নীতিমালা' => route('editorial-policy')]);
@endphp
<script type="application/ld+json">{!! json_encode($__breadSch, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

<div class="bg-primary text-white py-12 px-gutter">
  <div class="max-w-container-max mx-auto">
    <span class="font-label-caps text-label-caps text-secondary mb-3 block tracking-widest uppercase">নীতিমালা</span>
    <h1 class="font-headline-lg text-4xl mb-3 leading-tight">সম্পাদকীয় নীতিমালা</h1>
    <p class="font-meta-data text-meta-data text-white/60">সর্বশেষ আপডেট: জুলাই ২০২৬</p>
  </div>
</div>

<main class="max-w-container-max mx-auto px-gutter py-section-padding">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-stack-lg">

    <aside class="lg:col-span-3">
      <div class="sticky top-24 space-y-4">
        <div class="bg-surface-muted border border-subtle p-4">
          <h3 class="font-label-caps text-label-caps text-on-surface-variant mb-3">বিষয়সূচি</h3>
          <nav class="space-y-1">
            <a href="#mission" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">১. আমাদের লক্ষ্য</a>
            <a href="#accuracy" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">২. তথ্যের যথার্থতা</a>
            <a href="#sources" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৩. সূত্র ও যাচাই</a>
            <a href="#corrections" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৪. সংশোধন নীতি</a>
            <a href="#independence" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৫. সম্পাদকীয় স্বাধীনতা</a>
            <a href="#ethics" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৬. নৈতিক মানদণ্ড</a>
            <a href="#factcheck" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৭. ফ্যাক্ট-চেক পদ্ধতি</a>
            <a href="#contact" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৮. যোগাযোগ</a>
          </nav>
        </div>
      </div>
    </aside>

    <div class="lg:col-span-9">
      <div class="prose prose-lg max-w-none space-y-8">

        <section id="mission">
          <h2 class="font-headline-md text-headline-md text-primary border-l-4 border-secondary pl-4 mb-4">১. আমাদের লক্ষ্য</h2>
          <p class="text-on-surface-variant leading-relaxed mb-4">{{ $seoSettings?->site_name ?: 'সজীব নিউজ' }} একটি স্বাধীন অনলাইন সংবাদ পোর্টাল। আমাদের লক্ষ্য হলো বাংলাদেশের জনগণের কাছে নির্ভরযোগ্য, নিরপেক্ষ এবং সময়োপযোগী সংবাদ পৌঁছে দেওয়া। আমরা সত্য ও বস্তুনিষ্ঠ সাংবাদিকতায় বিশ্বাসী।</p>
        </section>

        <section id="accuracy">
          <h2 class="font-headline-md text-headline-md text-primary border-l-4 border-secondary pl-4 mb-4">২. তথ্যের যথার্থতা</h2>
          <p class="text-on-surface-variant leading-relaxed mb-4">আমরা প্রতিটি সংবাদ প্রকাশের পূর্বে তথ্যের সঠিকতা যাচাই করি। আমাদের নীতিমালা অনুযায়ী:</p>
          <ul class="list-disc list-inside text-on-surface-variant space-y-2 ml-4">
            <li>প্রতিটি সংবাদে ন্যূনতম দুটি স্বতন্ত্র সূত্র থেকে তথ্য যাচাই করা হয়</li>
            <li>পরিসংখ্যান ও তথ্য সরকারি বা নির্ভরযোগ্য প্রাতিষ্ঠানিক সূত্র থেকে সংগ্রহ করা হয়</li>
            <li>উদ্ধৃতি সরাসরি সংশ্লিষ্ট ব্যক্তি বা তাদের অনুমোদিত প্রতিনিধি থেকে নেওয়া হয়</li>
          </ul>
        </section>

        <section id="sources">
          <h2 class="font-headline-md text-headline-md text-primary border-l-4 border-secondary pl-4 mb-4">৩. সূত্র ও যাচাই</h2>
          <p class="text-on-surface-variant leading-relaxed mb-4">আমরা সংবাদের সূত্র সম্পর্কে স্বচ্ছ থাকতে প্রতিশ্রুতিবদ্ধ। আমাদের সাংবাদিকরা তথ্যের উৎস স্পষ্টভাবে উল্লেখ করেন। বেনামী সূত্র শুধুমাত্র সেই ক্ষেত্রে ব্যবহৃত হয় যেখানে সূত্রের নিরাপত্তা ঝুঁকিতে থাকে এবং তথ্যটি জনস্বার্থে গুরুত্বপূর্ণ।</p>
        </section>

        <section id="corrections">
          <h2 class="font-headline-md text-headline-md text-primary border-l-4 border-secondary pl-4 mb-4">৪. সংশোধন নীতি</h2>
          <p class="text-on-surface-variant leading-relaxed mb-4">ভুল তথ্য প্রকাশিত হলে আমরা দ্রুত সংশোধন করি:</p>
          <ul class="list-disc list-inside text-on-surface-variant space-y-2 ml-4">
            <li>ছোট ভুল (বানান, তারিখ) সরাসরি সংশোধন করা হয় এবং "আপডেটেড" চিহ্ন যুক্ত করা হয়</li>
            <li>বড় তথ্যগত ভুল হলে সংশোধন নোট সহ প্রকাশ করা হয়</li>
            <li>পাঠক আমাদের যোগাযোগ পৃষ্ঠার মাধ্যমে ভুল রিপোর্ট করতে পারেন</li>
          </ul>
        </section>

        <section id="independence">
          <h2 class="font-headline-md text-headline-md text-primary border-l-4 border-secondary pl-4 mb-4">৫. সম্পাদকীয় স্বাধীনতা</h2>
          <p class="text-on-surface-variant leading-relaxed mb-4">আমাদের সম্পাদকীয় সিদ্ধান্ত কোনো রাজনৈতিক দল, ব্যবসায়িক প্রতিষ্ঠান বা বিজ্ঞাপনদাতার দ্বারা প্রভাবিত হয় না। বিজ্ঞাপন এবং সম্পাদকীয় বিষয়বস্তু সম্পূর্ণ পৃথক রাখা হয়। স্পন্সরড বা প্রচারমূলক বিষয়বস্তু স্পষ্টভাবে চিহ্নিত করা হয়।</p>
        </section>

        <section id="ethics">
          <h2 class="font-headline-md text-headline-md text-primary border-l-4 border-secondary pl-4 mb-4">৬. নৈতিক মানদণ্ড</h2>
          <p class="text-on-surface-variant leading-relaxed mb-4">আমাদের সকল সাংবাদিক ও কর্মী নিম্নলিখিত নৈতিক মানদণ্ড মেনে চলেন:</p>
          <ul class="list-disc list-inside text-on-surface-variant space-y-2 ml-4">
            <li>ব্যক্তিগত গোপনীয়তার প্রতি সম্মান প্রদর্শন</li>
            <li>শিশু, সংখ্যালঘু ও দুর্বল জনগোষ্ঠীর সুরক্ষা</li>
            <li>ঘৃণা, সহিংসতা বা বৈষম্যমূলক বিষয়বস্তু প্রকাশ না করা</li>
            <li>স্বার্থের দ্বন্দ্ব (conflict of interest) প্রকাশ করা</li>
          </ul>
        </section>

        <section id="factcheck">
          <h2 class="font-headline-md text-headline-md text-primary border-l-4 border-secondary pl-4 mb-4">৭. ফ্যাক্ট-চেক পদ্ধতি</h2>
          <p class="text-on-surface-variant leading-relaxed mb-4">{{ $seoSettings?->site_name ?: 'সজীব নিউজ' }} ফ্যাক্ট-চেক প্রতিবেদন প্রকাশ করে। আমাদের ফ্যাক্ট-চেক পদ্ধতি:</p>
          <ul class="list-disc list-inside text-on-surface-variant space-y-2 ml-4">
            <li>দাবি চিহ্নিত করা এবং মূল উৎস খুঁজে বের করা</li>
            <li>একাধিক নির্ভরযোগ্য সূত্রের সাথে মিলিয়ে দেখা</li>
            <li>ClaimReview স্ট্রাকচার্ড ডেটা ব্যবহার করে ফলাফল প্রকাশ</li>
            <li>রেটিং: সত্য, অধিকাংশ সত্য, মিশ্র, অধিকাংশ মিথ্যা, মিথ্যা</li>
          </ul>
        </section>

        <section id="contact">
          <h2 class="font-headline-md text-headline-md text-primary border-l-4 border-secondary pl-4 mb-4">৮. যোগাযোগ</h2>
          <p class="text-on-surface-variant leading-relaxed mb-4">সম্পাদকীয় বিষয়ে মতামত, সংশোধনের অনুরোধ বা অভিযোগ জানাতে আমাদের <a href="{{ route('contact') }}" class="text-secondary hover:underline">যোগাযোগ পৃষ্ঠায়</a> যান।</p>
          @if($seoSettings?->office_email)
          <p class="text-on-surface-variant">ইমেইল: <a href="mailto:{{ $seoSettings->office_email }}" class="text-secondary hover:underline">{{ $seoSettings->office_email }}</a></p>
          @endif
        </section>

      </div>
    </div>
  </div>
</main>

@endsection
