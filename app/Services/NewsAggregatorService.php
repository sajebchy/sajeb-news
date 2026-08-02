<?php

namespace App\Services;

use App\Models\ExternalNewsItem;
use App\Models\NewsSource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Dependency-free RSS/Atom aggregator.
 *
 * Uses PHP's built-in SimpleXML + DOMDocument and Laravel's Http (Guzzle)
 * facade — no extra Composer packages. Pulls newly-published articles from
 * admin-managed source feeds, capturing full content and keywords.
 */
class NewsAggregatorService
{
    private const USER_AGENT = 'Mozilla/5.0 (compatible; SajebNewsBot/1.0; +https://sajebnews.com)';

    /**
     * Fetch every active source. Returns ['new' => int, 'errors' => int].
     */
    public function fetchAllActive(): array
    {
        $new = 0;
        $errors = 0;

        foreach (NewsSource::active()->get() as $source) {
            $result = $this->fetchSource($source);
            $new += $result['new'];
            if ($result['error']) {
                $errors++;
            }
        }

        return ['new' => $new, 'errors' => $errors];
    }

    /**
     * Fetch a single source, store new items. Returns ['new' => int, 'error' => ?string].
     */
    public function fetchSource(NewsSource $source): array
    {
        $newCount = 0;

        try {
            $feedUrl = $source->feed_url ?: $this->discoverFeed($source->site_url);

            if (!$feedUrl) {
                $this->markSource($source, 'no-feed', 'কোনো RSS ফিড খুঁজে পাওয়া যায়নি।');
                return ['new' => 0, 'error' => 'no-feed'];
            }

            if ($feedUrl !== $source->feed_url) {
                $source->feed_url = $feedUrl;
            }

            $response = Http::withHeaders(['User-Agent' => self::USER_AGENT])
                ->timeout(20)
                ->get($feedUrl);

            if (!$response->successful()) {
                $this->markSource($source, 'http-' . $response->status(), 'ফিড লোড করা যায়নি (HTTP ' . $response->status() . ')।');
                return ['new' => 0, 'error' => 'http'];
            }

            $items = $this->parseFeed($response->body());

            foreach ($items as $item) {
                if (empty($item['guid']) || empty($item['title'])) {
                    continue;
                }

                $exists = ExternalNewsItem::where('news_source_id', $source->id)
                    ->where('guid', $item['guid'])
                    ->exists();

                if ($exists) {
                    continue;
                }

                // Enrich with full page content only when the feed gave nothing usable.
                if (empty($item['content']) && !empty($item['url'])) {
                    $extracted = $this->extractFullContent($item['url']);
                    if ($extracted) {
                        $item['content'] = $extracted['content'];
                        if (empty($item['keywords']) && !empty($extracted['keywords'])) {
                            $item['keywords'] = $extracted['keywords'];
                        }
                    }
                }

                ExternalNewsItem::create([
                    'news_source_id' => $source->id,
                    'guid' => mb_substr($item['guid'], 0, 500),
                    'url' => $item['url'] ?? '',
                    'title' => mb_substr($item['title'], 0, 500),
                    'author' => $item['author'] ?? null,
                    'excerpt' => $item['excerpt'] ?? null,
                    'content' => $this->sanitizeContent($item['content'] ?? null),
                    'image_url' => $item['image_url'] ?? null,
                    'keywords' => $item['keywords'] ?? null,
                    'published_at' => $item['published_at'] ?? null,
                    'fetched_at' => now(),
                ]);

                $newCount++;
            }

            $source->items_count = $source->items()->count();
            $this->markSource($source, 'ok', null);

            return ['new' => $newCount, 'error' => null];
        } catch (\Throwable $e) {
            Log::error('News aggregator fetch failed for source ' . $source->id . ': ' . $e->getMessage());
            $this->markSource($source, 'error', mb_substr($e->getMessage(), 0, 500));

            return ['new' => $newCount, 'error' => 'exception'];
        }
    }

    /**
     * Discover an RSS/Atom feed URL from a site (or feed) URL.
     */
    public function discoverFeed(string $url): ?string
    {
        $url = trim($url);
        if ($url === '') {
            return null;
        }

        try {
            $response = Http::withHeaders(['User-Agent' => self::USER_AGENT])
                ->timeout(20)
                ->get($url);
        } catch (\Throwable $e) {
            return null;
        }

        if (!$response->successful()) {
            return null;
        }

        $body = ltrim($response->body());

        // Already a feed? (root element is <rss> or <feed>)
        if (preg_match('/^<\?xml|^<(rss|feed)[\s>]/i', $body)) {
            return $url;
        }

        // Look for <link rel="alternate" type="application/rss+xml|atom+xml" href="...">
        if (preg_match_all('/<link[^>]+>/i', $body, $matches)) {
            foreach ($matches[0] as $tag) {
                if (!preg_match('/rel\s*=\s*["\']?alternate["\']?/i', $tag)) {
                    continue;
                }
                if (!preg_match('/type\s*=\s*["\']application\/(rss|atom)\+xml["\']/i', $tag)) {
                    continue;
                }
                if (preg_match('/href\s*=\s*["\']([^"\']+)["\']/i', $tag, $href)) {
                    return $this->absoluteUrl($href[1], $url);
                }
            }
        }

        // Common fallback paths
        foreach (['/feed', '/rss', '/feed.xml', '/rss.xml', '/index.xml'] as $path) {
            $candidate = rtrim($this->originOf($url), '/') . $path;
            try {
                $probe = Http::withHeaders(['User-Agent' => self::USER_AGENT])->timeout(12)->get($candidate);
                if ($probe->successful() && preg_match('/^<\?xml|^\s*<(rss|feed)[\s>]/i', ltrim($probe->body()))) {
                    return $candidate;
                }
            } catch (\Throwable $e) {
                // try next
            }
        }

        return null;
    }

    /**
     * Parse RSS 2.0 or Atom XML into an array of normalized item arrays.
     */
    public function parseFeed(string $xml): array
    {
        $prev = libxml_use_internal_errors(true);
        $sxml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        if ($sxml === false) {
            return [];
        }

        $namespaces = $sxml->getNamespaces(true);
        $items = [];

        // RSS 2.0
        if (isset($sxml->channel)) {
            foreach ($sxml->channel->item as $item) {
                $items[] = $this->normalizeRssItem($item, $namespaces);
            }
            return $items;
        }

        // Atom
        if (isset($sxml->entry)) {
            foreach ($sxml->entry as $entry) {
                $items[] = $this->normalizeAtomEntry($entry, $namespaces);
            }
            return $items;
        }

        return $items;
    }

    private function normalizeRssItem(\SimpleXMLElement $item, array $namespaces): array
    {
        $content = null;
        if (isset($namespaces['content'])) {
            $c = $item->children($namespaces['content']);
            if (isset($c->encoded)) {
                $content = (string) $c->encoded;
            }
        }

        $author = null;
        if (isset($namespaces['dc'])) {
            $dc = $item->children($namespaces['dc']);
            if (isset($dc->creator)) {
                $author = (string) $dc->creator;
            }
        }
        if (!$author && isset($item->author)) {
            $author = (string) $item->author;
        }

        $keywords = [];
        foreach ($item->category as $cat) {
            $keywords[] = trim((string) $cat);
        }

        $link = (string) $item->link;
        $guid = isset($item->guid) ? (string) $item->guid : $link;

        $excerpt = (string) $item->description;
        if (!$content && $excerpt) {
            $content = $excerpt;
        }

        return [
            'guid' => $guid ?: $link,
            'url' => $link,
            'title' => trim(html_entity_decode(strip_tags((string) $item->title))),
            'author' => $author ? trim($author) : null,
            'excerpt' => $this->makeExcerpt($excerpt ?: $content),
            'content' => $content ?: null,
            'image_url' => $this->extractImage($item, $namespaces, $content),
            'keywords' => $this->cleanKeywords($keywords, $item, $namespaces),
            'published_at' => $this->parseDate((string) $item->pubDate),
        ];
    }

    private function normalizeAtomEntry(\SimpleXMLElement $entry, array $namespaces): array
    {
        // Prefer the alternate/html link
        $link = '';
        foreach ($entry->link as $l) {
            $rel = (string) $l['rel'];
            if ($rel === '' || $rel === 'alternate') {
                $link = (string) $l['href'];
                break;
            }
        }
        if (!$link && isset($entry->link['href'])) {
            $link = (string) $entry->link['href'];
        }

        $content = isset($entry->content) ? (string) $entry->content : null;
        $summary = isset($entry->summary) ? (string) $entry->summary : null;

        $author = null;
        if (isset($entry->author->name)) {
            $author = (string) $entry->author->name;
        }

        $keywords = [];
        foreach ($entry->category as $cat) {
            $term = (string) $cat['term'];
            if ($term !== '') {
                $keywords[] = trim($term);
            }
        }

        $guid = isset($entry->id) ? (string) $entry->id : $link;
        $date = (string) ($entry->published ?: $entry->updated);

        return [
            'guid' => $guid ?: $link,
            'url' => $link,
            'title' => trim(html_entity_decode(strip_tags((string) $entry->title))),
            'author' => $author ? trim($author) : null,
            'excerpt' => $this->makeExcerpt($summary ?: $content),
            'content' => $content ?: $summary ?: null,
            'image_url' => $this->extractImage($entry, $namespaces, $content),
            'keywords' => $this->cleanKeywords($keywords, $entry, $namespaces),
            'published_at' => $this->parseDate($date),
        ];
    }

    /**
     * Best-effort full-content + keyword extraction from an article page.
     * Returns ['content' => string, 'keywords' => ?string] or null.
     */
    public function extractFullContent(string $url): ?array
    {
        try {
            $response = Http::withHeaders(['User-Agent' => self::USER_AGENT])->timeout(20)->get($url);
            if (!$response->successful()) {
                return null;
            }
            $html = $response->body();
        } catch (\Throwable $e) {
            return null;
        }

        $prev = libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML('<?xml encoding="utf-8" ?>' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        // Strip noise
        foreach (['script', 'style', 'nav', 'aside', 'footer', 'header', 'form', 'noscript'] as $tag) {
            $nodes = $doc->getElementsByTagName($tag);
            for ($i = $nodes->length - 1; $i >= 0; $i--) {
                $node = $nodes->item($i);
                $node?->parentNode?->removeChild($node);
            }
        }

        // Meta keywords
        $keywords = null;
        foreach ($doc->getElementsByTagName('meta') as $meta) {
            if (strtolower($meta->getAttribute('name')) === 'keywords') {
                $val = trim($meta->getAttribute('content'));
                if ($val !== '') {
                    $keywords = $val;
                }
                break;
            }
        }

        // Prefer <article>, else the block with the most text
        $best = null;
        $bestLen = 0;
        $candidates = [];
        foreach ($doc->getElementsByTagName('article') as $a) {
            $candidates[] = $a;
        }
        if (empty($candidates)) {
            foreach (['div', 'section'] as $tag) {
                foreach ($doc->getElementsByTagName($tag) as $node) {
                    $candidates[] = $node;
                }
            }
        }

        foreach ($candidates as $node) {
            $len = mb_strlen(trim($node->textContent));
            if ($len > $bestLen) {
                $bestLen = $len;
                $best = $node;
            }
        }

        if (!$best || $bestLen < 200) {
            return null;
        }

        $content = $doc->saveHTML($best);
        // Drop the wrapping tag's attributes noise is acceptable; keep inner HTML-ish.

        return ['content' => $content, 'keywords' => $keywords];
    }

    private function extractImage(\SimpleXMLElement $node, array $namespaces, ?string $content): ?string
    {
        // media:content / media:thumbnail
        if (isset($namespaces['media'])) {
            $media = $node->children($namespaces['media']);
            foreach (['content', 'thumbnail'] as $tag) {
                if (isset($media->$tag)) {
                    $url = (string) $media->$tag->attributes()->url;
                    if ($url) {
                        return $url;
                    }
                }
            }
        }

        // enclosure
        if (isset($node->enclosure)) {
            $type = (string) $node->enclosure['type'];
            $url = (string) $node->enclosure['url'];
            if ($url && (str_starts_with($type, 'image') || preg_match('/\.(jpe?g|png|webp|gif)/i', $url))) {
                return $url;
            }
        }

        // First <img> in content
        if ($content && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m)) {
            return $m[1];
        }

        return null;
    }

    private function cleanKeywords(array $keywords, \SimpleXMLElement $node, array $namespaces): ?string
    {
        // media:keywords
        if (isset($namespaces['media'])) {
            $media = $node->children($namespaces['media']);
            if (isset($media->keywords)) {
                foreach (explode(',', (string) $media->keywords) as $k) {
                    $keywords[] = trim($k);
                }
            }
        }

        $keywords = collect($keywords)
            ->map(fn ($k) => trim(html_entity_decode(strip_tags((string) $k))))
            ->filter(fn ($k) => $k !== '' && mb_strlen($k) <= 60)
            ->unique()
            ->take(20)
            ->values();

        return $keywords->isEmpty() ? null : $keywords->implode(', ');
    }

    /**
     * Remove executable/dangerous markup from feed HTML before it is stored and
     * later rendered in the authenticated admin view (defence-in-depth XSS guard).
     */
    private function sanitizeContent(?string $html): ?string
    {
        if (!$html) {
            return null;
        }

        // Drop <script>/<style>/<iframe> blocks and inline event handlers.
        $html = preg_replace('#<(script|style|iframe)\b[^>]*>.*?</\1>#is', '', $html);
        $html = preg_replace('#<(script|style|iframe)\b[^>]*/?>#is', '', $html);
        $html = preg_replace('#\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $html);
        $html = preg_replace('#(href|src)\s*=\s*("|\')\s*javascript:[^"\']*(\2)#i', '$1=$2#$3', $html);

        return trim($html);
    }

    private function makeExcerpt(?string $text, int $length = 300): ?string
    {
        if (!$text) {
            return null;
        }
        $clean = trim(html_entity_decode(strip_tags($text)));
        if ($clean === '') {
            return null;
        }
        return mb_strlen($clean) > $length ? mb_substr($clean, 0, $length) . '…' : $clean;
    }

    private function parseDate(?string $date)
    {
        if (!$date) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($date);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function absoluteUrl(string $href, string $base): string
    {
        if (preg_match('#^https?://#i', $href)) {
            return $href;
        }
        $origin = $this->originOf($base);
        if (str_starts_with($href, '/')) {
            return rtrim($origin, '/') . $href;
        }
        return rtrim($base, '/') . '/' . ltrim($href, '/');
    }

    private function originOf(string $url): string
    {
        $parts = parse_url($url);
        if (!isset($parts['scheme'], $parts['host'])) {
            return $url;
        }
        $origin = $parts['scheme'] . '://' . $parts['host'];
        if (isset($parts['port'])) {
            $origin .= ':' . $parts['port'];
        }
        return $origin;
    }

    private function markSource(NewsSource $source, string $status, ?string $error): void
    {
        $source->last_fetched_at = now();
        $source->last_status = $status;
        $source->last_error = $error;
        $source->save();
    }
}
