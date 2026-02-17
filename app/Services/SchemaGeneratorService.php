<?php

namespace App\Services;

use App\Models\News;
use App\Models\SeoSetting;
use Illuminate\Support\Carbon;

class SchemaGeneratorService
{
    /**
     * Generate Organization Schema for the website
     */
    public static function organizationSchema(): array
    {
        $settings = SeoSetting::first();

        return [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => $settings->site_name ?? config('app.name'),
            "url" => url('/'),
            "logo" => $settings->logo ? asset('storage/' . $settings->logo) : null,
            "description" => $settings->site_description ?? '',
            "sameAs" => array_filter([
                $settings->facebook_url,
                $settings->twitter_url,
                $settings->instagram_url,
                $settings->youtube_url,
                $settings->linkedin_url,
                $settings->tiktok_url,
            ]),
            "contactPoint" => [
                "@type" => "ContactPoint",
                "contactType" => "Customer Service",
                "url" => url('/'),
            ]
        ];
    }

    /**
     * Generate Website Schema for search box integration
     */
    public static function websiteSchema(): array
    {
        $settings = SeoSetting::first();

        return [
            "@context" => "https://schema.org",
            "@type" => "WebSite",
            "name" => $settings->site_name ?? config('app.name'),
            "url" => url('/'),
            "description" => $settings->site_description ?? '',
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => [
                    "@type" => "EntryPoint",
                    "urlTemplate" => route('news.search') . "?q={search_term_string}"
                ],
                "query-input" => "required name=search_term_string"
            ]
        ];
    }

    /**
     * Generate NewsArticle Schema for news posts
     */
    public static function newsArticleSchema(News $news): array
    {
        $settings = SeoSetting::first();
        $author = $news->author;

        return [
            "@context" => "https://schema.org",
            "@type" => "NewsArticle",
            "headline" => $news->title,
            "description" => $news->excerpt ?? substr(strip_tags($news->content), 0, 160),
            "image" => [
                "@type" => "ImageObject",
                "url" => asset('storage/' . $news->featured_image) ?? asset('images/default-news.jpg'),
                "width" => 1200,
                "height" => 630
            ],
            "datePublished" => $news->created_at->toIso8601String(),
            "dateModified" => $news->updated_at->toIso8601String(),
            "author" => [
                "@type" => "Person",
                "name" => $author->name ?? $settings->site_name,
                "url" => $author && $author->avatar ? asset('storage/' . $author->avatar) : null
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => $settings->site_name ?? config('app.name'),
                "logo" => [
                    "@type" => "ImageObject",
                    "url" => $settings->logo ? asset('storage/' . $settings->logo) : null,
                    "width" => 250,
                    "height" => 60
                ]
            ],
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => route('news.show', $news->slug)
            ],
            "articleBody" => $news->content,
            "keywords" => $news->tags->pluck('name')->implode(', ')
        ];
    }

    /**
     * Generate BreadcrumbList Schema
     */
    public static function breadcrumbSchema(array $breadcrumbs): array
    {
        $itemListElement = [];
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $itemListElement[] = [
                "@type" => "ListItem",
                "position" => $index + 1,
                "name" => $breadcrumb['name'],
                "item" => $breadcrumb['url']
            ];
        }

        return [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $itemListElement
        ];
    }

    /**
     * Generate Person Schema for authors
     */
    public static function personSchema($user): array
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "Person",
            "name" => $user->name,
            "url" => route('author.show', $user->id),
            "image" => $user->avatar ? asset('storage/' . $user->avatar) : null,
            "description" => $user->bio ?? '',
            "jobTitle" => $user->roles->first()?->name ?? 'Author'
        ];
    }

    /**
     * Generate ImageObject Schema
     */
    public static function imageObjectSchema(string $imageUrl, string $name = '', int $width = 1200, int $height = 630): array
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "ImageObject",
            "url" => $imageUrl,
            "name" => $name,
            "width" => $width,
            "height" => $height
        ];
    }

    /**
     * Generate VideoObject Schema
     */
    public static function videoObjectSchema(string $videoUrl, string $thumbnailUrl, string $title, string $description, string $uploadDate): array
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "VideoObject",
            "name" => $title,
            "description" => $description,
            "thumbnailUrl" => $thumbnailUrl,
            "uploadDate" => $uploadDate,
            "duration" => "PT0M0S",
            "contentUrl" => $videoUrl,
            "embedUrl" => $videoUrl
        ];
    }

    /**
     * Generate LiveBlogPosting Schema for breaking news
     */
    public static function liveBlogPostingSchema(News $news, array $updates = []): array
    {
        $settings = SeoSetting::first();

        $liveBlogUpdate = [];
        foreach ($updates as $update) {
            $liveBlogUpdate[] = [
                "@type" => "BlogPosting",
                "headline" => $update['headline'] ?? '',
                "datePublished" => $update['datePublished'] ?? now()->toIso8601String(),
                "articleBody" => $update['articleBody'] ?? ''
            ];
        }

        return [
            "@context" => "https://schema.org",
            "@type" => "LiveBlogPosting",
            "headline" => $news->title,
            "description" => $news->excerpt ?? substr(strip_tags($news->content), 0, 160),
            "image" => asset('storage/' . $news->featured_image) ?? asset('images/default-news.jpg'),
            "datePublished" => $news->created_at->toIso8601String(),
            "dateModified" => $news->updated_at->toIso8601String(),
            "author" => [
                "@type" => "Person",
                "name" => $news->author->name ?? $settings->site_name
            ],
            "publisher" => [
                "@type" => "Organization",
                "name" => $settings->site_name ?? config('app.name')
            ],
            "liveBlogUpdate" => $liveBlogUpdate
        ];
    }

    /**
     * Generate FAQPage Schema
     */
    public static function faqPageSchema(array $faqItems): array
    {
        $mainEntity = [];
        foreach ($faqItems as $item) {
            $mainEntity[] = [
                "@type" => "Question",
                "name" => $item['question'] ?? '',
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => $item['answer'] ?? ''
                ]
            ];
        }

        return [
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => $mainEntity
        ];
    }

    /**
     * Generate JobPosting Schema
     */
    public static function jobPostingSchema(array $jobData): array
    {
        $settings = SeoSetting::first();

        return [
            "@context" => "https://schema.org",
            "@type" => "JobPosting",
            "title" => $jobData['title'] ?? '',
            "description" => $jobData['description'] ?? '',
            "hiringOrganization" => [
                "@type" => "Organization",
                "name" => $settings->site_name ?? config('app.name'),
                "sameAs" => url('/')
            ],
            "jobLocation" => [
                "@type" => "Place",
                "address" => [
                    "@type" => "PostalAddress",
                    "addressCountry" => "BD",
                    "addressLocality" => $jobData['location'] ?? 'Bangladesh'
                ]
            ],
            "baseSalary" => [
                "@type" => "PriceSpecification",
                "priceCurrency" => "BDT",
                "price" => $jobData['salary'] ?? 0
            ],
            "datePosted" => $jobData['datePosted'] ?? now()->toIso8601String(),
            "validThrough" => $jobData['validThrough'] ?? now()->addMonths(1)->toIso8601String()
        ];
    }

    /**
     * Generate Event Schema
     */
    public static function eventSchema(array $eventData): array
    {
        $settings = SeoSetting::first();

        return [
            "@context" => "https://schema.org",
            "@type" => "Event",
            "name" => $eventData['name'] ?? '',
            "description" => $eventData['description'] ?? '',
            "image" => $eventData['image'] ?? null,
            "startDate" => $eventData['startDate'] ?? now()->toIso8601String(),
            "endDate" => $eventData['endDate'] ?? now()->addDays(1)->toIso8601String(),
            "eventStatus" => "https://schema.org/EventScheduled",
            "eventAttendanceMode" => "https://schema.org/OnlineEventAttendanceMode",
            "organizer" => [
                "@type" => "Organization",
                "name" => $settings->site_name ?? config('app.name'),
                "url" => url('/')
            ],
            "location" => [
                "@type" => "VirtualLocation",
                "url" => url('/')
            ]
        ];
    }

    /**
     * Generate ClaimReview Schema
     */
    public static function claimReviewSchema(array $reviewData): array
    {
        $settings = SeoSetting::first();

        return [
            "@context" => "https://schema.org",
            "@type" => "ClaimReview",
            "claimReviewed" => $reviewData['claim'] ?? '',
            "url" => $reviewData['url'] ?? url('/'),
            "reviewRating" => [
                "@type" => "Rating",
                "ratingValue" => $reviewData['rating'] ?? 'True',
                "bestRating" => "True",
                "worstRating" => "False"
            ],
            "author" => [
                "@type" => "Organization",
                "name" => $settings->site_name ?? config('app.name')
            ],
            "reviewDate" => $reviewData['reviewDate'] ?? now()->toIso8601String(),
            "claimFirstAppearance" => [
                "@type" => "WebPage",
                "url" => $reviewData['claimUrl'] ?? url('/')
            ]
        ];
    }

    /**
     * Generate ClaimReview Schema for News Articles
     */
    public static function newsClaimReviewSchema($news): array
    {
        $settings = SeoSetting::first();
        $category = $news->category;

        return [
            "@context" => "https://schema.org",
            "@type" => "ClaimReview",
            "claimReviewed" => $news->claim_being_reviewed ?? $news->title,
            "url" => route('news.show', $news->slug),
            "reviewRating" => [
                "@type" => "Rating",
                "ratingValue" => $news->claim_rating ?? 'Unproven',
                "bestRating" => "True",
                "worstRating" => "False"
            ],
            "author" => [
                "@type" => "Organization",
                "name" => $category?->claim_reviewer_name ?? $settings->site_name ?? config('app.name'),
                "sameAs" => $category?->claim_reviewer_url ?? url('/')
            ],
            "reviewDate" => ($news->claim_review_date ?? $news->published_at)->toIso8601String(),
            "reviewBody" => $news->claim_review_evidence ?? $news->content,
            "claimFirstAppearance" => [
                "@type" => "WebPage",
                "url" => route('news.show', $news->slug)
            ]
        ];
    }

    /**
     * Extract videos from article content and generate VideoObject schemas
     * Supports YouTube, Vimeo, and other embedded video providers
     */
    public static function extractVideoSchemasFromContent(News $news): array
    {
        $videos = [];
        $content = $news->content;

        // Extract YouTube videos
        $youtubePatterns = [
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
            '/youtu\.be\/([a-zA-Z0-9_-]+)/',
            'src=["\'](?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]+)["\']'
        ];

        foreach ($youtubePatterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                foreach ($matches[1] as $videoId) {
                    $videos[] = [
                        'type' => 'youtube',
                        'id' => $videoId,
                        'url' => 'https://www.youtube.com/watch?v=' . $videoId,
                        'embed_url' => 'https://www.youtube.com/embed/' . $videoId,
                        'thumbnail' => 'https://img.youtube.com/vi/' . $videoId . '/maxresdefault.jpg'
                    ];
                }
            }
        }

        // Extract Vimeo videos
        if (preg_match_all('/vimeo\.com\/(\d+)/', $content, $matches)) {
            foreach ($matches[1] as $videoId) {
                $videos[] = [
                    'type' => 'vimeo',
                    'id' => $videoId,
                    'url' => 'https://vimeo.com/' . $videoId,
                    'embed_url' => 'https://player.vimeo.com/video/' . $videoId,
                    'thumbnail' => 'https://vimeo.com/api/v2/video/' . $videoId . '.json'
                ];
            }
        }

        // Generate VideoObject schemas for each video
        $schemas = [];
        foreach ($videos as $video) {
            $schemas[] = self::videoObjectSchema(
                $video['embed_url'],
                $video['thumbnail'],
                $news->title,
                $news->excerpt ?? substr(strip_tags($news->content), 0, 160),
                $news->published_at->toIso8601String()
            );
        }

        return $schemas;
    }

    /**
     * Check if article content contains any videos
     */
    public static function hasVideos(News $news): bool
    {
        $content = $news->content;
        
        // Check for common video embed patterns
        $videoPatterns = [
            '/youtube\.com\/embed\//',
            '/youtu\.be\//',
            '/vimeo\.com\//',
            '/<iframe.*?src=.*?(?:youtube|vimeo)/i',
            '/<video/i'
        ];

        foreach ($videoPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }
}
