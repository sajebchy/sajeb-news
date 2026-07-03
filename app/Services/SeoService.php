<?php

namespace App\Services;

use App\Models\SeoSetting;
use App\Models\News;

class SeoService
{
    public function getSeoSettings()
    {
        return SeoSetting::getInstance();
    }

    public function getNewsMetaTags(News $news)
    {
        $seo = SeoSetting::first();
        $siteName = $seo?->site_name ?: 'সজীব নিউজ';
        $imageUrl = $news->featured_image
            ? (str_starts_with($news->featured_image, 'http') ? $news->featured_image : asset($news->featured_image))
            : null;

        return [
            'title'               => $news->meta_title ?? $news->title . ' - ' . $siteName,
            'description'         => $news->meta_description ?? substr(strip_tags($news->excerpt ?? $news->content ?? ''), 0, 160),
            'keywords'            => $news->meta_keywords,
            'canonical'           => $news->canonical_url ?? route('news.show', $news->slug),
            'og_title'            => $news->title,
            'og_description'      => $news->og_description ?? $news->excerpt,
            'og_image'            => $imageUrl,
            'og_url'              => route('news.show', $news->slug),
            'og_type'             => 'article',
            'twitter_card'        => 'summary_large_image',
            'twitter_title'       => $news->title,
            'twitter_description' => $news->excerpt,
            'twitter_image'       => $imageUrl,
            'article_published'   => $news->published_at?->toIso8601String(),
            'article_modified'    => $news->updated_at->toIso8601String(),
            'article_author'      => $news->author?->name,
            'article_section'     => $news->category?->name,
        ];
    }

    public function getNewsSchema(News $news): array
    {
        $seo      = SeoSetting::first();
        $siteName = $seo?->site_name ?: 'সজীব নিউজ';
        $siteUrl  = $seo?->site_url  ?: url('/');
        $logoUrl  = $seo?->logo ? \Storage::url($seo->logo) : asset('images/logo.png');

        $imageUrl = $news->featured_image
            ? (str_starts_with($news->featured_image, 'http') ? $news->featured_image : asset($news->featured_image))
            : null;

        // Build full article text (strip HTML)
        $articleBody = strip_tags($news->content ?? '');

        $schemas = [];

        // ── 1. NewsArticle ────────────────────────────────────────────
        $newsArticle = [
            '@context'          => 'https://schema.org',
            '@type'             => 'NewsArticle',
            'headline'          => $news->title,
            'description'       => $news->excerpt ?? substr($articleBody, 0, 200),
            'articleBody'       => substr($articleBody, 0, 5000),
            'inLanguage'        => 'bn',
            'datePublished'     => $news->published_at?->toIso8601String(),
            'dateModified'      => $news->updated_at->toIso8601String(),
            'url'               => route('news.show', $news->slug),
            'mainEntityOfPage'  => [
                '@type' => 'WebPage',
                '@id'   => route('news.show', $news->slug),
            ],
            'publisher' => [
                '@type' => 'NewsMediaOrganization',
                'name'  => $siteName,
                'url'   => $siteUrl,
                'logo'  => [
                    '@type'  => 'ImageObject',
                    'url'    => $logoUrl,
                    'width'  => 250,
                    'height' => 60,
                ],
            ],
        ];

        if ($news->author) {
            $newsArticle['author'] = [
                '@type' => 'Person',
                'name'  => $news->author->name,
                'url'   => route('author.show', $news->author->id),
            ];
        }

        if ($imageUrl) {
            $newsArticle['image'] = [
                '@type'  => 'ImageObject',
                'url'    => $imageUrl,
                'width'  => 1200,
                'height' => 630,
            ];
        }

        if ($news->category) {
            $newsArticle['articleSection'] = $news->category->name;
            $newsArticle['keywords']       = $news->meta_keywords ?? $news->category->name;
        }

        $schemas[] = $newsArticle;

        // ── 2. BreadcrumbList ─────────────────────────────────────────
        $breadcrumbItems = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'হোম',           'item' => route('home')],
        ];
        if ($news->category) {
            $breadcrumbItems[] = [
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => $news->category->name,
                'item'     => route('category.show', $news->category->slug),
            ];
        }
        $breadcrumbItems[] = [
            '@type'    => 'ListItem',
            'position' => $news->category ? 3 : 2,
            'name'     => $news->title,
            'item'     => route('news.show', $news->slug),
        ];

        $schemas[] = [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $breadcrumbItems,
        ];

        return $schemas;
    }

    /** WebSite schema — for homepage only */
    public function getWebsiteSchema(): array
    {
        $seo      = SeoSetting::first();
        $siteName = $seo?->site_name ?: 'সজীব নিউজ';
        $siteUrl  = $seo?->site_url  ?: url('/');

        return [
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => $siteName,
            'url'      => $siteUrl,
            'inLanguage' => 'bn',
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'       => 'EntryPoint',
                    'urlTemplate' => url('/search') . '?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /** Organization schema — homepage / layout */
    public function getOrganizationSchema(): array
    {
        $seo      = SeoSetting::first();
        $siteName = $seo?->site_name ?: 'সজীব নিউজ';
        $siteUrl  = $seo?->site_url  ?: url('/');
        $logoUrl  = $seo?->logo ? \Storage::url($seo->logo) : asset('images/logo.png');

        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'NewsMediaOrganization',
            'name'        => $siteName,
            'url'         => $siteUrl,
            'description' => $seo?->site_description ?: null,
            'logo'        => [
                '@type'  => 'ImageObject',
                'url'    => $logoUrl,
                'width'  => 250,
                'height' => 60,
            ],
            'sameAs'      => array_filter([
                $seo?->facebook_url  ?? null,
                $seo?->twitter_url   ?? null,
                $seo?->youtube_url   ?? null,
                $seo?->instagram_url ?? null,
                $seo?->youtube_url   ?? null,
                $seo?->linkedin_url  ?? null,
            ]),
        ];

        if ($seo?->office_address) {
            $schema['address'] = [
                '@type'          => 'PostalAddress',
                'streetAddress'  => $seo->office_address,
                'addressCountry' => 'BD',
            ];
        }

        $contactPoint = [
            '@type'             => 'ContactPoint',
            'contactType'       => 'customer service',
            'areaServed'        => 'BD',
            'availableLanguage' => 'Bengali',
        ];
        if ($seo?->office_email) {
            $contactPoint['email'] = $seo->office_email;
        }
        if ($seo?->office_mobile) {
            $contactPoint['telephone'] = $seo->office_mobile;
        }
        $schema['contactPoint'] = $contactPoint;

        return array_filter($schema, fn($v) => $v !== null);
    }

    /** Category listing page schema */
    public function getCategorySchema($category, $newsList): array
    {
        $seo      = SeoSetting::first();
        $siteName = $seo?->site_name ?: 'সজীব নিউজ';

        $itemList = $newsList->take(10)->values()->map(fn($n, $i) => [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'url'      => route('news.show', $n->slug),
            'name'     => $n->title,
        ])->toArray();

        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'CollectionPage',
            'name'            => $category->name . ' - ' . $siteName,
            'description'     => $category->description ?? $category->name . ' বিভাগের সর্বশেষ সংবাদ',
            'url'             => route('category.show', $category->slug),
            'inLanguage'      => 'bn',
            'itemListElement' => $itemList,
        ];
    }

    public function getBreadcrumbSchema($items = []): array
    {
        $breadcrumbs = [[
            '@type'    => 'ListItem',
            'position' => 1,
            'name'     => 'হোম',
            'item'     => route('home'),
        ]];

        $position = 2;
        foreach ($items as $name => $url) {
            $breadcrumbs[] = ['@type' => 'ListItem', 'position' => $position++, 'name' => $name, 'item' => $url];
        }

        return ['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $breadcrumbs];
    }

    public function generateRobotsTxt(): string
    {
        $settings = $this->getSeoSettings();
        if ($settings->robots_txt) return $settings->robots_txt;

        return <<<'ROBOTS'
User-agent: *
Allow: /

Sitemap: /sitemap.xml
Crawl-delay: 1

Disallow: /admin/
Disallow: /api/
Disallow: /storage/
ROBOTS;
    }

    public function updateSettings(array $data)
    {
        $settings = SeoSetting::getInstance();
        return $settings->update($data);
    }
}
