<?php

namespace App\Services;

use App\Models\SeoSetting;
use App\Models\News;

class SeoService
{
    /**
     * Get SEO settings
     */
    public function getSeoSettings()
    {
        return SeoSetting::getInstance();
    }

    /**
     * Generate meta tags for news
     */
    public function getNewsMetaTags(News $news)
    {
        return [
            'title' => $news->meta_title ?? $news->title . ' - Sajeb News',
            'description' => $news->meta_description ?? substr(strip_tags($news->excerpt ?? $news->content), 0, 160),
            'keywords' => $news->meta_keywords,
            'canonical' => $news->canonical_url ?? route('news.show', $news->slug),
            'og_title' => $news->title,
            'og_description' => $news->og_description ?? $news->excerpt,
            'og_image' => $news->og_image ?? $news->featured_image,
            'og_url' => route('news.show', $news->slug),
            'og_type' => 'article',
            'twitter_card' => $news->twitter_card ?? 'summary_large_image',
            'twitter_title' => $news->title,
            'twitter_description' => $news->excerpt,
            'twitter_image' => $news->featured_image,
        ];
    }

    /**
     * Generate JSON-LD schema for news
     */
    public function getNewsSchema(News $news)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $news->title,
            'description' => $news->excerpt,
            'image' => $news->featured_image,
            'datePublished' => $news->published_at?->toIso8601String(),
            'dateModified' => $news->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $news->author->name,
                'url' => route('author.show', $news->author->id),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Sajeb News',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('images/logo.png'),
                    'width' => 250,
                    'height' => 60,
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('news.show', $news->slug),
            ],
        ];
    }

    /**
     * Generate breadcrumb schema
     */
    public function getBreadcrumbSchema($items = [])
    {
        $breadcrumbs = [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => route('home'),
            ],
        ];

        $position = 2;
        foreach ($items as $name => $url) {
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $name,
                'item' => $url,
            ];
            $position++;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs,
        ];
    }

    /**
     * Generate robots.txt content
     */
    public function generateRobotsTxt()
    {
        $settings = $this->getSeoSettings();
        
        if ($settings->robots_txt) {
            return $settings->robots_txt;
        }

        return <<<'ROBOTS'
User-agent: *
Allow: /

# Sitemap
Sitemap: /sitemap.xml

# Crawl delay
Crawl-delay: 1

# Disallow admin and private areas
Disallow: /admin/
Disallow: /admin-panel/
Disallow: /api/
Disallow: /storage/
ROBOTS;
    }

    /**
     * Update SEO settings
     */
    public function updateSettings(array $data)
    {
        $settings = SeoSetting::getInstance();
        return $settings->update($data);
    }
}
