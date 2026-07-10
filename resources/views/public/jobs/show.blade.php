@extends('public.layout')

@section('title', ($job_post->meta_title ?? $job_post->title . ' — ' . $job_post->company_name) . ' | সজীব নিউজ')
@section('meta_description', $job_post->meta_description ?? mb_substr(strip_tags($job_post->description), 0, 160))
@if($job_post->meta_keywords)
@section('meta_keywords', $job_post->meta_keywords)
@else
@section('meta_keywords', 'চাকরি, ' . $job_post->company_name . ', ' . $job_post->job_sector . ', ' . ($job_post->district ?? 'বাংলাদেশ') . ', নিয়োগ বিজ্ঞপ্তি')
@endif
@section('canonical', $job_post->canonical_url ?? route('jobs.show', $job_post->slug))

@push('styles')
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $job_post->title }} — {{ $job_post->company_name }}">
<meta property="og:description" content="{{ $job_post->og_description ?? mb_substr(strip_tags($job_post->description), 0, 200) }}">
<meta property="og:url" content="{{ route('jobs.show', $job_post->slug) }}">
@if($job_post->og_image)
<meta property="og:image" content="{{ asset('storage/' . $job_post->og_image) }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $job_post->title }} — {{ $job_post->company_name }}">
<meta name="twitter:description" content="{{ $job_post->og_description ?? mb_substr(strip_tags($job_post->description), 0, 200) }}">
@endpush

@push('scripts')
{{-- JobPosting Schema.org (SEO — Google for Jobs) --}}
@php
$jobSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'JobPosting',
    'title' => $job_post->title,
    'description' => strip_tags($job_post->description),
    'datePosted' => $job_post->published_at?->toIso8601String(),
    'employmentType' => match($job_post->job_type) {
        'full-time' => 'FULL_TIME',
        'part-time' => 'PART_TIME',
        'contract' => 'CONTRACTOR',
        'internship' => 'INTERN',
        'freelance' => 'OTHER',
        default => 'FULL_TIME',
    },
    'hiringOrganization' => [
        '@type' => 'Organization',
        'name' => $job_post->company_name,
    ],
    'jobLocation' => [
        '@type' => 'Place',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $job_post->district ?? $job_post->location,
            'addressRegion' => $job_post->division,
            'addressCountry' => 'BD',
        ],
    ],
    'inLanguage' => 'bn',
];

if ($job_post->company_logo) {
    $jobSchema['hiringOrganization']['logo'] = asset('storage/' . $job_post->company_logo);
}

if ($job_post->application_deadline) {
    $jobSchema['validThrough'] = $job_post->application_deadline->toIso8601String();
}

if ($job_post->salary_min || $job_post->salary_max) {
    $jobSchema['baseSalary'] = [
        '@type' => 'MonetaryAmount',
        'currency' => $job_post->salary_currency ?? 'BDT',
        'value' => [
            '@type' => 'QuantitativeValue',
            'unitText' => $job_post->salary_period === 'yearly' ? 'YEAR' : 'MONTH',
        ],
    ];
    if ($job_post->salary_min && $job_post->salary_max) {
        $jobSchema['baseSalary']['value']['minValue'] = $job_post->salary_min;
        $jobSchema['baseSalary']['value']['maxValue'] = $job_post->salary_max;
    } elseif ($job_post->salary_min) {
        $jobSchema['baseSalary']['value']['value'] = $job_post->salary_min;
    } else {
        $jobSchema['baseSalary']['value']['value'] = $job_post->salary_max;
    }
}

if ($job_post->workplace_type === 'remote') {
    $jobSchema['jobLocationType'] = 'TELECOMMUTE';
    $jobSchema['applicantLocationRequirements'] = [
        '@type' => 'Country',
        'name' => 'Bangladesh',
    ];
}

if ($job_post->education) {
    $jobSchema['educationRequirements'] = [
        '@type' => 'EducationalOccupationalCredential',
        'credentialCategory' => $job_post->education,
    ];
}

if ($job_post->experience_min) {
    $jobSchema['experienceRequirements'] = [
        '@type' => 'OccupationalExperienceRequirements',
        'monthsOfExperience' => $job_post->experience_min * 12,
    ];
}

if ($job_post->skills) {
    $jobSchema['skills'] = $job_post->skills;
}

if ($job_post->vacancy_count) {
    $jobSchema['totalJobOpenings'] = $job_post->vacancy_count;
}

if ($job_post->application_url) {
    $jobSchema['directApply'] = true;
}

if ($job_post->latitude && $job_post->longitude) {
    $jobSchema['jobLocation']['geo'] = [
        '@type' => 'GeoCoordinates',
        'latitude' => $job_post->latitude,
        'longitude' => $job_post->longitude,
    ];
}

$jobSchema['speakable'] = [
    '@type' => 'SpeakableSpecification',
    'cssSelector' => ['[itemprop="title"]', '[itemprop="description"]', '.job-summary'],
];

$seoSvc = app(\App\Services\SeoService::class);
$breadcrumbSchema = $seoSvc->getBreadcrumbSchema([
    'চাকরি' => route('jobs.index'),
    $job_post->title => route('jobs.show', $job_post->slug),
]);
@endphp
<script type="application/ld+json">{!! json_encode($jobSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')

<main class="max-w-container-max mx-auto px-gutter py-stack-lg">

  {{-- Breadcrumb --}}
  <nav class="mb-6 flex items-center gap-2 text-sm text-on-surface-variant font-label-caps text-label-caps">
    <a href="{{ route('home') }}" class="hover:text-primary transition-colors">হোম</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <a href="{{ route('jobs.index') }}" class="hover:text-primary transition-colors">চাকরি</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-on-surface">{{ Str::limit($job_post->title, 40) }}</span>
  </nav>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Main Content --}}
    <div class="lg:col-span-2">
      <article>
        {{-- Header --}}
        <div class="bg-surface-container-lowest border border-subtle rounded-lg p-6 mb-6">
          <div class="flex items-start gap-4">
            @if($job_post->company_logo)
            <img src="{{ asset('storage/' . $job_post->company_logo) }}" alt="{{ $job_post->company_name }}" class="w-16 h-16 object-contain rounded border border-subtle flex-shrink-0">
            @else
            <div class="w-16 h-16 bg-primary/5 rounded border border-subtle flex items-center justify-center flex-shrink-0">
              <span class="material-symbols-outlined text-primary/50 text-[32px]">business</span>
            </div>
            @endif
            <div class="min-w-0">
              <div class="flex flex-wrap items-center gap-2 mb-2">
                @if($job_post->is_urgent)
                <span class="text-[11px] font-bold bg-red-100 text-red-700 px-2 py-0.5 rounded-full">জরুরি নিয়োগ</span>
                @endif
                @if($job_post->is_featured)
                <span class="text-[11px] font-bold bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">ফিচার্ড</span>
                @endif
                <span class="text-[11px] font-bold bg-primary/10 text-primary px-2 py-0.5 rounded-full">{{ $job_post->job_sector }}</span>
              </div>
              <h1 itemprop="title" class="font-headline-lg text-xl md:text-2xl text-on-surface leading-tight">{{ $job_post->title }}</h1>
              <p class="text-on-surface-variant mt-1 text-base">{{ $job_post->company_name }}</p>
            </div>
          </div>

          {{-- Quick Info Grid --}}
          <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-5 pt-5 border-t border-subtle">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-[20px]">work</span>
              <div>
                <p class="text-[11px] text-on-surface-variant uppercase">ধরন</p>
                <p class="text-sm font-semibold text-on-surface">{{ $job_post->job_type_label }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-[20px]">location_on</span>
              <div>
                <p class="text-[11px] text-on-surface-variant uppercase">অবস্থান</p>
                <p class="text-sm font-semibold text-on-surface">{{ $job_post->district ?? $job_post->division ?? 'বাংলাদেশ' }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-[20px]">payments</span>
              <div>
                <p class="text-[11px] text-on-surface-variant uppercase">বেতন</p>
                <p class="text-sm font-semibold text-on-surface">{{ $job_post->salary_range }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-primary text-[20px]">laptop_mac</span>
              <div>
                <p class="text-[11px] text-on-surface-variant uppercase">কর্মস্থল</p>
                <p class="text-sm font-semibold text-on-surface">{{ $job_post->workplace_type_label }}</p>
              </div>
            </div>
          </div>
        </div>

        {{-- Job Summary (AEO — voice-assistant readable) --}}
        <div class="job-summary bg-primary/5 border border-primary/10 rounded-lg p-5 mb-6">
          <div class="flex items-center gap-2 mb-3">
            <span class="material-symbols-outlined text-primary text-[20px]">summarize</span>
            <h2 itemprop="description" class="font-bold text-on-surface">সংক্ষেপে</h2>
          </div>
          <p class="text-on-surface-variant text-sm leading-relaxed">
            {{ $job_post->company_name }}-এ {{ $job_post->title }} পদে নিয়োগ দেওয়া হবে।
            @if($job_post->vacancy_count) মোট {{ $job_post->vacancy_count }}টি পদ শূন্য। @endif
            চাকরির ধরন {{ $job_post->job_type_label }}{{ $job_post->workplace_type !== 'onsite' ? ' (' . $job_post->workplace_type_label . ')' : '' }}।
            @if($job_post->salary_min || $job_post->salary_max) বেতন {{ $job_post->salary_range }}। @endif
            @if($job_post->education) শিক্ষাগত যোগ্যতা: {{ $job_post->education }}। @endif
            @if($job_post->experience_min || $job_post->experience_max) অভিজ্ঞতা: {{ $job_post->experience_label }}। @endif
            @if($job_post->application_deadline) আবেদনের শেষ তারিখ {{ $job_post->application_deadline->format('d M, Y') }}। @endif
          </p>
        </div>

        {{-- Description --}}
        <section class="bg-surface-container-lowest border border-subtle rounded-lg p-6 mb-6">
          <h2 class="font-bold text-on-surface text-lg mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">description</span> চাকরির বিবরণ
          </h2>
          <div class="prose prose-sm max-w-none text-on-surface-variant leading-relaxed whitespace-pre-line">{{ $job_post->description }}</div>
        </section>

        {{-- Responsibilities --}}
        @if($job_post->responsibilities)
        <section class="bg-surface-container-lowest border border-subtle rounded-lg p-6 mb-6">
          <h2 class="font-bold text-on-surface text-lg mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">task_alt</span> দায়িত্বসমূহ
          </h2>
          <div class="text-on-surface-variant text-sm leading-relaxed whitespace-pre-line">{{ $job_post->responsibilities }}</div>
        </section>
        @endif

        {{-- Requirements --}}
        @if($job_post->requirements)
        <section class="bg-surface-container-lowest border border-subtle rounded-lg p-6 mb-6">
          <h2 class="font-bold text-on-surface text-lg mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">checklist</span> যোগ্যতা ও শর্তাবলী
          </h2>
          <div class="text-on-surface-variant text-sm leading-relaxed whitespace-pre-line">{{ $job_post->requirements }}</div>
        </section>
        @endif

        {{-- Benefits --}}
        @if($job_post->benefits)
        <section class="bg-surface-container-lowest border border-subtle rounded-lg p-6 mb-6">
          <h2 class="font-bold text-on-surface text-lg mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">redeem</span> সুযোগ-সুবিধা
          </h2>
          <div class="text-on-surface-variant text-sm leading-relaxed whitespace-pre-line">{{ $job_post->benefits }}</div>
        </section>
        @endif

        {{-- Share --}}
        <div class="bg-surface-container-lowest border border-subtle rounded-lg p-5">
          <h3 class="font-bold text-on-surface text-sm mb-3">এই চাকরিটি শেয়ার করুন</h3>
          <div class="flex gap-3">
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('jobs.show', $job_post->slug)) }}" target="_blank" rel="noopener"
               class="w-10 h-10 flex items-center justify-center rounded-full bg-[#1877F2] text-white hover:opacity-90 transition-opacity" aria-label="ফেসবুকে শেয়ার করুন">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('jobs.show', $job_post->slug)) }}&text={{ urlencode($job_post->title . ' — ' . $job_post->company_name) }}" target="_blank" rel="noopener"
               class="w-10 h-10 flex items-center justify-center rounded-full bg-black text-white hover:opacity-90 transition-opacity" aria-label="টুইটারে শেয়ার করুন">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a href="https://api.whatsapp.com/send?text={{ urlencode($job_post->title . ' — ' . $job_post->company_name . ' ' . route('jobs.show', $job_post->slug)) }}" target="_blank" rel="noopener"
               class="w-10 h-10 flex items-center justify-center rounded-full bg-[#25D366] text-white hover:opacity-90 transition-opacity" aria-label="হোয়াটসঅ্যাপে শেয়ার করুন">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
          </div>
        </div>
      </article>
    </div>

    {{-- Sidebar --}}
    <aside class="lg:col-span-1">
      {{-- Apply Box --}}
      <div class="bg-surface-container-lowest border border-subtle rounded-lg p-5 mb-6 sticky top-4">
        <h3 class="font-bold text-on-surface mb-4">আবেদনের তথ্য</h3>

        @if($job_post->isExpired())
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4 text-center">
          <span class="material-symbols-outlined text-red-500 text-[28px]">event_busy</span>
          <p class="text-red-700 font-bold text-sm mt-1">আবেদনের সময়সীমা শেষ</p>
        </div>
        @endif

        <div class="space-y-3 text-sm">
          @if($job_post->application_deadline)
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">schedule</span>
            <div>
              <p class="text-on-surface-variant text-xs">শেষ তারিখ</p>
              <p class="font-semibold text-on-surface">{{ $job_post->application_deadline->format('d M, Y') }}</p>
            </div>
          </div>
          @endif
          @if($job_post->vacancy_count)
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">group</span>
            <div>
              <p class="text-on-surface-variant text-xs">পদ সংখ্যা</p>
              <p class="font-semibold text-on-surface">{{ $job_post->vacancy_count }}টি</p>
            </div>
          </div>
          @endif
          @if($job_post->education)
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">school</span>
            <div>
              <p class="text-on-surface-variant text-xs">শিক্ষা</p>
              <p class="font-semibold text-on-surface">{{ $job_post->education }}</p>
            </div>
          </div>
          @endif
          <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px]">trending_up</span>
            <div>
              <p class="text-on-surface-variant text-xs">অভিজ্ঞতা</p>
              <p class="font-semibold text-on-surface">{{ $job_post->experience_label }}</p>
            </div>
          </div>
          @if($job_post->skills)
          <div class="flex items-start gap-2">
            <span class="material-symbols-outlined text-on-surface-variant text-[18px] mt-0.5">psychology</span>
            <div>
              <p class="text-on-surface-variant text-xs">দক্ষতা</p>
              <div class="flex flex-wrap gap-1 mt-1">
                @foreach(explode(',', $job_post->skills) as $skill)
                <span class="text-[11px] bg-primary/10 text-primary px-2 py-0.5 rounded-full">{{ trim($skill) }}</span>
                @endforeach
              </div>
            </div>
          </div>
          @endif
        </div>

        @if(!$job_post->isExpired())
        <div class="mt-5 space-y-2">
          @if($job_post->application_url)
          <a href="{{ $job_post->application_url }}" target="_blank" rel="noopener"
             class="block w-full bg-primary text-white text-center py-3 rounded-lg font-bold text-sm hover:opacity-90 transition-all">
            <span class="material-symbols-outlined text-[18px] align-middle mr-1">open_in_new</span> এখনই আবেদন করুন
          </a>
          @endif
          @if($job_post->application_email)
          <a href="mailto:{{ $job_post->application_email }}?subject=আবেদন: {{ $job_post->title }}"
             class="block w-full bg-surface-container text-on-surface text-center py-3 rounded-lg font-bold text-sm hover:bg-surface-container-high transition-all border border-subtle">
            <span class="material-symbols-outlined text-[18px] align-middle mr-1">mail</span> ইমেইলে আবেদন
          </a>
          @endif
        </div>
        @endif

        <div class="mt-4 pt-4 border-t border-subtle text-xs text-on-surface-variant flex items-center gap-2">
          <span class="material-symbols-outlined text-[14px]">visibility</span>
          {{ number_format($job_post->views) }} বার দেখা হয়েছে
        </div>
      </div>

      {{-- Related Jobs --}}
      @if($relatedJobs->count() > 0)
      <div class="bg-surface-container-lowest border border-subtle rounded-lg p-5">
        <h3 class="font-bold text-on-surface mb-4">সম্পর্কিত চাকরি</h3>
        <div class="space-y-3">
          @foreach($relatedJobs as $rJob)
          <a href="{{ route('jobs.show', $rJob) }}" class="block group">
            <h4 class="text-sm font-semibold text-on-surface group-hover:text-primary transition-colors line-clamp-2">{{ $rJob->title }}</h4>
            <p class="text-xs text-on-surface-variant mt-0.5">{{ $rJob->company_name }}</p>
            <div class="flex items-center gap-2 mt-1 text-xs text-on-surface-variant">
              <span>{{ $rJob->job_type_label }}</span>
              <span>•</span>
              <span>{{ $rJob->salary_range }}</span>
            </div>
          </a>
          @if(!$loop->last)<div class="border-t border-subtle"></div>@endif
          @endforeach
        </div>
      </div>
      @endif
    </aside>

  </div>
</main>

@endsection
