<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>নতুন খবর: {{ $news->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Arial, sans-serif; background: #f4f4f4; color: #1c1b1b; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,.08); }

        /* Header */
        .header { background: #004e9f; padding: 24px 32px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: .5px; }
        .header p { color: rgba(255,255,255,.75); font-size: 13px; margin-top: 4px; }

        /* Featured image */
        .featured-image { width: 100%; height: 260px; object-fit: cover; display: block; }
        .featured-placeholder { width: 100%; height: 200px; background: #dfe8ff; display: flex; align-items: center; justify-content: center; }

        /* Category badge */
        .badge-wrap { padding: 20px 32px 0; }
        .category-badge { display: inline-block; background: #004e9f; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 999px; text-transform: uppercase; letter-spacing: .05em; }
        .breaking-badge { display: inline-block; background: #ab3500; color: #fff; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 999px; text-transform: uppercase; letter-spacing: .05em; margin-left: 6px; }

        /* Body */
        .body { padding: 20px 32px 28px; }
        .news-title { font-size: 22px; font-weight: 700; line-height: 1.35; color: #1c1b1b; margin-bottom: 12px; }
        .news-excerpt { font-size: 15px; line-height: 1.7; color: #414753; margin-bottom: 24px; }
        .meta { font-size: 12px; color: #727784; margin-bottom: 24px; display: flex; gap: 16px; flex-wrap: wrap; }
        .meta span { display: flex; align-items: center; gap: 4px; }

        /* CTA */
        .cta-wrap { text-align: center; margin-bottom: 28px; }
        .cta-btn { display: inline-block; background: #004e9f; color: #ffffff !important; font-size: 15px; font-weight: 700; padding: 14px 36px; border-radius: 10px; text-decoration: none; }

        /* Divider */
        hr { border: none; border-top: 1px solid #e5e2e1; margin: 0 32px; }

        /* Footer */
        .footer { padding: 20px 32px; text-align: center; }
        .footer p { font-size: 12px; color: #727784; line-height: 1.7; }
        .footer a { color: #004e9f; text-decoration: underline; }
        .unsubscribe { margin-top: 12px; font-size: 11px; color: #9e9e9e; }
        .unsubscribe a { color: #9e9e9e; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <h1>{{ config('app.name', 'সজীব নিউজ') }}</h1>
        <p>আপনার বিশ্বস্ত সংবাদ উৎস</p>
    </div>

    {{-- Featured Image --}}
    @if($news->featured_image)
    <img class="featured-image"
         src="{{ asset('storage/' . $news->featured_image) }}"
         alt="{{ $news->title }}">
    @endif

    {{-- Category & Breaking badges --}}
    <div class="badge-wrap">
        @if($news->category)
        <span class="category-badge">{{ $news->category->name }}</span>
        @endif
        @if($news->is_breaking)
        <span class="breaking-badge">🔴 ব্রেকিং নিউজ</span>
        @endif
    </div>

    {{-- Body --}}
    <div class="body">
        <h2 class="news-title">{{ $news->title }}</h2>

        <div class="meta">
            <span>📅 {{ $news->published_at?->format('d M Y, H:i') }}</span>
            @if($news->author)
            <span>✍️ {{ $news->author->name }}</span>
            @endif
            @if($news->category)
            <span>📂 {{ $news->category->name }}</span>
            @endif
        </div>

        @if($news->excerpt)
        <p class="news-excerpt">{{ $news->excerpt }}</p>
        @else
        <p class="news-excerpt">{{ Str::limit(strip_tags($news->content ?? ''), 200) }}</p>
        @endif

        <div class="cta-wrap">
            <a class="cta-btn" href="{{ route('news.show', $news->slug) }}">
                সম্পূর্ণ খবর পড়ুন →
            </a>
        </div>
    </div>

    <hr>

    {{-- Footer --}}
    <div class="footer">
        <p>
            আপনি <strong>{{ config('app.name', 'সজীব নিউজ') }}</strong> নিউজলেটারে সাবস্ক্রাইব করেছেন।<br>
            <a href="{{ url('/') }}">{{ url('/') }}</a>
        </p>
        <p class="unsubscribe">
            আর ইমেইল পেতে না চাইলে
            <a href="{{ url('/newsletter/unsubscribe?email=' . urlencode($subscriber->email)) }}">এখানে ক্লিক করুন</a>।
        </p>
    </div>

</div>
</body>
</html>
