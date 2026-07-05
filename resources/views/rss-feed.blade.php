<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:media="http://search.yahoo.com/mrss/"
     xmlns:dc="http://purl.org/dc/elements/1.1/">
    <channel>
        <title>{{ htmlspecialchars($siteName, ENT_XML1, 'UTF-8') }}</title>
        <link>{{ $siteUrl }}</link>
        <description>{{ htmlspecialchars($siteDescription, ENT_XML1, 'UTF-8') }}</description>
        <language>bn</language>
        <lastBuildDate>{{ now()->toRfc2822String() }}</lastBuildDate>
        <atom:link href="{{ route('rss.feed') }}" rel="self" type="application/rss+xml"/>
        <image>
            <url>{{ asset('storage/' . (\App\Models\SeoSetting::first()?->logo ?? '')) }}</url>
            <title>{{ htmlspecialchars($siteName, ENT_XML1, 'UTF-8') }}</title>
            <link>{{ $siteUrl }}</link>
        </image>
@foreach($news as $item)
        <item>
            <title>{{ htmlspecialchars($item->title, ENT_XML1, 'UTF-8') }}</title>
            <link>{{ route('news.show', $item->slug) }}</link>
            <guid isPermaLink="true">{{ route('news.show', $item->slug) }}</guid>
            <pubDate>{{ $item->published_at->toRfc2822String() }}</pubDate>
            <description><![CDATA[{{ $item->excerpt ?? \Str::limit(strip_tags($item->content), 300) }}]]></description>
@if($item->author)
            <dc:creator>{{ htmlspecialchars($item->author->name, ENT_XML1, 'UTF-8') }}</dc:creator>
@endif
@if($item->category)
            <category>{{ htmlspecialchars($item->category->name, ENT_XML1, 'UTF-8') }}</category>
@endif
@if($item->featured_image)
            <media:content url="{{ asset('storage/' . $item->featured_image) }}" medium="image"/>
            <media:thumbnail url="{{ asset('storage/' . $item->featured_image) }}"/>
@endif
        </item>
@endforeach
    </channel>
</rss>
