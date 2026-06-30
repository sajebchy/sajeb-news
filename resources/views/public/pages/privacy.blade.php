@extends('public.layout')

@section('title', $metaTags['title'] ?? 'গোপনীয়তা নীতি - সজীব নিউজ')
@section('meta_description', $metaTags['description'] ?? 'সজীব নিউজের গোপনীয়তা নীতি পড়ুন।')

@section('content')

<!-- Page Hero -->
<div class="bg-primary text-white py-12 px-gutter">
  <div class="max-w-container-max mx-auto">
    <span class="font-label-caps text-label-caps text-secondary mb-3 block tracking-widest uppercase">নীতিমালা</span>
    <h1 class="font-headline-lg text-4xl mb-3 leading-tight">গোপনীয়তা নীতি</h1>
    <p class="font-meta-data text-meta-data text-white/60">সর্বশেষ আপডেট: জুন ২০২৫</p>
  </div>
</div>

<main class="max-w-container-max mx-auto px-gutter py-section-padding">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-stack-lg">

    <!-- Sidebar Nav -->
    <aside class="lg:col-span-3">
      <div class="sticky top-24 space-y-4">
        <div class="bg-surface-muted border border-subtle p-4">
          <h3 class="font-label-caps text-label-caps text-on-surface-variant mb-3">বিষয়সূচি</h3>
          <nav class="space-y-1" id="policy-toc">
            <a href="#intro" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">১. ভূমিকা</a>
            <a href="#collection" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">২. তথ্য সংগ্রহ</a>
            <a href="#usage" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৩. তথ্যের ব্যবহার</a>
            <a href="#cookies" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৪. কুকিজ পলিসি</a>
            <a href="#rights" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৫. আপনার অধিকার</a>
            <a href="#contact-us" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৬. যোগাযোগ</a>
          </nav>
        </div>
        <button onclick="window.print()" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-primary text-white font-label-caps text-label-caps hover:bg-news-blue-dark transition-colors">
          <span class="material-symbols-outlined text-[18px]">print</span> প্রিন্ট করুন
        </button>
      </div>
    </aside>

    <!-- Policy Content -->
    <div class="lg:col-span-9">
      <div class="bg-white border border-subtle p-8 space-y-0">

        <section id="intro" class="py-8 border-b border-subtle">
          <h2 class="font-headline-md text-xl text-primary mb-4">১. ভূমিকা</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-3">সজীব নিউজ আপনার গোপনীয়তাকে সম্মান করে। এই গোপনীয়তা নীতি ব্যাখ্যা করে যে আমরা আপনার ব্যক্তিগত তথ্য কীভাবে সংগ্রহ করি, ব্যবহার করি এবং সুরক্ষিত রাখি।</p>
          <p class="font-body-main text-on-surface-variant leading-relaxed">আমাদের ওয়েবসাইট ব্যবহার করে আপনি এই নীতির শর্তাবলীতে সম্মতি দিচ্ছেন।</p>
        </section>

        <section id="collection" class="py-8 border-b border-subtle">
          <h2 class="font-headline-md text-xl text-primary mb-4">২. তথ্য সংগ্রহ</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-4">আমরা নিম্নলিখিত ধরনের তথ্য সংগ্রহ করি:</p>
          <div class="bg-surface-muted border-l-4 border-secondary p-5">
            <ul class="space-y-3 font-body-sm text-on-surface-variant">
              <li class="flex gap-2"><span class="material-symbols-outlined text-secondary text-[18px] flex-shrink-0 mt-0.5">person</span><span><strong class="text-on-surface">ব্যক্তিগত তথ্য:</strong> নাম, ইমেইল (নিউজলেটার সাবস্ক্রিপশন বা যোগাযোগ ফর্মের সময়)</span></li>
              <li class="flex gap-2"><span class="material-symbols-outlined text-secondary text-[18px] flex-shrink-0 mt-0.5">analytics</span><span><strong class="text-on-surface">ব্যবহার তথ্য:</strong> পাতা ভিজিট, সময়, ক্লিক করা লিংক</span></li>
              <li class="flex gap-2"><span class="material-symbols-outlined text-secondary text-[18px] flex-shrink-0 mt-0.5">devices</span><span><strong class="text-on-surface">ডিভাইস তথ্য:</strong> ব্রাউজার ধরন, অপারেটিং সিস্টেম, IP ঠিকানা</span></li>
              <li class="flex gap-2"><span class="material-symbols-outlined text-secondary text-[18px] flex-shrink-0 mt-0.5">cookie</span><span><strong class="text-on-surface">কুকিজ:</strong> ওয়েবসাইটের কার্যকারিতা উন্নয়নের জন্য</span></li>
            </ul>
          </div>
        </section>

        <section id="usage" class="py-8 border-b border-subtle">
          <h2 class="font-headline-md text-xl text-primary mb-4">৩. তথ্যের ব্যবহার</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-4">আমরা আপনার তথ্য নিম্নলিখিত উদ্দেশ্যে ব্যবহার করি:</p>
          <ul class="space-y-2 font-body-sm text-on-surface-variant pl-4">
            <li class="flex gap-2 items-start"><span class="material-symbols-outlined text-secondary text-[16px] mt-0.5 flex-shrink-0">check_circle</span> আমাদের সেবা উন্নত করতে এবং ব্যক্তিগতকৃত অভিজ্ঞতা প্রদান করতে</li>
            <li class="flex gap-2 items-start"><span class="material-symbols-outlined text-secondary text-[16px] mt-0.5 flex-shrink-0">check_circle</span> নিউজলেটার এবং আপডেট পাঠাতে (শুধুমাত্র সম্মতি দিলে)</li>
            <li class="flex gap-2 items-start"><span class="material-symbols-outlined text-secondary text-[16px] mt-0.5 flex-shrink-0">check_circle</span> প্রযুক্তিগত সমস্যা সমাধান করতে</li>
            <li class="flex gap-2 items-start"><span class="material-symbols-outlined text-secondary text-[16px] mt-0.5 flex-shrink-0">check_circle</span> আইনি বাধ্যবাধকতা পালন করতে</li>
          </ul>
          <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded flex gap-3 items-start">
            <span class="material-symbols-outlined text-green-600 flex-shrink-0">security</span>
            <p class="font-body-sm text-green-800">আমরা কখনই আপনার ব্যক্তিগত তথ্য তৃতীয় পক্ষের কাছে বিক্রি করি না।</p>
          </div>
        </section>

        <section id="cookies" class="py-8 border-b border-subtle">
          <h2 class="font-headline-md text-xl text-primary mb-4">৪. কুকিজ পলিসি</h2>
          <div class="bg-amber-50 border border-amber-200 p-4 rounded flex gap-3 mb-4">
            <span class="material-symbols-outlined text-amber-600 flex-shrink-0">cookie</span>
            <div>
              <p class="font-body-sm text-amber-800 mb-2">আমরা কুকিজ ব্যবহার করি আপনার ব্রাউজিং অভিজ্ঞতা উন্নত করতে।</p>
              <p class="font-body-sm text-amber-800">আপনার ব্রাউজার সেটিং থেকে যেকোনো সময় কুকিজ নিষ্ক্রিয় করতে পারেন।</p>
            </div>
          </div>
          <p class="font-body-sm text-on-surface-variant">আমরা তিন ধরনের কুকিজ ব্যবহার করি: প্রয়োজনীয় কুকিজ, কার্যকারিতা কুকিজ এবং বিশ্লেষণ কুকিজ।</p>
        </section>

        <section id="rights" class="py-8 border-b border-subtle">
          <h2 class="font-headline-md text-xl text-primary mb-4">৫. আপনার অধিকার</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-4">আপনার নিম্নলিখিত অধিকার রয়েছে:</p>
          <ul class="space-y-3 font-body-sm text-on-surface-variant">
            <li class="flex gap-3 items-start"><span class="material-symbols-outlined text-secondary text-[18px] flex-shrink-0">lock_open</span><span><strong class="text-on-surface">অ্যাক্সেসের অধিকার:</strong> আমরা আপনার সম্পর্কে কী তথ্য রাখি তা জানার অধিকার</span></li>
            <li class="flex gap-3 items-start"><span class="material-symbols-outlined text-secondary text-[18px] flex-shrink-0">edit</span><span><strong class="text-on-surface">সংশোধনের অধিকার:</strong> ভুল তথ্য সংশোধন করার অধিকার</span></li>
            <li class="flex gap-3 items-start"><span class="material-symbols-outlined text-secondary text-[18px] flex-shrink-0">delete</span><span><strong class="text-on-surface">মুছে ফেলার অধিকার:</strong> আপনার ব্যক্তিগত তথ্য মুছে ফেলার অনুরোধ</span></li>
            <li class="flex gap-3 items-start"><span class="material-symbols-outlined text-secondary text-[18px] flex-shrink-0">cancel</span><span><strong class="text-on-surface">সম্মতি প্রত্যাহারের অধিকার:</strong> যেকোনো সময় সম্মতি প্রত্যাহার</span></li>
          </ul>
        </section>

        <section id="contact-us" class="py-8">
          <h2 class="font-headline-md text-xl text-primary mb-4">৬. যোগাযোগ</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-3">গোপনীয়তা সংক্রান্ত যেকোনো প্রশ্নের জন্য:</p>
          <div class="bg-surface-container-low p-4 space-y-2 font-body-sm text-on-surface-variant">
            <div class="flex gap-2"><span class="material-symbols-outlined text-[18px] text-secondary">mail</span> <a href="mailto:privacy@sajebnews.com" class="text-secondary hover:underline">privacy@sajebnews.com</a></div>
            <div class="flex gap-2"><span class="material-symbols-outlined text-[18px] text-secondary">location_on</span> <span>১২২/এ মতিঝিল বাণিজ্যিক এলাকা, ঢাকা ১০০০</span></div>
          </div>
          <p class="mt-4 font-body-sm text-on-surface-variant">অথবা আমাদের <a href="{{ route('contact') }}" class="text-secondary hover:underline">যোগাযোগ ফর্ম</a> ব্যবহার করুন।</p>
        </section>

      </div>
    </div>
  </div>
</main>

<script>
// Scroll spy for sidebar nav
const policySections = document.querySelectorAll('section[id]');
const policyLinks = document.querySelectorAll('#policy-toc a');
window.addEventListener('scroll', () => {
  let current = '';
  policySections.forEach(s => { if (window.scrollY >= s.offsetTop - 140) current = s.id; });
  policyLinks.forEach(a => {
    a.classList.remove('border-secondary','text-primary','bg-surface-container-low');
    if (a.getAttribute('href') === '#' + current) {
      a.classList.add('border-secondary','text-primary','bg-surface-container-low');
    }
  });
});
</script>

@endsection
