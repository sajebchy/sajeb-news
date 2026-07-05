<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($news as $item)
    <url>
        <loc>{{ route('news.show', $item->slug) }}</loc>
        <news:news>
            <news:publication>
                <news:name>{{ htmlspecialchars($siteName, ENT_XML1, 'UTF-8') }}</news:name>
                <news:language>bn</news:language>
            </news:publication>
            <news:publication_date>{{ $item->published_at->toIso8601String() }}</news:publication_date>
            <news:title>{{ htmlspecialchars($item->title, ENT_XML1, 'UTF-8') }}</news:title>
@if($item->meta_keywords)
            <news:keywords>{{ htmlspecialchars($item->meta_keywords, ENT_XML1, 'UTF-8') }}</news:keywords>
@endif
        </news:news>
@if($item->featured_image)
        <image:image>
            <image:loc>{{ asset('storage/' . $item->featured_image) }}</image:loc>
            <image:title>{{ htmlspecialchars($item->title, ENT_XML1, 'UTF-8') }}</image:title>
        </image:image>
@endif
    </url>
@endforeach
</urlset>
