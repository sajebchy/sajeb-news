@extends('public.layout')

@section('title', 'চাকরির খবর — সজীব নিউজ')
@section('meta_description', 'বাংলাদেশের সর্বশেষ চাকরির খবর ও নিয়োগ বিজ্ঞপ্তি। সরকারি, বেসরকারি, ব্যাংক, এনজিও — সব সেক্টরের চাকরি এক জায়গায়।')
@section('meta_keywords', 'চাকরি, নিয়োগ বিজ্ঞপ্তি, বাংলাদেশ চাকরি, সরকারি চাকরি, বেসরকারি চাকরি, job circular bangladesh')
@section('canonical', route('jobs.index'))

@push('styles')
<meta property="og:type" content="website">
<meta property="og:title" content="চাকরির খবর — সজীব নিউজ">
<meta property="og:description" content="বাংলাদেশের সর্বশেষ চাকরির খবর ও নিয়োগ বিজ্ঞপ্তি">
<meta property="og:url" content="{{ route('jobs.index') }}">
@endpush

@push('scripts')
@php
  $__seoSvc = app(\App\Services\SeoService::class);
  $__breadSch = $__seoSvc->getBreadcrumbSchema(['চাকরি' => route('jobs.index')]);
@endphp
<script type="application/ld+json">{!! json_encode($__breadSch, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

<main class="max-w-container-max mx-auto px-gutter py-stack-lg">

  {{-- Page Header --}}
  <div class="mb-stack-lg border-b border-subtle pb-4">
    <h1 class="font-headline-lg text-headline-lg text-primary mb-1">চাকরির খবর</h1>
    <p class="text-meta-data font-meta-data text-on-surface-variant">বাংলাদেশের সর্বশেষ নিয়োগ বিজ্ঞপ্তি ও চাকরির সুযোগ</p>
  </div>

  {{-- Search & Filter --}}
  <form method="GET" action="{{ route('jobs.index') }}" class="mb-8">
    <div class="bg-surface-container rounded-lg p-4 flex flex-col md:flex-row gap-3 items-center">
      <div class="relative flex-1 w-full">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
        <input type="text" name="q" value="{{ request('q') }}"
               class="w-full pl-10 pr-4 py-2.5 bg-white border border-subtle rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary"
               placeholder="চাকরি, প্রতিষ্ঠান বা দক্ষতা খুঁজুন...">
      </div>
      <select name="sector" class="bg-white border border-subtle rounded-lg px-3 py-2.5 text-sm w-full md:w-auto">
        <option value="">সব সেক্টর</option>
        @foreach(\App\Models\JobPost::jobSectors() as $sector)
        <option value="{{ $sector }}" @selected(request('sector') === $sector)>{{ $sector }}</option>
        @endforeach
      </select>
      <select name="type" class="bg-white border border-subtle rounded-lg px-3 py-2.5 text-sm w-full md:w-auto">
        <option value="">সব ধরন</option>
        <option value="full-time" @selected(request('type') === 'full-time')>পূর্ণকালীন</option>
        <option value="part-time" @selected(request('type') === 'part-time')>খণ্ডকালীন</option>
        <option value="contract" @selected(request('type') === 'contract')>চুক্তিভিত্তিক</option>
        <option value="internship" @selected(request('type') === 'internship')>ইন্টার্নশিপ</option>
        <option value="freelance" @selected(request('type') === 'freelance')>ফ্রিল্যান্স</option>
      </select>
      <select name="division" class="bg-white border border-subtle rounded-lg px-3 py-2.5 text-sm w-full md:w-auto">
        <option value="">সব বিভাগ</option>
        @foreach(\App\Models\JobPost::divisions() as $div)
        <option value="{{ $div }}" @selected(request('division') === $div)>{{ $div }}</option>
        @endforeach
      </select>
      <button type="submit" class="bg-primary text-white px-5 py-2.5 rounded-lg font-bold text-sm hover:opacity-90 transition-all whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px] align-middle mr-1">search</span> খুঁজুন
      </button>
    </div>
  </form>

  {{-- Featured Jobs --}}
  @if($featuredJobs->count() > 0 && !request('q') && !request('sector') && !request('type') && !request('division'))
  <section class="mb-stack-lg">
    <h2 class="font-headline-md text-lg text-primary mb-4 flex items-center gap-2">
      <span class="material-symbols-outlined text-[22px]">star</span> ফিচার্ড চাকরি
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach($featuredJobs as $fJob)
      <a href="{{ route('jobs.show', $fJob) }}" class="block bg-yellow-50 border-2 border-yellow-200 rounded-lg p-4 hover:shadow-md transition-all group">
        <div class="flex items-start gap-3">
          @if($fJob->company_logo)
          <img src="{{ asset('storage/' . $fJob->company_logo) }}" alt="{{ $fJob->company_name }}" class="w-12 h-12 object-contain rounded flex-shrink-0">
          @else
          <div class="w-12 h-12 bg-primary/10 rounded flex items-center justify-center flex-shrink-0">
            <span class="material-symbols-outlined text-primary">business</span>
          </div>
          @endif
          <div class="min-w-0">
            <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors line-clamp-2">{{ $fJob->title }}</h3>
            <p class="text-sm text-on-surface-variant mt-1">{{ $fJob->company_name }}</p>
            <div class="flex flex-wrap gap-2 mt-2">
              <span class="inline-flex items-center gap-1 text-xs bg-white px-2 py-0.5 rounded-full text-on-surface-variant">
                <span class="material-symbols-outlined text-[14px]">location_on</span> {{ $fJob->district ?? $fJob->division ?? 'বাংলাদেশ' }}
              </span>
              <span class="inline-flex items-center gap-1 text-xs bg-white px-2 py-0.5 rounded-full text-on-surface-variant">
                <span class="material-symbols-outlined text-[14px]">payments</span> {{ $fJob->salary_range }}
              </span>
            </div>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </section>
  @endif

  {{-- Job Listing --}}
  <section>
    <h2 class="font-headline-md text-lg text-on-surface mb-4">
      @if(request('q') || request('sector') || request('type') || request('division'))
        অনুসন্ধানের ফলাফল ({{ $jobs->total() }}টি)
      @else
        সর্বশেষ চাকরি
      @endif
    </h2>

    @if($jobs->count() > 0)
    <div class="space-y-4">
      @foreach($jobs as $job)
      <a href="{{ route('jobs.show', $job) }}" class="block bg-surface-container-lowest border border-subtle rounded-lg p-5 hover:shadow-md hover:border-primary/30 transition-all group">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
          <div class="flex items-start gap-3 flex-1 min-w-0">
            @if($job->company_logo)
            <img src="{{ asset('storage/' . $job->company_logo) }}" alt="{{ $job->company_name }}" class="w-14 h-14 object-contain rounded border border-subtle flex-shrink-0">
            @else
            <div class="w-14 h-14 bg-primary/5 rounded border border-subtle flex items-center justify-center flex-shrink-0">
              <span class="material-symbols-outlined text-primary/50 text-[28px]">business</span>
            </div>
            @endif
            <div class="min-w-0">
              <h3 class="font-bold text-on-surface group-hover:text-primary transition-colors text-base line-clamp-1">{{ $job->title }}</h3>
              <p class="text-sm text-on-surface-variant mt-0.5">{{ $job->company_name }}</p>
              <div class="flex flex-wrap gap-2 mt-2">
                @if($job->is_urgent)
                <span class="text-[11px] font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded-full">জরুরি</span>
                @endif
                <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant">
                  <span class="material-symbols-outlined text-[14px]">work</span> {{ $job->job_type_label }}
                </span>
                <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant">
                  <span class="material-symbols-outlined text-[14px]">location_on</span> {{ $job->district ?? $job->division ?? 'বাংলাদেশ' }}
                </span>
                <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant">
                  <span class="material-symbols-outlined text-[14px]">payments</span> {{ $job->salary_range }}
                </span>
                @if($job->workplace_type !== 'onsite')
                <span class="inline-flex items-center gap-1 text-xs text-on-surface-variant">
                  <span class="material-symbols-outlined text-[14px]">laptop_mac</span> {{ $job->workplace_type_label }}
                </span>
                @endif
              </div>
            </div>
          </div>
          <div class="flex flex-row md:flex-col items-center md:items-end gap-2 md:gap-1 flex-shrink-0">
            @if($job->application_deadline)
            <span class="text-xs text-on-surface-variant whitespace-nowrap">
              <span class="material-symbols-outlined text-[14px] align-middle">schedule</span>
              ডেডলাইন: {{ $job->application_deadline->format('d M, Y') }}
            </span>
            @endif
            <span class="text-xs text-on-surface-variant/60">{{ $job->published_at?->diffForHumans() }}</span>
          </div>
        </div>
      </a>
      @endforeach
    </div>

    {{-- Pagination --}}
    @if($jobs->hasPages())
    <div class="mt-8 flex justify-center">
      {{ $jobs->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-16">
      <span class="material-symbols-outlined text-on-surface-variant/30" style="font-size:64px;">work_off</span>
      <p class="text-on-surface-variant font-semibold mt-3">কোনো চাকরি পাওয়া যায়নি</p>
      @if(request('q') || request('sector') || request('type') || request('division'))
      <a href="{{ route('jobs.index') }}" class="inline-block mt-4 text-primary font-bold text-sm hover:underline">সব চাকরি দেখুন</a>
      @endif
    </div>
    @endif
  </section>

</main>

@endsection
