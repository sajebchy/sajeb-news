@extends('public.layout')

@section('title', $metaTags['title'] ?? 'আমাদের সম্পর্কে - সজীব নিউজ')
@section('meta_description', $metaTags['description'] ?? 'সজীব নিউজ - বাংলাদেশের বিশ্বস্ত সংবাদ প্রতিষ্ঠান।')

@section('content')

<!-- Page Hero -->
<div class="bg-primary text-white py-16 px-gutter">
  <div class="max-w-container-max mx-auto">
    <span class="font-label-caps text-label-caps text-secondary mb-3 block tracking-widest uppercase">আমাদের পরিচয়</span>
    <h1 class="font-headline-lg text-4xl md:text-5xl mb-4 leading-tight">সজীব নিউজ সম্পর্কে</h1>
    <p class="font-body-main text-white/80 max-w-2xl text-lg">বাংলাদেশের প্রতিটি নাগরিকের কাছে সত্য ও নির্ভরযোগ্য সংবাদ পৌঁছে দেওয়াই আমাদের লক্ষ্য।</p>
  </div>
</div>

<main class="max-w-container-max mx-auto px-gutter py-section-padding">

  <!-- Mission & Vision 70/30 Grid -->
  <div class="grid grid-cols-1 md:grid-cols-12 gap-stack-lg mb-section-padding">
    <div class="md:col-span-8 space-y-stack-lg">
      <div>
        <span class="font-label-caps text-label-caps text-secondary mb-2 block tracking-widest uppercase">আমাদের লক্ষ্য</span>
        <h2 class="font-headline-lg text-2xl md:text-3xl mb-stack-md leading-tight">সত্য ও স্বচ্ছতার মাধ্যমে বাংলাদেশের কণ্ঠস্বর হওয়া।</h2>
        <p class="text-on-surface-variant text-lg leading-relaxed font-body-main">
          সজীব নিউজে আমরা বিশ্বাস করি যে তথ্য গণতন্ত্রের ভিত্তি। প্রতিটি নাগরিক যাচাইকৃত ও নিরপেক্ষ তথ্য পাওয়ার অধিকারী — এই নীতির উপর ভিত্তি করে আমরা প্রতিষ্ঠিত হয়েছি। আমাদের লক্ষ্য শুধু সংবাদ পরিবেশনে সীমাবদ্ধ নয়; আমরা একটি সচেতন সমাজ গঠনে অবদান রাখতে চাই।
        </p>
      </div>
      <div class="p-stack-lg bg-surface-muted border-l-4 border-secondary">
        <p class="font-headline-md text-xl italic text-primary">"সত্যের কোনো পক্ষ নেই, শুধু আছে সাক্ষী।"</p>
        <span class="block mt-4 font-meta-data text-meta-data text-on-surface-variant">— সম্পাদকীয় বোর্ড, ২০২৪</span>
      </div>
      <div class="space-y-4">
        <h3 class="font-headline-md text-xl text-primary">আমাদের দর্শন</h3>
        <p class="font-body-main text-on-surface-variant leading-relaxed">আমরা বিশ্বাস করি সংবাদ কেবল ঘটনার বিবরণ নয়, এটি সমাজের দর্পণ। প্রতিটি প্রতিবেদন তৈরিতে আমরা সত্যতা যাচাই, একাধিক সূত্র থেকে তথ্য সংগ্রহ এবং পাঠকের কাছে নিরপেক্ষভাবে উপস্থাপনের নীতি মেনে চলি।</p>
      </div>
    </div>
    <div class="md:col-span-4 bg-surface-container-low p-stack-lg h-fit border border-subtle">
      <h3 class="font-headline-md text-headline-md mb-stack-sm">মূল মূল্যবোধ</h3>
      <ul class="space-y-4">
        <li class="flex items-start gap-3">
          <span class="material-symbols-outlined text-secondary mt-1">verified</span>
          <div>
            <span class="font-bold block font-body-main">সম্পাদকীয় সততা</span>
            <p class="text-body-sm text-on-surface-variant">গতির চেয়ে নির্ভুলতাকে আমরা অগ্রাধিকার দিই।</p>
          </div>
        </li>
        <li class="flex items-start gap-3">
          <span class="material-symbols-outlined text-secondary mt-1">visibility</span>
          <div>
            <span class="font-bold block font-body-main">স্বচ্ছতা</span>
            <p class="text-body-sm text-on-surface-variant">আমরা সূত্র প্রকাশ করি এবং দ্রুত ত্রুটি সংশোধন করি।</p>
          </div>
        </li>
        <li class="flex items-start gap-3">
          <span class="material-symbols-outlined text-secondary mt-1">diversity_3</span>
          <div>
            <span class="font-bold block font-body-main">বৈচিত্র্যের প্রতি শ্রদ্ধা</span>
            <p class="text-body-sm text-on-surface-variant">পক্ষপাতহীনভাবে দেশের বৈচিত্র্যময় কণ্ঠস্বর তুলে ধরি।</p>
          </div>
        </li>
        <li class="flex items-start gap-3">
          <span class="material-symbols-outlined text-secondary mt-1">balance</span>
          <div>
            <span class="font-bold block font-body-main">নিরপেক্ষতা</span>
            <p class="text-body-sm text-on-surface-variant">কোনো দল বা গোষ্ঠীর পক্ষে নয়, শুধু সত্যের পক্ষে।</p>
          </div>
        </li>
      </ul>
    </div>
  </div>

  <!-- History Timeline -->
  <div class="mb-section-padding border-t border-subtle pt-section-padding">
    <h2 class="font-headline-lg text-2xl text-center mb-12">আমাদের যাত্রা</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter">
      <div class="group">
        <div class="h-1 bg-surface-variant mb-6 group-hover:bg-secondary transition-colors duration-300"></div>
        <span class="font-headline-md text-2xl text-secondary block mb-2">১৯৯৪</span>
        <h4 class="font-bold mb-2 font-body-main">যাত্রার শুরু</h4>
        <p class="text-body-sm text-on-surface-variant">সজীব নিউজ ঢাকায় একটি সাপ্তাহিক প্রিন্ট বুলেটিন হিসেবে যাত্রা শুরু করে।</p>
      </div>
      <div class="group">
        <div class="h-1 bg-surface-variant mb-6 group-hover:bg-secondary transition-colors duration-300"></div>
        <span class="font-headline-md text-2xl text-secondary block mb-2">২০০৫</span>
        <h4 class="font-bold mb-2 font-body-main">ডিজিটাল রূপান্তর</h4>
        <p class="text-body-sm text-on-surface-variant">বাংলাদেশে প্রথম সংবাদপত্রগুলোর মধ্যে ২৪/৭ অনলাইন প্ল্যাটফর্মে রূপান্তরিত।</p>
      </div>
      <div class="group">
        <div class="h-1 bg-surface-variant mb-6 group-hover:bg-secondary transition-colors duration-300"></div>
        <span class="font-headline-md text-2xl text-secondary block mb-2">২০১৬</span>
        <h4 class="font-bold mb-2 font-body-main">মোবাইল অ্যাপ</h4>
        <p class="text-body-sm text-on-surface-variant">স্মার্টফোন ব্যবহারকারীদের জন্য iOS ও Android অ্যাপ চালু।</p>
      </div>
      <div class="group">
        <div class="h-1 bg-surface-variant mb-6 group-hover:bg-secondary transition-colors duration-300"></div>
        <span class="font-headline-md text-2xl text-secondary block mb-2">বর্তমান</span>
        <h4 class="font-bold mb-2 font-body-main">শীর্ষস্থানে</h4>
        <p class="text-body-sm text-on-surface-variant">৫০ লক্ষেরও বেশি মাসিক পাঠক সহ বাংলাদেশের শীর্ষস্থানীয় সংবাদ মাধ্যম।</p>
      </div>
    </div>
  </div>

  <!-- Meet The Team -->
  <div class="mb-section-padding border-t border-subtle pt-section-padding">
    <div class="flex items-end justify-between mb-12">
      <div>
        <span class="font-label-caps text-label-caps text-secondary mb-2 block tracking-widest uppercase">নিউজরুম</span>
        <h2 class="font-headline-lg text-2xl">আমাদের প্রধান সম্পাদনা দল</h2>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Editor-in-Chief (2 cols) -->
      <div class="md:col-span-2 relative h-[380px] overflow-hidden group bg-surface-container-high rounded">
        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
             src="https://picsum.photos/seed/editor1/800/400" alt="প্রধান সম্পাদক"/>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-stack-lg">
          <span class="text-white/70 font-label-caps text-label-caps mb-2">সম্পাদক-ইন-চিফ</span>
          <h3 class="text-white font-headline-lg text-xl">মোহাম্মদ জামিল আহমেদ</h3>
          <p class="text-white/80 text-body-sm max-w-md">৩০ বছরের অনুসন্ধানমূলক সাংবাদিকতার অভিজ্ঞতা।</p>
        </div>
      </div>
      <!-- Managing Editor -->
      <div class="relative h-[380px] overflow-hidden group bg-surface-container-high rounded">
        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
             src="https://picsum.photos/seed/editor2/400/400" alt="ব্যবস্থাপনা সম্পাদক"/>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-stack-lg">
          <span class="text-white/70 font-label-caps text-label-caps mb-2">ব্যবস্থাপনা সম্পাদক</span>
          <h3 class="text-white font-headline-md text-lg">নাসরিন আক্তার</h3>
        </div>
      </div>
      <!-- Senior Reporters -->
      @foreach([['রফিকুল ইসলাম','রাজনৈতিক সাংবাদিক',3],['সুমাইয়া খানম','প্রযুক্তি সম্পাদক',4],['তানভীর হোসেন','ক্রীড়া সাংবাদিক',5]] as [$name,$role,$seed])
      <div class="relative h-[260px] overflow-hidden group bg-surface-container-high rounded">
        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
             src="https://picsum.photos/seed/reporter{{ $seed }}/400/260" alt="{{ $name }}"/>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-6">
          <span class="text-white/70 font-label-caps text-label-caps mb-1">{{ $role }}</span>
          <h4 class="text-white font-bold">{{ $name }}</h4>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- Transparency CTA -->
  <section class="bg-primary text-white p-section-padding text-center relative overflow-hidden rounded">
    <div class="absolute top-0 right-0 p-12 opacity-10 pointer-events-none">
      <span class="material-symbols-outlined text-[200px]">policy</span>
    </div>
    <div class="relative z-10 max-w-2xl mx-auto">
      <h2 class="font-headline-lg text-2xl mb-6">স্বচ্ছতার প্রতি আমাদের অঙ্গীকার</h2>
      <p class="font-body-main text-lg mb-8 text-white/80">প্রতি বছর আমরা আমাদের অর্থায়ন, সম্পাদকীয় পরিবর্তন ও ত্রুটি সংশোধনের সম্পূর্ণ প্রতিবেদন প্রকাশ করি।</p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('contact') }}" class="bg-secondary text-white px-8 py-4 font-label-caps text-label-caps rounded hover:opacity-90 transition-opacity">যোগাযোগ করুন</a>
        <a href="{{ route('privacy') }}" class="border border-white/30 text-white px-8 py-4 font-label-caps text-label-caps rounded hover:bg-white/10 transition-colors">আমাদের নীতিমালা</a>
      </div>
    </div>
  </section>

</main>

@endsection
