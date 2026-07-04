@extends('layouts.admin')

@section('page-title', 'ড্যাশবোর্ড')

@push('styles')
<style>
  @keyframes draw {
    from { stroke-dashoffset: 400; }
    to { stroke-dashoffset: 0; }
  }
  .chart-line {
    stroke-dasharray: 400;
    animation: draw 2s ease-out forwards;
  }
  .card-hover { transition: transform .2s, box-shadow .2s; }
  .card-hover:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
</style>
@endpush

@section('content')

{{-- ===== Stats Grid (2x2 → 4 cols on lg) ===== --}}
<section class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

  {{-- Live Views --}}
  <div class="card-hover bg-surface-container-lowest p-4 rounded-xl shadow-[0px_2px_4px_rgba(0,0,0,0.06)] flex flex-col gap-3 border border-outline-variant/30">
    <div class="flex items-center justify-between">
      <span class="material-symbols-outlined text-primary text-[26px]">visibility</span>
      <span class="text-[11px] font-bold text-tertiary bg-tertiary/10 px-2 py-0.5 rounded-full">
        +12%
      </span>
    </div>
    <div>
      <p class="text-sm text-on-surface-variant">লাইভ ভিউ</p>
      @php $liveViews = \App\Models\News::where('status','published')->sum('views'); @endphp
      <p class="text-2xl font-bold text-on-surface mt-0.5">
        {{ $liveViews > 1000 ? number_format($liveViews/1000,1).'K' : number_format($liveViews) }}
      </p>
    </div>
  </div>

  {{-- Articles --}}
  <div class="card-hover bg-surface-container-lowest p-4 rounded-xl shadow-[0px_2px_4px_rgba(0,0,0,0.06)] flex flex-col gap-3 border border-outline-variant/30">
    <div class="flex items-center justify-between">
      <span class="material-symbols-outlined text-secondary text-[26px]">article</span>
      <span class="text-[11px] font-bold text-tertiary bg-tertiary/10 px-2 py-0.5 rounded-full">
        +{{ \App\Models\News::whereDate('created_at', today())->count() }} আজ
      </span>
    </div>
    <div>
      <p class="text-sm text-on-surface-variant">সংবাদ</p>
      <p class="text-2xl font-bold text-on-surface mt-0.5">{{ number_format($totalNews ?? \App\Models\News::count()) }}</p>
    </div>
  </div>

  {{-- Comments --}}
  <div class="card-hover bg-surface-container-lowest p-4 rounded-xl shadow-[0px_2px_4px_rgba(0,0,0,0.06)] flex flex-col gap-3 border border-outline-variant/30">
    <div class="flex items-center justify-between">
      <span class="material-symbols-outlined text-tertiary text-[26px]">comment</span>
      @php $commentCount = \App\Models\Comment::count(); @endphp
      <span class="text-[11px] font-bold text-on-surface-variant bg-surface-container px-2 py-0.5 rounded-full">মোট</span>
    </div>
    <div>
      <p class="text-sm text-on-surface-variant">মন্তব্য</p>
      <p class="text-2xl font-bold text-on-surface mt-0.5">{{ number_format($commentCount) }}</p>
    </div>
  </div>

  {{-- Users --}}
  <div class="card-hover bg-surface-container-lowest p-4 rounded-xl shadow-[0px_2px_4px_rgba(0,0,0,0.06)] flex flex-col gap-3 border border-outline-variant/30">
    <div class="flex items-center justify-between">
      <span class="material-symbols-outlined text-surface-tint text-[26px]">group</span>
      @php $newUsers = \App\Models\User::whereDate('created_at', '>=', now()->subDays(30))->count(); @endphp
      <span class="text-[11px] font-bold text-tertiary bg-tertiary/10 px-2 py-0.5 rounded-full">+{{ $newUsers }}</span>
    </div>
    <div>
      <p class="text-sm text-on-surface-variant">ব্যবহারকারী</p>
      <p class="text-2xl font-bold text-on-surface mt-0.5">{{ number_format(\App\Models\User::count()) }}</p>
    </div>
  </div>

</section>

{{-- ===== Two Column: Chart + Quick Actions ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

  {{-- News Performance Chart --}}
  <section class="lg:col-span-2 bg-surface-container-lowest p-5 rounded-xl shadow-[0px_2px_4px_rgba(0,0,0,0.06)] border border-outline-variant/30">
    <div class="flex items-center justify-between mb-5">
      <h2 class="text-lg font-bold text-on-surface" style="font-family:'SolaimanLipi',serif;">সংবাদ কার্যক্ষমতা</h2>
      <span class="text-[11px] font-bold text-primary bg-primary/10 px-3 py-1 rounded-full uppercase tracking-wider">শেষ ৭ দিন</span>
    </div>
    <div class="relative h-48 w-full">
      @php
        $chartDays = collect(range(6,0))->map(fn($d) => [
          'label' => now()->subDays($d)->format('D')[0],
          'value' => \App\Models\News::whereDate('created_at', now()->subDays($d))->count(),
        ]);
        $maxVal = max($chartDays->pluck('value')->max(), 1);
      @endphp
      <svg class="absolute inset-0 h-full w-full" viewBox="0 0 100 60" preserveAspectRatio="none">
        <defs>
          <linearGradient id="chartGradient" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color:#004e9f;stop-opacity:1"/>
            <stop offset="100%" style="stop-color:#aac7ff;stop-opacity:1"/>
          </linearGradient>
          <linearGradient id="fillGradient" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" style="stop-color:#004e9f;stop-opacity:0.15"/>
            <stop offset="100%" style="stop-color:#004e9f;stop-opacity:0"/>
          </linearGradient>
        </defs>
        @php
          $points = $chartDays->values()->map(fn($d,$i) => [
            'x' => $i * (100/6),
            'y' => 55 - ($d['value'] / $maxVal * 45),
          ]);
          $pathD = $points->map(fn($p,$i) => ($i===0?'M':'L').round($p['x'],1).','.round($p['y'],1))->implode(' ');
          $fillD = $pathD . ' L100,60 L0,60 Z';
        @endphp
        <path d="{{ $fillD }}" fill="url(#fillGradient)"/>
        <path class="chart-line" d="{{ $pathD }}" fill="none" stroke="url(#chartGradient)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        @foreach($points as $p)
        <circle cx="{{ round($p['x'],1) }}" cy="{{ round($p['y'],1) }}" r="2" fill="#004e9f"/>
        @endforeach
      </svg>
      <div class="absolute bottom-0 left-0 right-0 flex justify-between px-1">
        @foreach($chartDays as $d)
        <span class="text-[10px] font-bold text-on-surface-variant">{{ $d['label'] }}</span>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Quick Actions --}}
  <section class="bg-surface-container-lowest p-5 rounded-xl shadow-[0px_2px_4px_rgba(0,0,0,0.06)] border border-outline-variant/30 flex flex-col">
    <h2 class="text-lg font-bold text-on-surface mb-4" style="font-family:'SolaimanLipi',serif;">দ্রুত অ্যাকশন</h2>
    <div class="grid grid-cols-2 gap-3 flex-1">
      @php
        $quickActions = [
            ['route' => 'admin.news.create', 'icon' => 'add_circle', 'label' => 'নতুন সংবাদ', 'color' => 'text-primary', 'bg' => 'bg-primary/10', 'roles' => ['super-admin','admin','editor','reporter']],
            ['route' => 'admin.news.index', 'icon' => 'article', 'label' => 'সংবাদ তালিকা', 'color' => 'text-secondary', 'bg' => 'bg-secondary/10', 'roles' => ['super-admin','admin','editor','reporter']],
            ['route' => 'admin.categories.index', 'icon' => 'category', 'label' => 'বিভাগসমূহ', 'color' => 'text-tertiary', 'bg' => 'bg-tertiary/10', 'roles' => ['super-admin','admin','editor']],
            ['route' => 'admin.users.index', 'icon' => 'group', 'label' => 'ব্যবহারকারী', 'color' => 'text-primary', 'bg' => 'bg-primary/10', 'roles' => ['super-admin','admin']],
            ['route' => 'admin.tags.index', 'icon' => 'sell', 'label' => 'ট্যাগসমূহ', 'color' => 'text-secondary', 'bg' => 'bg-secondary/10', 'roles' => ['super-admin','admin','editor']],
            ['route' => 'admin.settings', 'icon' => 'settings', 'label' => 'সেটিংস', 'color' => 'text-tertiary', 'bg' => 'bg-tertiary/10', 'roles' => ['super-admin','admin']],
        ];
      @endphp
      @foreach($quickActions as $action)
      @if(auth()->user()->hasRole($action['roles']))
      <a href="{{ route($action['route']) }}"
         class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl {{ $action['bg'] }} hover:opacity-80 transition-opacity text-center group">
        <span class="material-symbols-outlined {{ $action['color'] }} text-[24px]">{{ $action['icon'] }}</span>
        <span class="text-[11px] font-bold text-on-surface-variant group-hover:text-on-surface transition-colors leading-tight">{{ $action['label'] }}</span>
      </a>
      @endif
      @endforeach
    </div>
  </section>

</div>

{{-- ===== Recent Articles ===== --}}
<section class="mb-6">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-on-surface" style="font-family:'SolaimanLipi',serif;">সাম্প্রতিক সংবাদ</h2>
    <a href="{{ route('admin.news.index') }}" class="text-primary font-bold text-[12px] uppercase tracking-wider hover:underline">সব দেখুন</a>
  </div>
  @php $recentNews = \App\Models\News::with(['category','author'])->latest()->limit(5)->get(); @endphp
  <div class="space-y-3">
    @foreach($recentNews as $article)
    <div class="bg-surface-container-lowest p-3 rounded-xl flex gap-4 shadow-sm border border-outline-variant/30 hover:shadow-md transition-shadow">
      <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
        <img class="w-full h-full object-cover"
             src="{{ $article->featured_image ? Storage::url($article->featured_image) : 'https://picsum.photos/seed/'.$article->id.'/200/200' }}"
             alt="{{ $article->title }}">
      </div>
      <div class="flex-1 flex flex-col justify-between min-w-0">
        <h3 class="text-sm font-bold text-on-surface line-clamp-1" style="font-family:'SolaimanLipi',serif;">
          {{ $article->title }}
        </h3>
        <div class="flex items-center justify-between gap-2 flex-wrap">
          <div class="flex items-center gap-2">
            @if($article->category)
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
              {{ $article->status === 'published' ? 'bg-primary/10 text-primary' :
                 ($article->status === 'draft'    ? 'bg-outline-variant/50 text-on-surface-variant' :
                  'bg-secondary/10 text-secondary') }}">
              {{ $article->category->name }}
            </span>
            @endif
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
              {{ $article->status === 'published' ? 'bg-tertiary/10 text-tertiary' :
                 ($article->status === 'draft'    ? 'bg-surface-container text-on-surface-variant' :
                  'bg-secondary/10 text-secondary') }}">
              {{ $article->status === 'published' ? 'প্রকাশিত' : ($article->status === 'draft' ? 'খসড়া' : 'নির্ধারিত') }}
            </span>
          </div>
          <span class="text-[10px] text-on-surface-variant">{{ $article->created_at->diffForHumans() }}</span>
        </div>
      </div>
      <div class="flex items-center gap-1 flex-shrink-0">
        <a href="{{ route('admin.news.edit', $article) }}"
           class="w-8 h-8 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary/10 text-on-surface-variant hover:text-primary transition-colors">
          <span class="material-symbols-outlined text-[16px]">edit</span>
        </a>
      </div>
    </div>
    @endforeach
  </div>
</section>

{{-- ===== Bottom Two: System Logs + Category Stats ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

  {{-- System Logs --}}
  <section class="bg-surface-container-lowest p-5 rounded-xl shadow-[0px_2px_4px_rgba(0,0,0,0.06)] border border-outline-variant/30">
    <h2 class="text-lg font-bold text-on-surface mb-5" style="font-family:'SolaimanLipi',serif;">সাম্প্রতিক কার্যক্রম</h2>
    <div class="space-y-4">
      @php
        $latestPublished = \App\Models\News::where('status','published')->with('author')->latest('published_at')->first();
        $latestUser = \App\Models\User::latest()->first();
        $latestComment = \App\Models\Comment::with('user')->latest()->first();
      @endphp

      @if($latestPublished)
      <div class="flex gap-4">
        <div class="w-2 bg-primary rounded-full flex-shrink-0"></div>
        <div>
          <p class="text-sm font-bold text-on-surface">সংবাদ প্রকাশিত</p>
          <p class="text-[12px] text-on-surface-variant line-clamp-1">{{ $latestPublished->title }}</p>
          <p class="text-[11px] text-outline font-bold mt-1">{{ $latestPublished->published_at?->diffForHumans() }}</p>
        </div>
      </div>
      @endif

      @if($latestUser)
      <div class="flex gap-4">
        <div class="w-2 bg-tertiary rounded-full flex-shrink-0"></div>
        <div>
          <p class="text-sm font-bold text-on-surface">নতুন ব্যবহারকারী</p>
          <p class="text-[12px] text-on-surface-variant">{{ $latestUser->name }} যোগ দিয়েছেন</p>
          <p class="text-[11px] text-outline font-bold mt-1">{{ $latestUser->created_at->diffForHumans() }}</p>
        </div>
      </div>
      @endif

      @if($latestComment)
      <div class="flex gap-4">
        <div class="w-2 bg-secondary rounded-full flex-shrink-0"></div>
        <div>
          <p class="text-sm font-bold text-on-surface">নতুন মন্তব্য</p>
          <p class="text-[12px] text-on-surface-variant line-clamp-1">{{ $latestComment->user?->name ?? 'অতিথি' }}: {{ Str::limit($latestComment->content ?? $latestComment->body ?? '', 60) }}</p>
          <p class="text-[11px] text-outline font-bold mt-1">{{ $latestComment->created_at->diffForHumans() }}</p>
        </div>
      </div>
      @else
      <div class="flex gap-4">
        <div class="w-2 bg-error rounded-full flex-shrink-0"></div>
        <div>
          <p class="text-sm font-bold text-on-surface">কোনো মন্তব্য নেই</p>
          <p class="text-[12px] text-on-surface-variant">এখনো কোনো মন্তব্য করা হয়নি</p>
        </div>
      </div>
      @endif
    </div>
  </section>

  {{-- Category Stats --}}
  <section class="bg-surface-container-lowest p-5 rounded-xl shadow-[0px_2px_4px_rgba(0,0,0,0.06)] border border-outline-variant/30">
    <div class="flex items-center justify-between mb-5">
      <h2 class="text-lg font-bold text-on-surface" style="font-family:'SolaimanLipi',serif;">বিভাগ পরিসংখ্যান</h2>
      <a href="{{ route('admin.categories.index') }}" class="text-primary font-bold text-[12px] uppercase tracking-wider hover:underline">সব দেখুন</a>
    </div>
    @php
      $catStats = \App\Models\Category::withCount(['news' => fn($q) => $q->where('status','published')])->orderByDesc('news_count')->limit(6)->get();
      $maxCount = max($catStats->pluck('news_count')->max(), 1);
    @endphp
    <div class="space-y-3">
      @foreach($catStats as $cat)
      <div class="flex items-center gap-3">
        <span class="text-[12px] text-on-surface-variant w-24 truncate flex-shrink-0">{{ $cat->name }}</span>
        <div class="flex-1 bg-surface-container-high rounded-full h-2 overflow-hidden">
          <div class="h-full bg-primary rounded-full transition-all duration-500"
               style="width: {{ ($cat->news_count / $maxCount) * 100 }}%"></div>
        </div>
        <span class="text-[11px] font-bold text-primary w-6 text-right flex-shrink-0">{{ $cat->news_count }}</span>
      </div>
      @endforeach
    </div>
  </section>

</div>

{{-- Floating Action Button --}}
<a href="{{ route('admin.news.create') }}"
   class="fixed bottom-8 right-8 w-14 h-14 bg-secondary-container text-on-secondary-container rounded-full shadow-lg flex items-center justify-center hover:opacity-90 active:scale-90 transition-all z-40">
  <span class="material-symbols-outlined text-[28px]" style="font-variation-settings:'FILL' 1;">add</span>
</a>

@endsection
