@extends('public.layout')

@section('title', $author->name . ' - লেখক প্রোফাইল | সজীব নিউজ')
@section('meta_description', $author->bio ?? $author->name . ' এর প্রকাশিত সব সংবাদ পড়ুন সজীব নিউজে।')

@section('content')

<!-- Author Hero -->
<div class="bg-surface-container-low border-b border-subtle py-10 px-gutter">
  <div class="max-w-container-max mx-auto">
    <div class="flex flex-col md:flex-row gap-6 items-start">
      <!-- Avatar -->
      @php $avatarPath = $author->profile_photo_path ?? $author->avatar ?? null; @endphp
      <div class="w-36 h-36 rounded-lg overflow-hidden flex-shrink-0 bg-surface-container-high border border-subtle">
        @if($avatarPath)
        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $avatarPath) }}" alt="{{ $author->name }}"/>
        @else
        <div class="w-full h-full flex items-center justify-center bg-primary-container">
          <span class="material-symbols-outlined text-[60px] text-on-primary-container/40">person</span>
        </div>
        @endif
      </div>

      <!-- Info -->
      <div class="flex-1">
        <span class="font-label-caps text-label-caps text-secondary block mb-1 tracking-widest uppercase">{{ $author->role ?? 'সাংবাদিক' }}</span>
        <h1 class="font-headline-lg text-3xl md:text-4xl text-primary mb-3 leading-tight">{{ $author->name }}</h1>
        @if($author->bio ?? null)
        <p class="font-body-main text-on-surface-variant leading-relaxed max-w-2xl mb-4">{{ $author->bio }}</p>
        @else
        <p class="font-body-main text-on-surface-variant leading-relaxed max-w-2xl mb-4">{{ $author->name }} সজীব নিউজের একজন লেখক।</p>
        @endif

        <!-- Social Links -->
        <div class="flex gap-2">
          @if($author->facebook ?? null)
          <a href="{{ $author->facebook }}" target="_blank" rel="noopener"
             class="w-9 h-9 flex items-center justify-center rounded-full bg-surface-container-high text-on-surface hover:bg-secondary hover:text-white transition-colors">
            <span class="material-symbols-outlined text-[18px]">face_nod</span>
          </a>
          @endif
          @if($author->twitter ?? null)
          <a href="{{ $author->twitter }}" target="_blank" rel="noopener"
             class="w-9 h-9 flex items-center justify-center rounded-full bg-surface-container-high text-on-surface hover:bg-secondary hover:text-white transition-colors">
            <span class="material-symbols-outlined text-[18px]">alternate_email</span>
          </a>
          @endif
          @if($author->email ?? null)
          <a href="mailto:{{ $author->email }}"
             class="w-9 h-9 flex items-center justify-center rounded-full bg-surface-container-high text-on-surface hover:bg-secondary hover:text-white transition-colors">
            <span class="material-symbols-outlined text-[18px]">mail</span>
          </a>
          @endif
        </div>
      </div>

      <!-- Stats Card -->
      <div class="hidden md:block flex-shrink-0 bg-white border border-subtle p-5 min-w-[200px] rounded">
        <h3 class="font-label-caps text-label-caps text-on-surface-variant border-b border-subtle pb-3 mb-4">লেখকের পরিসংখ্যান</h3>
        <div class="grid grid-cols-2 gap-4 text-center">
          <div>
            <span class="font-headline-lg text-2xl font-bold text-primary block">{{ $news->total() }}</span>
            <span class="font-meta-data text-meta-data text-on-surface-variant">প্রকাশিত নিবন্ধ</span>
          </div>
          <div>
            @php $totalViews = \App\Models\News::where('author_id', $author->id)->sum('views'); @endphp
            <span class="font-headline-lg text-2xl font-bold text-primary block">{{ $totalViews > 1000 ? number_format($totalViews/1000,1).'K' : number_format($totalViews) }}</span>
            <span class="font-meta-data text-meta-data text-on-surface-variant">মোট পাঠক</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Articles + Sidebar -->
<main class="max-w-container-max mx-auto px-gutter py-stack-lg">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-stack-lg">

    <!-- Article List (70%) -->
    <div class="lg:col-span-8">
      <div class="flex items-center justify-between mb-6">
        <h2 class="font-headline-md text-xl text-primary">প্রকাশিত নিবন্ধসমূহ</h2>
        <span class="font-meta-data text-meta-data text-on-surface-variant">মোট {{ $news->total() }} টি</span>
      </div>

      @forelse($news as $item)
      <a href="{{ route('news.show', $item->slug) }}"
         class="flex flex-col md:flex-row gap-5 py-6 border-b border-subtle group hover:bg-surface-container-low px-3 -mx-3 rounded transition-colors">
        <div class="md:w-48 flex-shrink-0 overflow-hidden rounded aspect-video md:aspect-auto md:h-32">
          <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
               src="{{ $item->featured_image ?: 'https://picsum.photos/seed/'.$item->id.'/300/200' }}"
               alt="{{ $item->title }}"/>
        </div>
        <div class="flex-1 flex flex-col">
          @if($item->category)
          <span class="font-label-caps text-label-caps text-secondary mb-1">{{ $item->category->name }}</span>
          @endif
          <h3 class="font-headline-md text-[17px] leading-snug text-primary group-hover:text-secondary transition-colors mb-2 line-clamp-2">{{ $item->title }}</h3>
          @if($item->excerpt)
          <p class="font-body-sm text-body-sm text-on-surface-variant mb-3 line-clamp-2">{{ $item->excerpt }}</p>
          @endif
          <div class="mt-auto flex items-center gap-4 font-meta-data text-meta-data text-outline">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">schedule</span> {{ $item->published_at?->diffForHumans() }}</span>
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">visibility</span> {{ number_format($item->views ?? 0) }}</span>
          </div>
        </div>
      </a>
      @empty
      <div class="text-center py-16">
        <span class="material-symbols-outlined text-[80px] text-outline-variant">article</span>
        <p class="font-headline-md mt-4 text-on-surface-variant">এই লেখকের কোনো নিবন্ধ নেই।</p>
      </div>
      @endforelse

      <!-- Pagination -->
      @if($news->hasPages())
      <div class="pt-stack-lg">{{ $news->links() }}</div>
      @endif
    </div>

    <!-- Sidebar (30%) -->
    <aside class="lg:col-span-4 space-y-stack-lg">
      @php $popularNews = \App\Models\News::where('status','published')->orderBy('views','desc')->limit(5)->get(); @endphp
      @if($popularNews->count() > 0)
      <div class="bg-surface-container-low p-stack-md border border-subtle rounded">
        <h3 class="font-headline-md text-headline-md text-primary mb-4 border-l-4 border-secondary pl-3 flex items-center gap-2">
          <span class="material-symbols-outlined text-secondary">trending_up</span> পাঠকপ্রিয় খবর
        </h3>
        <ol class="space-y-4">
          @foreach($popularNews as $idx => $pn)
          <li class="{{ $idx > 0 ? 'border-t border-subtle pt-4' : '' }}">
            <a href="{{ route('news.show', $pn->slug) }}" class="flex gap-4 group">
              <span class="font-headline-lg text-2xl text-outline-variant group-hover:text-secondary transition-colors flex-shrink-0 w-8">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
              <p class="font-body-sm text-body-sm text-on-surface-variant group-hover:text-secondary transition-colors line-clamp-2">{{ $pn->title }}</p>
            </a>
          </li>
          @endforeach
        </ol>
      </div>
      @endif

      <!-- Newsletter -->
      <div class="bg-primary-container p-stack-lg rounded text-center">
        <h3 class="font-headline-md text-headline-md text-white mb-2">খবরের আপডেট পেতে</h3>
        <p class="font-body-sm text-on-primary-container mb-4 opacity-80">প্রতিদিন সেরা সব খবর সরাসরি ইমেইলে।</p>
        <div class="flex flex-col gap-2">
          <input class="w-full px-3 py-2 rounded border border-white/20 bg-white/10 text-white placeholder:text-white/60 outline-none focus:bg-white/20 transition-all" placeholder="আপনার ইমেইল" type="email"/>
          <button class="w-full py-2 bg-secondary text-white font-label-caps text-label-caps rounded hover:bg-news-red-accent transition-colors">সাবস্ক্রাইব</button>
        </div>
      </div>
    </aside>
  </div>
</main>

@endsection
