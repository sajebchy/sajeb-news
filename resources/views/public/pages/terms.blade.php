@extends('public.layout')

@section('title', $metaTags['title'] ?? 'শর্তাবলী - সজীব নিউজ')
@section('meta_description', $metaTags['description'] ?? 'সজীব নিউজ ব্যবহারের শর্তাবলী পড়ুন।')

@section('content')

<!-- Page Hero -->
<div class="bg-primary text-white py-12 px-gutter">
  <div class="max-w-container-max mx-auto">
    <span class="font-label-caps text-label-caps text-secondary mb-3 block tracking-widest uppercase">আইনি নথি</span>
    <h1 class="font-headline-lg text-4xl mb-3 leading-tight">ব্যবহারের শর্তাবলী</h1>
    <p class="font-meta-data text-meta-data text-white/60">সর্বশেষ আপডেট: জুন ২০২৫</p>
  </div>
</div>

<main class="max-w-container-max mx-auto px-gutter py-section-padding">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-stack-lg">

    <!-- Sidebar Nav -->
    <aside class="lg:col-span-3">
      <div class="sticky top-24 space-y-4">
        <div class="bg-surface-muted border border-subtle p-4">
          <h3 class="font-label-caps text-label-caps text-on-surface-variant mb-3">নথি পরিচালনা</h3>
          <nav class="space-y-1" id="terms-toc">
            <a href="#intro" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">১. ভূমিকা</a>
            <a href="#obligations" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">২. ব্যবহারকারীর দায়িত্ব</a>
            <a href="#ip" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৩. মেধাস্বত্ব</a>
            <a href="#liability" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৪. দায়বদ্ধতার সীমা</a>
            <a href="#law" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৫. প্রযোজ্য আইন</a>
            <a href="#changes" class="block px-3 py-2 font-body-sm text-on-surface-variant border-l-2 border-transparent hover:border-secondary hover:bg-surface-container-low hover:text-primary transition-all">৬. পরিবর্তনসমূহ</a>
          </nav>
        </div>
        <button onclick="window.print()" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-primary text-white font-label-caps text-label-caps hover:bg-news-blue-dark transition-colors">
          <span class="material-symbols-outlined text-[18px]">print</span> প্রিন্ট করুন
        </button>
      </div>
    </aside>

    <!-- Terms Content -->
    <div class="lg:col-span-9">
      <div class="bg-white border border-subtle p-8">

        <section id="intro" class="py-8 border-b border-subtle">
          <h2 class="font-headline-md text-xl text-primary mb-4">১. ভূমিকা</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-3">সজীব নিউজে স্বাগতম। এই শর্তাবলী আমাদের ওয়েবসাইট এবং সেবা ব্যবহারের নিয়মকানুন নির্ধারণ করে। আমাদের সেবা ব্যবহার করে আপনি এই শর্তাবলী মেনে নিচ্ছেন।</p>
          <p class="font-body-main text-on-surface-variant leading-relaxed">আপনি যদি এই শর্তাবলীর কোনো অংশের সাথে একমত না হন, তাহলে অনুগ্রহ করে আমাদের সেবা ব্যবহার করবেন না।</p>
        </section>

        <section id="obligations" class="py-8 border-b border-subtle">
          <h2 class="font-headline-md text-xl text-primary mb-4">২. ব্যবহারকারীর দায়িত্ব</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-4">আমাদের সেবা ব্যবহার করতে গিয়ে আপনি নিম্নলিখিত বিষয়গুলো মেনে চলতে সম্মত হচ্ছেন:</p>
          <ul class="space-y-2 font-body-sm text-on-surface-variant">
            <li class="flex gap-2 items-start"><span class="material-symbols-outlined text-secondary text-[16px] mt-0.5 flex-shrink-0">check_circle</span> কোনো বেআইনি উদ্দেশ্যে সেবা ব্যবহার না করা</li>
            <li class="flex gap-2 items-start"><span class="material-symbols-outlined text-secondary text-[16px] mt-0.5 flex-shrink-0">check_circle</span> অন্য ব্যবহারকারীদের হয়রানি বা ক্ষতি না করা</li>
            <li class="flex gap-2 items-start"><span class="material-symbols-outlined text-secondary text-[16px] mt-0.5 flex-shrink-0">check_circle</span> ভুয়া বা বিভ্রান্তিকর তথ্য প্রচার না করা</li>
            <li class="flex gap-2 items-start"><span class="material-symbols-outlined text-secondary text-[16px] mt-0.5 flex-shrink-0">check_circle</span> মেধাস্বত্ব লঙ্ঘন না করা</li>
            <li class="flex gap-2 items-start"><span class="material-symbols-outlined text-secondary text-[16px] mt-0.5 flex-shrink-0">check_circle</span> আমাদের সিস্টেমে অননুমোদিত প্রবেশের চেষ্টা না করা</li>
            <li class="flex gap-2 items-start"><span class="material-symbols-outlined text-secondary text-[16px] mt-0.5 flex-shrink-0">check_circle</span> স্বয়ংক্রিয় সরঞ্জাম ব্যবহার করে অতিরিক্ত অনুরোধ না পাঠানো</li>
          </ul>
        </section>

        <section id="ip" class="py-8 border-b border-subtle">
          <h2 class="font-headline-md text-xl text-primary mb-4">৩. মেধাস্বত্ব</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-3">সজীব নিউজে প্রকাশিত সকল কন্টেন্ট — সংবাদ, নিবন্ধ, ছবি, ভিডিও, গ্রাফিক্স — আমাদের মেধাস্বত্বের অধীনে সুরক্ষিত।</p>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-3">আপনি ব্যক্তিগত ও অ-বাণিজ্যিক উদ্দেশ্যে আমাদের কন্টেন্ট শেয়ার করতে পারেন, তবে উৎস উল্লেখ করতে হবে।</p>
          <div class="bg-surface-muted border-l-4 border-secondary p-4">
            <p class="font-body-sm text-on-surface-variant">বাণিজ্যিক ব্যবহারের জন্য আমাদের <a href="{{ route('contact') }}" class="text-secondary hover:underline">লিখিত অনুমতি</a> প্রয়োজন।</p>
          </div>
        </section>

        <section id="liability" class="py-8 border-b border-subtle">
          <h2 class="font-headline-md text-xl text-primary mb-4">৪. দায়বদ্ধতার সীমা</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-3">সজীব নিউজ সঠিক ও নির্ভরযোগ্য তথ্য প্রদানে সর্বোচ্চ চেষ্টা করে। তবে আমরা গ্যারান্টি দিতে পারি না যে সকল তথ্য সম্পূর্ণ নির্ভুল বা আপ-টু-ডেট।</p>
          <p class="font-body-main text-on-surface-variant leading-relaxed">আমাদের প্রকাশিত তথ্য ব্যবহার করে নেওয়া সিদ্ধান্তের পরিণতির জন্য সজীব নিউজ দায়বদ্ধ নয়।</p>
        </section>

        <section id="law" class="py-8 border-b border-subtle">
          <h2 class="font-headline-md text-xl text-primary mb-4">৫. প্রযোজ্য আইন</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-3">এই শর্তাবলী বাংলাদেশের আইন অনুযায়ী পরিচালিত হবে। যেকোনো বিরোধ নিষ্পত্তির ক্ষেত্রে ঢাকার আদালতের এখতিয়ার প্রযোজ্য হবে।</p>
          <p class="font-body-main text-on-surface-variant leading-relaxed">ডিজিটাল নিরাপত্তা আইন ২০১৮ এবং তথ্য ও যোগাযোগ প্রযুক্তি আইন ২০০৬ সহ বাংলাদেশের সকল প্রযোজ্য আইন এই শর্তাবলীর অন্তর্ভুক্ত।</p>
        </section>

        <section id="changes" class="py-8">
          <h2 class="font-headline-md text-xl text-primary mb-4">৬. পরিবর্তনসমূহ</h2>
          <p class="font-body-main text-on-surface-variant leading-relaxed mb-3">সজীব নিউজ যেকোনো সময় এই শর্তাবলী পরিবর্তন করার অধিকার সংরক্ষণ করে। পরিবর্তনসমূহ এই পাতায় প্রকাশিত হবে।</p>
          <p class="font-body-main text-on-surface-variant leading-relaxed">কোনো প্রশ্ন থাকলে <a href="{{ route('contact') }}" class="text-secondary hover:underline">আমাদের সাথে যোগাযোগ করুন</a>।</p>
        </section>

      </div>
    </div>
  </div>
</main>

<script>
const termsSections = document.querySelectorAll('section[id]');
const termsLinks = document.querySelectorAll('#terms-toc a');
window.addEventListener('scroll', () => {
  let current = '';
  termsSections.forEach(s => { if (window.scrollY >= s.offsetTop - 140) current = s.id; });
  termsLinks.forEach(a => {
    a.classList.remove('border-secondary','text-primary','bg-surface-container-low');
    if (a.getAttribute('href') === '#' + current) {
      a.classList.add('border-secondary','text-primary','bg-surface-container-low');
    }
  });
});
</script>

@endsection
