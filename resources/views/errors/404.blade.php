@extends('public.layout')

@section('title', '৪০৪ - পৃষ্ঠা পাওয়া যায়নি | সজীব নিউজ')
@section('meta_description', 'দুঃখিত, আপনি যে পৃষ্ঠাটি খুঁজছেন তা পাওয়া যায়নি।')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center text-center px-gutter py-section-padding relative overflow-hidden">
  <!-- Giant 404 background number -->
  <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none">
    <span class="font-headline-lg text-primary opacity-[0.04]" style="font-size:clamp(180px,25vw,300px);line-height:1;font-weight:900;">404</span>
  </div>

  <div class="relative z-10 max-w-xl mx-auto">
    <span class="font-label-caps text-label-caps text-secondary mb-4 block tracking-widest uppercase">ত্রুটি ৪০৪</span>
    <h1 class="font-headline-lg text-3xl md:text-4xl text-secondary mb-4 leading-tight">পৃষ্ঠাটি খুঁজে পাওয়া যায়নি</h1>
    <p class="font-body-main text-on-surface-variant text-lg leading-relaxed mb-8">দুঃখিত, আপনি যে পৃষ্ঠাটি খুঁজছেন সেটি সরানো হয়েছে, নামকরণ করা হয়েছে, বা সাময়িকভাবে অনুপলব্ধ।</p>

    <!-- Search -->
    <form action="{{ route('news.search') }}" method="GET" class="flex gap-3 max-w-md mx-auto mb-8">
      <div class="relative flex-1">
        <input type="text" name="q" placeholder="সংবাদ অনুসন্ধান করুন..."
               class="w-full pl-4 pr-10 py-3 border border-subtle focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-sm bg-white rounded"/>
        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant material-symbols-outlined text-[20px]">search</span>
      </div>
      <button type="submit" class="bg-primary text-white px-6 py-3 font-label-caps text-label-caps hover:bg-news-blue-dark transition-colors rounded">খুঁজুন</button>
    </form>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 justify-center mb-10">
      <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white font-label-caps text-label-caps rounded hover:bg-news-blue-dark transition-colors">
        <span class="material-symbols-outlined text-[18px]">home</span> প্রচ্ছদে ফিরুন
      </a>
      <a href="{{ route('news.search') }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-transparent text-primary border border-subtle font-label-caps text-label-caps rounded hover:bg-surface-container-low transition-colors">
        <span class="material-symbols-outlined text-[18px]">archive</span> আর্কাইভ দেখুন
      </a>
    </div>

    <!-- Category chips -->
    <div>
      <p class="font-label-caps text-label-caps text-on-surface-variant mb-3">বিভাগ দেখুন:</p>
      <div class="flex flex-wrap gap-2 justify-center">
        @foreach(\App\Models\Category::where('is_active', true)->orderBy('featured_order')->limit(8)->get() as $cat)
        <a href="{{ route('category.show', $cat->slug) }}"
           class="inline-block px-4 py-2 bg-surface-container-low rounded-full font-body-sm text-on-surface-variant hover:bg-secondary hover:text-white hover:border-secondary border border-subtle transition-all">
          {{ $cat->name }}
        </a>
        @endforeach
      </div>
    </div>
  </div>
</div>

@endsection
