@extends('public.layout')

@section('title', $category->name . ' - সজীব নিউজ')
@section('meta_description', $category->description ?? $category->name . ' বিভাগের সর্বশেষ সংবাদ - সজীব নিউজ')

@section('content')

<!-- Breaking Ticker -->
@php $catBreaking = \App\Models\News::where('status','published')->where('is_breaking',true)->latest('published_at')->limit(3)->get(); @endphp
@if($catBreaking->count() > 0)
<div class="w-full bg-primary text-white overflow-hidden h-10 flex items-center">
  <div class="px-4 py-1 bg-secondary font-headline-md text-white whitespace-nowrap z-10 flex items-center gap-2 flex-shrink-0">
    <span class="animate-pulse w-2 h-2 bg-white rounded-full inline-block"></span> সদ্য সংবাদ
  </div>
  <div class="overflow-hidden flex-1">
    <div class="ticker-scroll whitespace-nowrap font-body-main text-body-sm flex gap-16 items-center">
      @foreach($catBreaking as $bk)<a href="{{ route('news.show', $bk->slug) }}" class="hover:underline">{{ $bk->title }}</a>@endforeach
    </div>
  </div>
</div>
@endif

<main class="max-w-container-max mx-auto px-gutter py-stack-lg">
  <!-- Category Header -->
  <div class="mb-stack-lg flex flex-col md:flex-row md:items-end justify-between border-b border-subtle pb-4">
    <div>
      <h2 class="font-headline-lg text-headline-lg text-primary mb-1">{{ $category->name }}</h2>
      @if($category->description)
      <p class="text-meta-data font-meta-data text-on-surface-variant">{{ $category->description }}</p>
      @endif
    </div>
    <div class="mt-4 md:mt-0 text-meta-data font-meta-data text-on-surface-variant flex items-center gap-2">
      <span class="material-symbols-outlined text-[18px]">calendar_today</span>
      <span id="cat-date"></span>
    </div>
  </div>

  <!-- Featured News Card (First Article) -->
  @php $featuredArticle = $news->first(); @endphp
  @if($featuredArticle)
  <section class="mb-stack-lg">
    <a href="{{ route('news.show', $featuredArticle->slug) }}" class="block relative group overflow-hidden bg-surface-container rounded-lg">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-0 overflow-hidden">
        <div class="md:col-span-8 overflow-hidden">
          <img class="w-full h-[300px] md:h-[400px] object-cover transition-transform duration-700 group-hover:scale-105"
               src="{{ $featuredArticle->featured_image ?: 'https://picsum.photos/seed/'.$featuredArticle->id.'/800/450' }}"
               alt="{{ $featuredArticle->title }}"/>
        </div>
        <div class="md:col-span-4 p-gutter flex flex-col justify-center bg-white">
          <span class="text-secondary font-label-caps text-label-caps mb-2">{{ $category->name }}</span>
          <h3 class="font-headline-lg text-headline-lg text-primary mb-4 leading-tight">{{ $featuredArticle->title }}</h3>
          @if($featuredArticle->excerpt)
          <p class="font-body-main text-body-main text-on-surface-variant mb-6 line-clamp-3">{{ $featuredArticle->excerpt }}</p>
          @endif
          <div class="flex items-center gap-3">
            <span class="bg-primary text-white font-label-caps text-label-caps px-6 py-3 rounded">আরও পড়ুন</span>
            @if($featuredArticle->published_at)
            <span class="text-meta-data font-meta-data text-outline">{{ $featuredArticle->published_at->diffForHumans() }}</span>
            @endif
          </div>
        </div>
      </div>
    </a>
  </section>
  @endif

  <!-- 70/30 Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-stack-lg">
    <!-- Left: News List -->
    <div class="lg:col-span-8 space-y-stack-lg">
      @forelse($news->skip(1) as $article)
      <article class="flex flex-col md:flex-row gap-6 p-4 border border-transparent hover:bg-surface-container-low rounded-lg group hover-lift">
        <div class="md:w-1/3 flex-shrink-0">
          <img class="w-full h-48 md:h-44 object-cover rounded-md"
               src="{{ $article->featured_image ?: 'https://picsum.photos/seed/'.$article->id.'/400/250' }}"
               alt="{{ $article->title }}"/>
        </div>
        <div class="flex-1 flex flex-col">
          <span class="text-secondary font-label-caps text-label-caps mb-1">{{ $article->category->name ?? $category->name }}</span>
          <a href="{{ route('news.show', $article->slug) }}">
            <h4 class="font-headline-md text-headline-md text-primary mb-2 group-hover:text-secondary transition-colors">{{ $article->title }}</h4>
          </a>
          @if($article->excerpt)
          <p class="font-body-sm text-body-sm text-on-surface-variant mb-4 line-clamp-3">{{ $article->excerpt }}</p>
          @endif
          <div class="mt-auto flex items-center justify-between text-meta-data font-meta-data text-outline">
            <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">person</span> {{ $article->author->name ?? 'সংবাদদাতা' }}</span>
            @if($article->published_at)<span>{{ $article->published_at->diffForHumans() }}</span>@endif
          </div>
        </div>
      </article>
      @empty
      <div class="text-center py-12 text-on-surface-variant">
        <span class="material-symbols-outlined text-6xl text-outline-variant">article</span>
        <p class="font-headline-md mt-4">এই বিভাগে কোনো সংবাদ নেই</p>
      </div>
      @endforelse

      <!-- Pagination -->
      @if($news instanceof \Illuminate\Pagination\LengthAwarePaginator && $news->hasPages())
      <div class="flex justify-center pt-stack-md">
        <nav class="flex gap-2">
          @for($p = 1; $p <= $news->lastPage(); $p++)
          <a href="{{ $news->url($p) }}" class="w-10 h-10 flex items-center justify-center border border-subtle rounded hover:bg-primary hover:text-white transition-colors {{ $p === $news->currentPage() ? 'bg-primary text-white' : '' }}">{{ $p }}</a>
          @endfor
          @if($news->hasMorePages())
          <a href="{{ $news->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center border border-subtle rounded hover:bg-primary hover:text-white transition-colors">
            <span class="material-symbols-outlined">chevron_right</span>
          </a>
          @endif
        </nav>
      </div>
      @endif
    </div>

    <!-- Sidebar -->
    <aside class="lg:col-span-4 space-y-stack-lg">
      <!-- Popular in this category -->
      @php $catPopular = \App\Models\News::where('status','published')->where('category_id',$category->id)->orderBy('views','desc')->limit(4)->get(); @endphp
      <div class="bg-surface-muted p-stack-md rounded-lg border border-subtle">
        <h3 class="font-headline-md text-headline-md text-primary mb-4 border-l-4 border-secondary pl-3">জনপ্রিয় সংবাদ</h3>
        <div class="space-y-4">
          @forelse($catPopular as $idx => $cp)
          <a href="{{ route('news.show', $cp->slug) }}" class="group flex gap-4 items-start {{ $idx > 0 ? 'border-t border-subtle/50 pt-4' : '' }} block">
            <span class="font-display-breaking text-3xl text-outline-variant font-bold leading-none group-hover:text-secondary transition-colors">{{ $idx + 1 }}.</span>
            <p class="font-body-sm text-body-sm text-on-surface-variant group-hover:text-secondary transition-colors">{{ $cp->title }}</p>
          </a>
          @empty
          <p class="text-body-sm text-on-surface-variant">কোনো সংবাদ নেই</p>
          @endforelse
        </div>
      </div>

      <!-- Poll / Sports Poll style -->
      <div class="bg-primary p-stack-md rounded-lg text-white">
        <div class="flex items-center gap-2 mb-4">
          <span class="material-symbols-outlined text-secondary">how_to_vote</span>
          <h3 class="font-headline-md text-headline-md">আজকের জরিপ</h3>
        </div>
        <p class="font-body-main text-body-main mb-6 leading-snug">আপনি কি সজীব নিউজের সংবাদ উপস্থাপনা পছন্দ করেন?</p>
        <div class="space-y-3">
          <button class="w-full text-left p-3 border border-white/20 rounded hover:bg-white/10 transition-colors">হ্যাঁ, অনেক ভালো লাগে</button>
          <button class="w-full text-left p-3 border border-white/20 rounded hover:bg-white/10 transition-colors">মাঝারি মানের</button>
          <button class="w-full text-left p-3 border border-white/20 rounded hover:bg-white/10 transition-colors">আরও উন্নত করা দরকার</button>
        </div>
        <button class="w-full mt-6 py-3 bg-secondary text-white font-label-caps text-label-caps rounded-full hover:brightness-110 transition-all shadow-lg">ভোট দিন</button>
      </div>

      <!-- Newsletter -->
      <div class="bg-surface-container-high p-stack-md rounded-lg">
        <h3 class="font-headline-md text-headline-md text-primary mb-2">প্রতিদিনের খবর</h3>
        <p class="text-body-sm text-body-sm text-on-surface-variant mb-4">সকালে আপনার ইনবক্সে সেরা সংবাদ পেতে সাবস্ক্রাইব করুন।</p>
        <form class="space-y-3">
          <input class="w-full p-3 border border-subtle rounded focus:ring-2 focus:ring-primary focus:border-transparent outline-none" placeholder="আপনার ইমেইল" type="email"/>
          <button type="submit" class="w-full py-3 bg-primary text-white font-label-caps text-label-caps rounded hover:bg-news-blue-dark transition-colors">সাবস্ক্রাইব</button>
        </form>
      </div>
    </aside>
  </div>
</main>

<script>
const _d=new Date();const _m=['জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'];
const _b=['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
function tb(n){return n.toString().split('').map(x=>_b[+x]??x).join('');}
const el=document.getElementById('cat-date');if(el)el.textContent=`${tb(_d.getDate())} ${_m[_d.getMonth()]} ${tb(_d.getFullYear())}`;
</script>

@endsection
