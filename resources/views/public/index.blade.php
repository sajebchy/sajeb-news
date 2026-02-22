@extends('public.layout')

@section('title', 'Sajeb NEWS - বাংলাদেশী নিউজ পোর্টাল')

@section('schema')
    @php
        $schemaSettings = \App\Models\SchemaSetting::getInstance();
    @endphp
    
    <!-- Breadcrumb Schema for Homepage -->
    @if($schemaSettings->enable_breadcrumb_schema)
        <script type="application/ld+json">
        {!! json_encode(\App\Services\SchemaGeneratorService::breadcrumbSchema([
            ['name' => 'হোম', 'url' => route('home')]
        ])) !!}
        </script>
    @endif
@endsection

@section('content')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
        background: #f8f8f7;
        color: #1b1b18;
    }

    /* News Ticker - At the bottom */
    .news-ticker {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: white;
        padding: 16px 0;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 100;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);
    }

    .ticker-header {
        display: flex;
        align-items: center;
        gap: 16px;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .ticker-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.1);
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        border-left: 3px solid #ef4444;
    }

    .ticker-play-icon {
        color: #ef4444;
        font-size: 16px;
    }

    .marquee-content {
        flex: 1;
        overflow: hidden;
        position: relative;
    }

    .marquee-text {
        display: flex;
        gap: 30px;
        animation: marquee-scroll 50s linear infinite;
        white-space: nowrap;
    }

    @keyframes marquee-scroll {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }

    .marquee-text:hover {
        animation-play-state: paused;
    }

    .ticker-item {
        display: flex;
        align-items: center;
        gap: 12px;
        color: white;
        text-decoration: none;
        padding: 0 15px;
        transition: color 0.3s;
    }

    .ticker-item:hover {
        color: #60a5fa;
    }

    .ticker-bullet {
        display: inline-block;
        color: #ef4444;
        font-weight: bold;
        font-size: 14px;
    }

    /* Main Container */
    .homepage-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
        padding-bottom: 140px;
    }

    /* Live Stream Button - Floating */
    .pulsate-wrap {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 999;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .puls-content-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pulsate {
        position: relative;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pulsate.live .ringring {
        animation: pulsate-ring 1.5s ease-out infinite;
    }

    .pulsate.offline .ringring {
        animation: blink-ring 1s ease-in-out infinite;
    }

    .ringring {
        position: absolute;
        border: 3px solid #ef4444;
        border-radius: 50%;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }

    @keyframes pulsate-ring {
        0% {
            width: 100%;
            height: 100%;
            opacity: 1;
            border-color: #ef4444;
        }
        100% {
            width: 140%;
            height: 140%;
            opacity: 0;
            border-color: rgba(239, 68, 68, 0);
        }
    }

    @keyframes blink-ring {
        0%, 49% {
            opacity: 1;
            border-color: #333333;
        }
        50%, 100% {
            opacity: 0.3;
            border-color: #555555;
        }
    }

    .circle {
        position: absolute;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .pulsate.offline .circle {
        background: #333333;
    }

    .live-text {
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        background: white;
        padding: 8px 12px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        color: #1b1b18;
        display: none;
    }

    .pulsate-wrap:hover .live-text {
        display: inline-block;
    }

    .pulsate-wrap a {
        text-decoration: none;
        color: inherit;
    }

    @media (max-width: 768px) {
        .pulsate-wrap {
            bottom: 20px;
            right: 20px;
        }

        .live-text {
            font-size: 12px;
            padding: 6px 10px;
        }

        .circle {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }

        .ringring {
            border-width: 2px;
        }
    }


    /* Subscribe Banner */
    .subscribe-banner {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        padding: 32px;
        border-radius: 12px;
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .subscribe-banner h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .subscribe-banner p {
        font-size: 14px;
        opacity: 0.95;
        margin-bottom: 0;
    }

    .subscribe-btn {
        background: white;
        color: #3b82f6;
        border: none;
        padding: 12px 28px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .subscribe-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Push Modal Styles */
    .push-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        animation: fadeIn 0.3s ease-in-out;
    }

    .push-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        animation: slideUp 0.4s ease-out;
    }

    .push-modal-overlay.active {
        display: block;
    }

    .push-modal.active {
        display: block;
    }

    .push-modal-content {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 90vw;
        animation: modalPopIn 0.4s ease-out;
    }

    /* Safari Style Icon */
    .safari-style-header {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        padding: 24px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80px;
    }

    .safari-style-icon {
        width: 64px;
        height: 64px;
        background: white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 36px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        position: relative;
    }

    .safari-style-icon::before {
        content: '📬';
        font-size: 40px;
        display: block;
    }

    /* Modal Title Section */
    .safari-style-title {
        padding: 28px 24px 16px;
        text-align: center;
    }

    .modal-title {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 8px 0;
        color: #1f2937;
        line-height: 1.3;
    }

    .modal-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }

    /* Modal Description */
    .safari-style-description {
        padding: 0 24px 24px;
        text-align: center;
    }

    .safari-style-description p {
        font-size: 15px;
        color: #4b5563;
        line-height: 1.6;
        margin: 0;
    }

    /* Modal Buttons */
    .modal-buttons {
        display: flex;
        gap: 12px;
        padding: 20px 24px 24px;
        border-top: 1px solid #e5e7eb;
    }

    .modal-btn {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .modal-btn-cancel {
        background: #f3f4f6;
        color: #374151;
    }

    .modal-btn-cancel:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    .modal-btn-confirm {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
    }

    .modal-btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
    }

    .modal-btn-confirm:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Floating Subscribe Button */
    .floating-subscribe-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        border: none;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        cursor: pointer;
        font-size: 28px;
        transition: all 0.3s ease;
        z-index: 500;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .floating-subscribe-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
    }

    .floating-subscribe-btn:active {
        transform: scale(0.95);
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translate(-50%, -40%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }

    @keyframes modalPopIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .push-modal-content {
            max-width: 95vw;
        }

        .modal-title {
            font-size: 20px;
        }

        .floating-subscribe-btn {
            bottom: 16px;
            right: 16px;
            width: 54px;
            height: 54px;
            font-size: 24px;
        }

        .modal-buttons {
            flex-direction: column;
        }

        .modal-btn {
            width: 100%;
        }
    }/* Section Titles */
    .section-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 24px;
        color: #1b1b18;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Categories Section */
    .categories-section {
        margin-bottom: 48px;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 16px;
    }

    .category-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none;
        color: #1b1b18;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .category-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        background: #f0f7ff;
    }

    .category-icon {
        font-size: 32px;
        line-height: 1;
    }

    .category-name {
        font-weight: 700;
        font-size: 14px;
        line-height: 1.2;
    }

    .category-count {
        font-size: 12px;
        color: #706f6c;
    }

    /* Featured News Section */
    .featured-section {
        margin-bottom: 48px;
    }

    .featured-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .featured-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .featured-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }

    .featured-card-image {
        width: 100%;
        height: 240px;
        object-fit: cover;
        display: block;
    }

    .featured-card-content {
        padding: 20px;
    }

    .featured-card-category {
        display: inline-block;
        background: #eff6ff;
        color: #1e40af;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .featured-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1b1b18;
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .featured-card a {
        text-decoration: none;
        color: inherit;
    }

    .featured-card a:hover .featured-card-title {
        color: #3b82f6;
    }

    .featured-card-meta {
        font-size: 13px;
        color: #706f6c;
    }

    /* Main Content Area */
    .content-wrapper {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 32px;
        margin-bottom: 48px;
    }

    /* News Grid */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
    }

    .news-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
    }

    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }

    .news-card-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
    }

    .news-card-content {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .news-card-category {
        display: inline-block;
        background: #fef3c7;
        color: #92400e;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 8px;
        width: fit-content;
    }

    .news-card-title {
        font-size: 14px;
        font-weight: 600;
        color: #1b1b18;
        margin-bottom: 8px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .news-card a {
        text-decoration: none;
        color: inherit;
    }

    .news-card a:hover .news-card-title {
        color: #3b82f6;
    }

    .news-card-time {
        font-size: 12px;
        color: #706f6c;
    }

    /* Sidebar */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .sidebar-widget {
        background: white;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .widget-title {
        font-size: 16px;
        font-weight: 700;
        color: #1b1b18;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e5e5e0;
    }

    /* Categories Widget */
    .categories-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .category-item {
        padding: 12px 16px;
        background: #f8f8f7;
        border-radius: 6px;
        text-decoration: none;
        color: #1b1b18;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-item:hover {
        background: #3b82f6;
        color: white;
    }

    .category-count {
        font-size: 12px;
        opacity: 0.7;
        background: rgba(0,0,0,0.05);
        padding: 2px 8px;
        border-radius: 3px;
    }

    .category-item:hover .category-count {
        background: rgba(255,255,255,0.2);
    }

    /* Newsletter Widget */
    .newsletter-form {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .newsletter-input {
        padding: 12px 14px;
        border: 1px solid #e5e5e0;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s;
        font-family: inherit;
    }

    .newsletter-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .newsletter-btn {
        padding: 12px 14px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-family: inherit;
    }

    .newsletter-btn:hover {
        background: #1e40af;
    }

    /* Trending News in Sidebar */
    .trending-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .trending-item {
        padding-bottom: 16px;
        border-bottom: 1px solid #e5e5e0;
        display: flex;
        gap: 12px;
    }

    .trending-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .trending-number {
        display: inline-block;
        width: 28px;
        height: 28px;
        background: #3b82f6;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 28px;
        font-weight: 700;
        font-size: 12px;
        flex-shrink: 0;
    }

    .trending-content {
        flex: 1;
    }

    .trending-title {
        font-size: 13px;
        font-weight: 600;
        color: #1b1b18;
        text-decoration: none;
        display: block;
        line-height: 1.3;
        margin-bottom: 4px;
    }

    .trending-title:hover {
        color: #3b82f6;
    }

    .trending-views {
        font-size: 11px;
        color: #706f6c;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 40px;
        flex-wrap: wrap;
    }

    .pagination-wrapper a, 
    .pagination-wrapper span {
        padding: 8px 12px;
        border: 1px solid #e5e5e0;
        border-radius: 4px;
        text-decoration: none;
        color: #1b1b18;
        transition: all 0.3s;
        font-size: 14px;
    }

    .pagination-wrapper a:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    .pagination-wrapper .active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    /* Empty State */
    .empty-state {
        grid-column: 1/-1;
        padding: 60px 20px;
        text-align: center;
        color: #706f6c;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content-wrapper {
            grid-template-columns: 1fr;
        }

        .sidebar {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            flex-direction: row;
            gap: 24px;
        }

        .subscribe-banner {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }

        .subscribe-banner p {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .featured-grid {
            grid-template-columns: 1fr;
        }

        .categories-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 12px;
        }

        .sidebar {
            flex-direction: column;
            grid-template-columns: 1fr;
        }

        .section-title {
            font-size: 18px;
        }

        .news-grid {
            grid-template-columns: 1fr;
        }

        .subscribe-banner {
            padding: 24px;
        }

        .homepage-container {
            padding-bottom: 160px;
        }

        .news-ticker {
            padding: 12px 0;
        }

        .ticker-badge {
            font-size: 11px;
            padding: 6px 10px;
        }

        .ticker-item {
            font-size: 13px;
            padding: 0 10px;
        }
    }
</style>

<div class="homepage-container">
    <!-- Live Stream Floating Button -->
    @php
        $activeLiveStream = \App\Models\LiveStream::where('status', 'active')
            ->where('start_time', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_time')
                      ->orWhere('end_time', '>', now());
            })
            ->first();
    @endphp

    <div class="pulsate-wrap" id="liveStreamContainer">
        <a href="{{ $activeLiveStream ? route('live.watch', $activeLiveStream->slug) : route('live.index') }}" class="puls-content-wrap">
            <div class="pulsate {{ $activeLiveStream ? 'live' : 'offline' }}" id="pulsateIndicator">
                <div class="ringring"></div>
                <div class="circle">
                    @if($activeLiveStream)
                        🔴
                    @else
                        📺
                    @endif
                </div>
            </div>
            <span class="live-text" id="liveText">
                @if($activeLiveStream)
                    Live TV
                @else
                    Live
                @endif
            </span>
        </a>
    </div>

    <!-- Subscribe Popup Modal -->
    <div id="push-modal-overlay" class="push-modal-overlay"></div>
    <div id="push-modal" class="push-modal">
        <div class="push-modal-content">
            <!-- Safari-style Notification Header -->
            <div class="safari-style-header">
                <div class="safari-style-icon"></div>
            </div>

            <!-- Modal Body -->
            <div class="safari-style-title">
                <h2 class="modal-title">📬 নতুন খবর সরাসরি পান!</h2>
                <p class="modal-subtitle">প্রতিদিন সর্বশেষ খবর সরাসরি আপনার কাছে</p>
            </div>

            <!-- Modal Description -->
            <div class="safari-style-description">
                <p>আমরা আপনাকে গুরুত্বপূর্ণ খবর, নতুন গল্প এবং ঘোষণা সম্পর্কে অবহিত রাখব।</p>
            </div>

            <!-- Modal Buttons -->
            <div class="modal-buttons">
                <button id="close-modal-btn" class="modal-btn modal-btn-cancel">এখন নয়</button>
                <button id="push-subscribe-btn" class="modal-btn modal-btn-confirm">সাবস্ক্রাইব করুন</button>
            </div>
        </div>
    </div>

    <!-- Floating Subscribe Button (for easy access) -->
    <button id="floating-subscribe-btn" class="floating-subscribe-btn" title="সাবস্ক্রাইব করুন">
        📬
    </button>

    <!-- Categories Section -->
    <section class="categories-section">
        <h2 class="section-title">📂 বিভাগসমূহ</h2>
        <div class="categories-grid">
            @php
                $categories = \App\Models\Category::where('is_active', true)
                    ->whereNull('parent_id')
                    ->limit(8)
                    ->get();
            @endphp
            @foreach($categories as $cat)
            <a href="{{ route('category.show', $cat->slug) }}" class="category-card">
                <div class="category-icon">{{ $cat->icon ?? '📁' }}</div>
                <div class="category-name">{{ $cat->name }}</div>
                <div class="category-count">{{ $cat->news()->count() }} খবর</div>
            </a>
            @endforeach
        </div>
    </section>

    <!-- Featured News Section -->
    @if($featured->count() > 0)
    <section class="featured-section">
        <h2 class="section-title">⭐ প্রধান খবর</h2>
        <div class="featured-grid">
            @foreach($featured->take(3) as $news)
            <div class="featured-card">
                <a href="{{ route('news.show', $news->slug) }}">
                    @if($news->featured_image)
                    <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}" class="featured-card-image">
                    @else
                    <div style="width: 100%; height: 240px; background: #e5e5e0; display: flex; align-items: center; justify-content: center; color: #706f6c; font-size: 14px;">
                        কোন ছবি নেই
                    </div>
                    @endif
                    <div class="featured-card-content">
                        <span class="featured-card-category">{{ $news->category->name ?? 'খবর' }}</span>
                        <h3 class="featured-card-title">{{ $news->title }}</h3>
                        <p style="font-size: 13px; color: #706f6c; line-height: 1.4; margin-bottom: 12px;">
                            {{ substr($news->excerpt ?? $news->content, 0, 100) }}...
                        </p>
                        <div class="featured-card-meta" style="display: flex; justify-content: space-between; align-items: center;">
                            <span>{{ $news->published_at?->diffForHumans() ?? 'নতুন' }}</span>
                            @if($news->author)
                            <a href="{{ route('author.show', $news->author->id) }}" style="color: #706f6c; text-decoration: none; font-size: 12px; font-weight: 500;" title="লেখক">লিখেছেন: {{ $news->author->name }}</a>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Homepage Top Ad (After Featured News) -->
    <div class="container" style="margin: 40px 0;">
        <div style="background: white; padding: 20px; border-radius: 8px; text-align: center;">
            <x-ad-placement placement="homepage_top" random="true" class="ad-homepage-top" />
        </div>
    </div>

    <!-- Main Content with Sidebar -->
    <div class="content-wrapper">
        <!-- News Grid -->
        <section>
            <h2 class="section-title">📰 সর্বশেষ খবর</h2>
            <div class="news-grid">
                @forelse($latest as $news)
                <div class="news-card">
                    <a href="{{ route('news.show', $news->slug) }}">
                        @if($news->featured_image)
                        <img src="{{ asset('storage/' . $news->featured_image) }}" alt="{{ $news->title }}" class="news-card-image">
                        @else
                        <div style="width: 100%; height: 180px; background: #e5e5e0; display: flex; align-items: center; justify-content: center; color: #706f6c; font-size: 13px;">
                            কোন ছবি
                        </div>
                        @endif
                        <div class="news-card-content">
                            <span class="news-card-category">{{ $news->category->name ?? 'খবর' }}</span>
                            <h3 class="news-card-title">{{ $news->title }}</h3>
                            <div style="font-size: 12px; color: #706f6c; margin-bottom: 8px;">
                                @if($news->author)
                                <a href="{{ route('author.show', $news->author->id) }}" style="color: #706f6c; text-decoration: none;" title="লেখক">{{ $news->author->name }}</a>
                                @endif
                            </div>
                            <div class="news-card-time">{{ $news->published_at?->diffForHumans() ?? 'নতুন' }}</div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="empty-state">
                    <p style="font-size: 16px;">কোনো খবর পাওয়া যাচ্ছে না</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($latest && $latest->hasPages())
            <div class="pagination-wrapper">
                {{ $latest->links() }}
            </div>
            @endif
        </section>

        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Categories Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">📂 বিভাগসমূহ</h3>
                <div class="categories-list">
                    @php
                        $categories = \App\Models\Category::withCount('news')->limit(8)->get();
                    @endphp
                    @forelse($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" class="category-item">
                        <span>{{ $category->name }}</span>
                        <span class="category-count">{{ $category->news_count }}</span>
                    </a>
                    @empty
                    <p style="color: #706f6c; font-size: 13px;">কোনো বিভাগ পাওয়া যাচ্ছে না</p>
                    @endforelse
                </div>
            </div>

            <!-- Trending Widget -->
            @if($trending && $trending->count() > 0)
            <div class="sidebar-widget">
                <h3 class="widget-title">📈 ট্রেন্ডিং নিউজ</h3>
                <div class="trending-list">
                    @foreach($trending->take(5) as $index => $news)
                    <div class="trending-item">
                        <div class="trending-number">{{ $index + 1 }}</div>
                        <div class="trending-content">
                            <a href="{{ route('news.show', $news->slug) }}" class="trending-title">{{ $news->title }}</a>
                            <div class="trending-views">{{ $news->views ?? 0 }} ভিউ</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Newsletter Widget -->
            <div class="sidebar-widget">
                <h3 class="widget-title">📧 নিউজলেটার</h3>
                <p style="font-size: 13px; color: #706f6c; margin-bottom: 16px;">সর্বশেষ খবর আপনার ইনবক্সে পান</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                    @csrf
                    <input type="email" name="email" class="newsletter-input" placeholder="আপনার ইমেইল" required>
                    <button type="submit" class="newsletter-btn">সাবস্ক্রাইব করুন</button>
                </form>
            </div>
        </aside>
    </div>

    <!-- News Ticker - নিউজ টিকার সবার নিচে -->
    @php
        $latestNews = \App\Models\News::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get();
    @endphp
    
    @if($latestNews->count() > 0)
    <div class="news-ticker">
        <div class="ticker-header">
            <div class="ticker-badge">
                <span class="ticker-play-icon"><i class="fa fa-play"></i></span>
                <span>শিরোনাম</span>
            </div>
            <div class="marquee-content">
                <div class="marquee-text">
                    @foreach($latestNews as $news)
                        <a href="{{ route('news.show', $news->slug) }}" class="ticker-item">
                            <span class="ticker-bullet">●</span>
                            <span>{{ $news->title }}</span>
                        </a>
                    @endforeach
                    @foreach($latestNews as $news)
                        <a href="{{ route('news.show', $news->slug) }}" class="ticker-item">
                            <span class="ticker-bullet">●</span>
                            <span>{{ $news->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    // Modal Management
    class SubscribeModalManager {
        constructor() {
            this.modal = document.getElementById('push-modal');
            this.overlay = document.getElementById('push-modal-overlay');
            this.subscribeBtn = document.getElementById('push-subscribe-btn');
            this.closeBtn = document.getElementById('close-modal-btn');
            this.floatingBtn = document.getElementById('floating-subscribe-btn');
            this.init();
        }

        init() {
            // Open modal when floating button is clicked
            this.floatingBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                this.openModal();
            });

            // Close modal when close button is clicked
            this.closeBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                this.closeModal();
            });

            // Close modal when overlay is clicked
            this.overlay?.addEventListener('click', (e) => {
                if (e.target === this.overlay) {
                    this.closeModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.modal?.classList.contains('active')) {
                    this.closeModal();
                }
            });

            // Setup subscribe button
            this.setupSubscribeButton();
        }

        openModal() {
            this.modal?.classList.add('active');
            this.overlay?.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        closeModal() {
            this.modal?.classList.remove('active');
            this.overlay?.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        setupSubscribeButton() {
            if (!this.subscribeBtn) return;

            // Check if manager is available
            const checkManager = setInterval(() => {
                if (window.PushNotificationManager) {
                    clearInterval(checkManager);
                    this.initPushNotifications();
                }
            }, 100);

            // Timeout after 2 seconds
            setTimeout(() => clearInterval(checkManager), 2000);
        }

        initPushNotifications() {
            const manager = new PushNotificationManager();
            
            // Check if browser supports push notifications
            if (!manager.isSupported()) {
                this.subscribeBtn.innerHTML = '<i class="fas fa-ban"></i> সাপোর্ট করে না';
                this.subscribeBtn.disabled = true;
                this.floatingBtn.style.display = 'none';
                return;
            }

            // Check if already subscribed
            this.checkIfSubscribed(manager);

            // Add click handler
            this.subscribeBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                this.subscribeBtn.disabled = true;
                this.subscribeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> অপেক্ষা করুন...';

                try {
                    const result = await manager.subscribe();
                    
                    if (result.success) {
                        this.subscribeBtn.innerHTML = '<i class="fas fa-check-circle"></i> সক্ষম হয়েছে!';
                        this.subscribeBtn.style.background = '#10b981';
                        this.subscribeBtn.style.color = 'white';
                        this.closeBtn.innerHTML = 'বন্ধ করুন';
                        
                        setTimeout(() => this.closeModal(), 2000);
                    } else {
                        this.showAlert('error', result.message || 'সাবস্ক্রিপশন ব্যর্থ হয়েছে');
                        this.subscribeBtn.disabled = false;
                        this.subscribeBtn.innerHTML = 'সাবস্ক্রাইব করুন';
                    }
                } catch (error) {
                    console.error('Subscribe error:', error);
                    this.showAlert('error', 'একটি ত্রুটি ঘটেছে। অনুগ্রহ করে পুনরায় চেষ্টা করুন।');
                    this.subscribeBtn.disabled = false;
                    this.subscribeBtn.innerHTML = 'সাবস্ক্রাইব করুন';
                }
            });
        }

        checkIfSubscribed(manager) {
            manager.isEnabled().then(enabled => {
                if (enabled) {
                    this.subscribeBtn.innerHTML = '<i class="fas fa-check-circle"></i> সক্ষম করা আছে';
                    this.subscribeBtn.style.background = '#10b981';
                    this.subscribeBtn.style.color = 'white';
                    this.subscribeBtn.disabled = true;
                    this.floatingBtn.style.opacity = '0.5';
                    this.floatingBtn.disabled = true;
                }
            });
        }

        showAlert(type, message) {
            const alertEl = document.createElement('div');
            alertEl.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: ${type === 'error' ? '#ef4444' : '#10b981'};
                color: white;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                max-width: 400px;
                font-size: 14px;
                animation: slideDown 0.3s ease-out;
            `;
            alertEl.textContent = message;
            document.body.appendChild(alertEl);
            
            setTimeout(() => {
                alertEl.style.opacity = '0';
                alertEl.style.transition = 'opacity 0.3s';
                setTimeout(() => alertEl.remove(), 300);
            }, 5000);
        }
    }

    // Initialize modal manager when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        new SubscribeModalManager();
    });

    // Live Stream Status Checker for Pulsate
    function checkLiveStreamStatus() {
        fetch('{{ route("api.live.active") }}', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const pulsateIndicator = document.getElementById('pulsateIndicator');
            const liveText = document.getElementById('liveText');
            const circles = document.querySelector('.circle');
            
            if (!pulsateIndicator) return;

            if (data.active) {
                // Activate Live
                pulsateIndicator.classList.remove('offline');
                pulsateIndicator.classList.add('live');
                
                if (circles) {
                    circles.innerHTML = '🔴';
                }
                if (liveText) {
                    liveText.textContent = 'Live TV';
                }
            } else {
                // Deactivate Live
                pulsateIndicator.classList.remove('live');
                pulsateIndicator.classList.add('offline');
                
                if (circles) {
                    circles.innerHTML = '📺';
                }
                if (liveText) {
                    liveText.textContent = 'Live';
                }
            }
        })
        .catch(error => console.log('Live stream check error:', error));
    }

    // Check live stream status on page load and then every 30 seconds
    document.addEventListener('DOMContentLoaded', function() {
        checkLiveStreamStatus();
        setInterval(checkLiveStreamStatus, 30000);
    });
</script>
@endsection
