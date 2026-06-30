@extends('public.layout')

@section('title', $metaTags['title'] ?? 'সাইটম্যাপ - সজীব নিউজ')
@section('description', $metaTags['description'] ?? '')
@section('keywords', $metaTags['keywords'] ?? '')
@section('canonical', $metaTags['canonical'] ?? '')
@section('og_title', $metaTags['og_title'] ?? '')
@section('og_url', $metaTags['og_url'] ?? '')

@push('styles')
<style>
    .bengali-text { font-family: 'Noto Serif Bengali', serif; }
    .sitemap-link {
        position: relative;
        transition: all 0.2s ease;
    }
    .sitemap-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 1px;
        background-color: #004e9f;
        transition: width 0.3s ease;
    }
    .sitemap-link:hover::after { width: 100%; }

    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.5s ease-out;
    }
</style>
@endpush

@section('content')

<main class="pb-16 px-4 md:px-6 max-w-[1200px] mx-auto min-h-screen">

    {{-- Page Header --}}
    <div class="mb-12 border-b border-outline-variant pb-4 pt-8">
        <h1 class="text-3xl md:text-4xl font-bold text-on-surface bengali-text">সাইটম্যাপ</h1>
        <p class="text-base text-on-surface-variant mt-1 bengali-text">
            সজীব নিউজ পোর্টালের সকল খবরের বিভাগ এবং তথ্যের একটি সংক্ষিপ্ত তালিকা।
        </p>
    </div>

    {{-- Bento Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Card 1: বিভাগসমূহ --}}
        <section class="fade-in bg-white p-6 rounded-xl shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 mb-4 text-primary">
                <span class="material-symbols-outlined">category</span>
                <h2 class="text-xl font-semibold bengali-text">বিভাগসমূহ</h2>
            </div>
            <ul class="space-y-2">
                @forelse($categories as $category)
                <li>
                    <a href="{{ route('category.show', $category->slug) }}"
                       class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        {{ $category->name }}
                    </a>
                </li>
                @empty
                <li class="text-sm text-on-surface-variant bengali-text">কোনো বিভাগ নেই</li>
                @endforelse
            </ul>
        </section>

        {{-- Card 2: ব্যবহারকারী --}}
        <section class="fade-in bg-white p-6 rounded-xl shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 mb-4 text-primary">
                <span class="material-symbols-outlined">person</span>
                <h2 class="text-xl font-semibold bengali-text">ব্যবহারকারী</h2>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('login') }}" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        লগইন (Login)
                    </a>
                </li>
                <li>
                    <a href="{{ route('register') }}" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        রেজিস্ট্রেশন (Register)
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        আমার প্রোফাইল (My Profile)
                    </a>
                </li>
                <li>
                    <a href="{{ route('news.search') }}" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        সংবাদ অনুসন্ধান (Search)
                    </a>
                </li>
            </ul>
        </section>

        {{-- Card 3: প্রতিষ্ঠান --}}
        <section class="fade-in bg-white p-6 rounded-xl shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 mb-4 text-primary">
                <span class="material-symbols-outlined">corporate_fare</span>
                <h2 class="text-xl font-semibold bengali-text">প্রতিষ্ঠান</h2>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('about') }}" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        আমাদের সম্পর্কে (About Us)
                    </a>
                </li>
                <li>
                    <a href="{{ route('contact') }}" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        যোগাযোগ (Contact)
                    </a>
                </li>
                <li>
                    <a href="{{ route('live.index') }}" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        লাইভ স্ট্রিম (Live)
                    </a>
                </li>
                <li>
                    <a href="{{ route('sitemap.xml') }}" target="_blank" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        XML সাইটম্যাপ
                    </a>
                </li>
            </ul>
        </section>

        {{-- Card 4: আইনি --}}
        <section class="fade-in bg-white p-6 rounded-xl shadow-sm border border-outline-variant/30">
            <div class="flex items-center gap-2 mb-4 text-primary">
                <span class="material-symbols-outlined">gavel</span>
                <h2 class="text-xl font-semibold bengali-text">আইনি</h2>
            </div>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('privacy') }}" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        গোপনীয়তা নীতি (Privacy Policy)
                    </a>
                </li>
                <li>
                    <a href="{{ route('terms') }}" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        ব্যবহারের শর্তাবলী (Terms of Service)
                    </a>
                </li>
                <li>
                    <a href="{{ route('llm.txt') }}" target="_blank" class="sitemap-link text-base text-on-surface-variant hover:text-primary bengali-text">
                        LLM তথ্য ফাইল (llm.txt)
                    </a>
                </li>
            </ul>
        </section>

    </div>

    {{-- Featured Section --}}
    <div class="mt-12 grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Today's News CTA --}}
        <div class="relative overflow-hidden rounded-xl h-64 group cursor-pointer shadow-lg">
            <div class="absolute inset-0 bg-primary opacity-90 transition-opacity group-hover:opacity-100"></div>
            {{-- Big bolt icon watermark --}}
            <div class="absolute right-0 top-0 h-full w-1/2 opacity-20 flex items-center justify-end pr-4">
                <span class="material-symbols-outlined text-white" style="font-size: 160px;">bolt</span>
            </div>
            <div class="relative z-10 p-8 flex flex-col justify-end h-full">
                <span class="material-symbols-outlined text-white text-4xl mb-4">newspaper</span>
                <h3 class="text-2xl md:text-3xl font-bold text-white bengali-text">আজকের তাজা খবর</h3>
                <p class="text-base text-blue-100 mt-1 bengali-text">
                    মুহূর্তের খবর মুহূর্তে পেতে ভিজিট করুন আমাদের হোমপেজ।
                </p>
                <a href="{{ route('home') }}"
                   class="mt-4 self-start px-5 py-2 bg-white text-primary font-bold text-sm rounded-lg hover:bg-blue-50 transition-colors bengali-text">
                    হোমপেজে যান
                </a>
            </div>
        </div>

        {{-- Newsletter Subscribe --}}
        <div class="bg-surface-container-high p-8 rounded-xl border-2 border-dashed border-outline-variant flex flex-col justify-center items-center text-center">
            <h3 class="text-xl md:text-2xl font-semibold text-on-surface mb-5 bengali-text">
                আপনি কি আমাদের সংবাদ পেতে চান?
            </h3>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex w-full max-w-md gap-2">
                @csrf
                <input type="email" name="email" required
                       class="flex-grow px-4 py-2 rounded-lg border border-outline focus:ring-2 focus:ring-primary focus:border-transparent outline-none text-base bengali-text"
                       placeholder="আপনার ইমেইল দিন">
                <button type="submit"
                        class="px-6 py-2 bg-secondary text-white rounded-lg font-bold text-sm uppercase hover:opacity-90 transition-all bengali-text">
                    সাবস্ক্রাইব
                </button>
            </form>
            <p class="mt-3 text-xs text-on-surface-variant bengali-text">
                আমরা আপনার তথ্যের সুরক্ষা নিশ্চিত করি।
            </p>
        </div>

    </div>

</main>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.fade-in');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 + index * 100);
        });
    });
</script>
@endpush

@endsection
