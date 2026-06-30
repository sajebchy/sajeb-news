@extends('public.layout')

@section('title', $query ? 'অনুসন্ধান: ' . $query . ' - সজীব নিউজ' : 'অনুসন্ধান - সজীব নিউজ')

@section('content')

<main class="max-w-container-max mx-auto px-gutter py-stack-lg">
  <!-- Search Header -->
  <div class="mb-stack-lg border-b border-subtle pb-6">
    <h1 class="font-headline-lg text-headline-lg text-primary mb-4">
      @if($query)
        "<span class="text-secondary">{{ $query }}</span>" এর অনুসন্ধান ফলাফল
      @else
        সংবাদ অনুসন্ধান
      @endif
    </h1>
    <!-- Search Form -->
    <form action="{{ route('news.search') }}" method="GET" class="flex gap-3 max-w-2xl">
      <div class="relative flex-1">
        <input name="q" value="{{ $query }}" type="text"
               class="w-full pl-4 pr-10 py-3 bg-surface-container-low border border-subtle rounded-lg font-body-main text-body-main focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary transition-all"
               placeholder="খুঁজুন..." autofocus/>
        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant material-symbols-outlined">search</span>
      </div>
      <button type="submit" class="bg-secondary text-white px-6 py-3 rounded-lg font-label-caps text-label-caps hover:bg-news-red-accent transition-colors whitespace-nowrap">
        অনুসন্ধান
      </button>
    </form>
    @if($query)
    <p class="text-meta-data font-meta-data text-on-surface-variant mt-3">
      {{ $news->total() ?? $news->count() }} টি ফলাফল পাওয়া গেছে
    </p>
    @endif
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-stack-lg">
    <!-- Results -->
    <div class="lg:col-span-8">
      @if($query)
        @forelse($news as $article)
        <article class="flex flex-col md:flex-row gap-6 p-4 mb-4 border-b border-subtle group hover:bg-surface-container-low rounded-lg transition-colors">
          @if($article->featured_image)
          <div class="md:w-48 flex-shrink-0">
            <img class="w-full h-32 object-cover rounded-md"
                 src="{{ \Storage::url($article->featured_image) }}" alt="{{ $article->title }}"/>
          </div>
          @endif
          <div class="flex-1 flex flex-col">
            @if($article->category)
            <span class="text-secondary font-label-caps text-label-caps mb-1">{{ $article->category->name }}</span>
            @endif
            <a href="{{ route('news.show', $article->slug) }}">
              <h3 class="font-headline-md text-headline-md text-primary mb-2 group-hover:text-secondary transition-colors leading-tight">{{ $article->title }}</h3>
            </a>
            @if($article->excerpt)
            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2 mb-3">{{ $article->excerpt }}</p>
            @endif
            <div class="mt-auto flex items-center gap-4 text-meta-data font-meta-data text-outline">
              @if($article->author)<span>{{ $article->author->name }}</span>@endif
              @if($article->published_at)<span>{{ $article->published_at->diffForHumans() }}</span>@endif
              <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">visibility</span> {{ number_format($article->views) }}</span>
            </div>
          </div>
        </article>
        @empty
        <div class="text-center py-16">
          <span class="material-symbols-outlined text-8xl text-outline-variant">search_off</span>
          <h3 class="font-headline-md text-xl mt-4 text-on-surface-variant">"{{ $query }}" এর জন্য কোনো ফলাফল পাওয়া যায়নি</h3>
          <p class="font-body-sm text-on-surface-variant mt-2">ভিন্ন কীওয়ার্ড দিয়ে চেষ্টা করুন</p>
        </div>
        @endforelse

        <!-- Pagination -->
        @if($news instanceof \Illuminate\Pagination\LengthAwarePaginator && $news->hasPages())
        <div class="flex justify-center pt-stack-lg">
          {{ $news->appends(['q' => $query])->links() }}
        </div>
        @endif
      @else
        <div class="text-center py-16">
          <span class="material-symbols-outlined text-8xl text-outline-variant">manage_search</span>
          <h3 class="font-headline-md text-xl mt-4 text-on-surface-variant">কী খুঁজছেন লিখুন</h3>
        </div>
      @endif
    </div>

    <!-- Sidebar -->
    <aside class="lg:col-span-4 space-y-stack-lg">
      @php $trendingSearch = \App\Models\News::where('status','published')->orderBy('views','desc')->limit(5)->get(); @endphp
      <div class="bg-surface-muted p-stack-md rounded-lg border border-subtle">
        <h3 class="font-headline-md text-headline-md text-primary mb-4 border-l-4 border-secondary pl-3">ট্রেন্ডিং</h3>
        <div class="space-y-4">
          @foreach($trendingSearch as $idx => $ts)
          <a href="{{ route('news.show', $ts->slug) }}" class="group flex gap-4 items-start {{ $idx > 0 ? 'border-t border-subtle/50 pt-4' : '' }} block">
            <span class="font-display-breaking text-3xl text-outline-variant font-bold leading-none group-hover:text-secondary transition-colors">{{ $idx+1 }}.</span>
            <p class="font-body-sm text-body-sm text-on-surface-variant group-hover:text-secondary transition-colors line-clamp-2">{{ $ts->title }}</p>
          </a>
          @endforeach
        </div>
      </div>
    </aside>
  </div>
</main>

@endsection
