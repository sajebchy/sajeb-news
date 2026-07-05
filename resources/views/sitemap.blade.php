<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($urls as $url)
    <url>
        <loc>{{ $url['url'] }}</loc>
        <lastmod>{{ $url['lastmod'] }}</lastmod>
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        <priority>{{ $url['priority'] }}</priority>
@if(!empty($url['image_url']))
        <image:image>
            <image:loc>{{ $url['image_url'] }}</image:loc>
            <image:title>{{ htmlspecialchars($url['image_title'] ?? '', ENT_XML1, 'UTF-8') }}</image:title>
        </image:image>
@endif
    </url>
@endforeach
</urlset>
