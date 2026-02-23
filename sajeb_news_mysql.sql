-- Sajeb News - MySQL Database Schema
-- Compatible with cPanel phpMyAdmin
-- Version 2.1 - Updated February 23, 2026
--
-- Features:
-- - Live Streaming with Stream Comments
-- - Push Notifications Support
-- - Enhanced Schema Settings
-- - Improved SEO Settings with AdSense Configuration
-- - Stream Analytics
-- - Advanced Comment System
-- - Advanced Advertisement System (Multi-Network, Placement-Based, CPC/CPM)
-- - Fact-Checking / ClaimReview Support
-- - Visitor Analytics
-- - Permission-Based Access Control (Spatie)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Users table

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `email_verified_at` timestamp NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `role` enum('admin','editor','reporter','author','user') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `is_active` boolean DEFAULT 1,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci,
  `facebook_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `facebook_token` varchar(255) COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache table

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL PRIMARY KEY,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache locks table

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL PRIMARY KEY,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jobs table

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL DEFAULT 0,
  `reserved_at` int UNSIGNED,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity logs table

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` bigint UNSIGNED,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci,
  `model_id` bigint UNSIGNED,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `activity_logs_user_id_index` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories table

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `description` text COLLATE utf8mb4_unicode_ci,
  `parent_id` bigint UNSIGNED,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci,
  `order` int DEFAULT 0,
  `featured_order` int COMMENT 'Order for display on homepage (1-5), NULL means not featured',
  `is_active` boolean DEFAULT 1,
  `is_fact_checker` boolean DEFAULT 0,
  `claim_review_enabled` boolean DEFAULT 0,
  `claim_rating_scale` enum('True','Mostly True','Partly False','False','Unproven') COLLATE utf8mb4_unicode_ci,
  `claim_reviewer_name` varchar(255) COLLATE utf8mb4_unicode_ci,
  `claim_reviewer_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `categories_parent_id_index` (`parent_id`),
  FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tags table

CREATE TABLE `tags` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `color` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT '#007bff',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- News table

CREATE TABLE `news` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `category_id` bigint UNSIGNED NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','published','scheduled','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `is_featured` boolean DEFAULT 0,
  `is_claim_review` boolean DEFAULT 0,
  `claim_being_reviewed` text COLLATE utf8mb4_unicode_ci,
  `claim_rating` enum('True','Mostly True','Partly False','False','Unproven') COLLATE utf8mb4_unicode_ci,
  `claim_review_evidence` longtext COLLATE utf8mb4_unicode_ci,
  `claim_review_date` timestamp NULL,
  `is_breaking` boolean DEFAULT 0,
  `is_trending` boolean DEFAULT 0,
  `view_count` int DEFAULT 0,
  `published_at` timestamp NULL,
  `scheduled_at` timestamp NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `news_category_id_index` (`category_id`),
  KEY `news_author_id_index` (`author_id`),
  KEY `news_slug_index` (`slug`),
  KEY `news_status_index` (`status`),
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- News tags pivot table

CREATE TABLE `news_tag` (
  `news_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`news_id`, `tag_id`),
  FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Advertisements table (Multi-Network, Placement-Based, CPC/CPM support)

CREATE TABLE `advertisements` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `code` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'banner',
  `ad_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'standard',
  `ad_source` enum('offline','online') COLLATE utf8mb4_unicode_ci DEFAULT 'offline',
  `ad_network` varchar(255) COLLATE utf8mb4_unicode_ci COMMENT 'Ad network: adsense, media_net, ezoic, propeller_ads, mediavine, raptive, monumetric, adsterra, monetag, infolinks, taboola_outbrain, amazon_associates',
  `network_config` json COMMENT 'Network-specific configuration (code, zone_id, site_id, etc.)',
  `placement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'sidebar',
  `device_target` enum('desktop','mobile','all') COLLATE utf8mb4_unicode_ci DEFAULT 'all',
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `ad_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci,
  `utm_source` varchar(255) COLLATE utf8mb4_unicode_ci,
  `utm_medium` varchar(255) COLLATE utf8mb4_unicode_ci,
  `utm_campaign` varchar(255) COLLATE utf8mb4_unicode_ci,
  `utm_term` varchar(255) COLLATE utf8mb4_unicode_ci,
  `utm_content` varchar(255) COLLATE utf8mb4_unicode_ci,
  `start_date` datetime NOT NULL,
  `end_date` datetime,
  `is_active` boolean DEFAULT 1,
  `is_adsense_enabled` boolean DEFAULT 0,
  `disable_page_limit` int DEFAULT 3 COMMENT 'Max ads per page as per AdSense policy',
  `minimum_content_length` int DEFAULT 300 COMMENT 'Minimum words for AdSense policy',
  `adsense_code` text COLLATE utf8mb4_unicode_ci,
  `adsense_slot_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `adsense_publisher_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `views` int DEFAULT 0,
  `clicks` int DEFAULT 0,
  `target_categories` json,
  `target_tags` json,
  `display_order` int DEFAULT 0,
  `show_on_mobile` boolean DEFAULT 1,
  `show_on_desktop` boolean DEFAULT 1,
  `daily_impression_limit` int,
  `max_clicks_per_day` int,
  `cpc_amount` decimal(10,2) COMMENT 'Cost per click',
  `cpm_amount` decimal(10,2) COMMENT 'Cost per thousand impressions',
  `total_spent` decimal(10,2) DEFAULT 0.00,
  `advertiser_name` varchar(255) COLLATE utf8mb4_unicode_ci,
  `advertiser_email` varchar(255) COLLATE utf8mb4_unicode_ci,
  `advertiser_phone` varchar(255) COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `advertisements_type_index` (`type`(50)),
  KEY `advertisements_is_active_index` (`is_active`),
  KEY `advertisements_placement_index` (`placement`(50)),
  KEY `advertisements_display_order_index` (`display_order`),
  KEY `advertisements_ad_network_index` (`ad_network`(50)),
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Newsletter subscribers table

CREATE TABLE `newsletter_subscribers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci,
  `is_subscribed` boolean DEFAULT 1,
  `verified_at` timestamp NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Push notifications table

CREATE TABLE `push_notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_id` bigint UNSIGNED,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `sent_at` timestamp NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEO settings table (with AdSense, Social, Analytics, VAPID config)

CREATE TABLE `seo_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `site_title` varchar(255) COLLATE utf8mb4_unicode_ci,
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci,
  `site_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `site_description` text COLLATE utf8mb4_unicode_ci,
  `site_keywords` varchar(255) COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci,
  `mobile_logo` varchar(255) COLLATE utf8mb4_unicode_ci,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci,
  `about_page_content` longtext COLLATE utf8mb4_unicode_ci,
  `adsense_publisher_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `adsense_anchor_ad_code` longtext COLLATE utf8mb4_unicode_ci,
  `adsense_sidebar_ad_code` longtext COLLATE utf8mb4_unicode_ci,
  `adsense_between_articles_ad_code` longtext COLLATE utf8mb4_unicode_ci,
  `show_anchor_ads` boolean DEFAULT 1,
  `show_sidebar_ads` boolean DEFAULT 1,
  `show_between_articles_ads` boolean DEFAULT 1,
  `twitter_handle` varchar(255) COLLATE utf8mb4_unicode_ci,
  `google_analytics_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `ga_tracking_id` varchar(100) COLLATE utf8mb4_unicode_ci,
  `google_tag_manager_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `gtm_tracking_id` varchar(100) COLLATE utf8mb4_unicode_ci,
  `facebook_pixel_id` varchar(100) COLLATE utf8mb4_unicode_ci,
  `facebook_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `instagram_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `youtube_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `tiktok_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `recaptcha_site_key` varchar(255) COLLATE utf8mb4_unicode_ci,
  `recaptcha_secret_key` varchar(255) COLLATE utf8mb4_unicode_ci,
  `recaptcha_score_threshold` decimal(3,2) DEFAULT 0.50,
  `vapid_public_key` text COLLATE utf8mb4_unicode_ci,
  `vapid_private_key` text COLLATE utf8mb4_unicode_ci,
  `push_notifications_enabled` boolean DEFAULT 0,
  `adsense_client_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `adsense_slot_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `enable_adsense` boolean DEFAULT 0,
  `robots_txt` text COLLATE utf8mb4_unicode_ci,
  `enable_sitemap` boolean DEFAULT 1,
  `enable_robots` boolean DEFAULT 1,
  `enable_analytics` boolean DEFAULT 1,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- News analytics table

CREATE TABLE `news_analytics` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `news_id` bigint UNSIGNED NOT NULL,
  `views` int DEFAULT 0,
  `unique_views` int DEFAULT 0,
  `shares` int DEFAULT 0,
  `comments_count` int DEFAULT 0,
  `date` date NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `news_analytics_news_id_index` (`news_id`),
  FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessions table

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL PRIMARY KEY,
  `user_id` bigint UNSIGNED,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Visitor analytics table

CREATE TABLE `visitor_analytics` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referrer` varchar(255) COLLATE utf8mb4_unicode_ci,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `visited_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comments table

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `news_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `facebook_user_id` varchar(255) COLLATE utf8mb4_unicode_ci,
  `comment_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved` boolean DEFAULT 0,
  `spam_score` decimal(5,2) DEFAULT 0,
  `recaptcha_score` decimal(3,2),
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `comments_news_id_index` (`news_id`),
  KEY `comments_user_id_index` (`user_id`),
  KEY `comments_approved_index` (`approved`),
  KEY `comments_created_at_index` (`created_at`),
  FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Live streams table

CREATE TABLE `live_streams` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci,
  `stream_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `stream_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `visibility` enum('public','private','unlisted') COLLATE utf8mb4_unicode_ci DEFAULT 'public',
  `viewer_count` int DEFAULT 0,
  `peak_viewers` int DEFAULT 0,
  `scheduled_at` timestamp NULL,
  `started_at` timestamp NULL,
  `ended_at` timestamp NULL,
  `duration_seconds` int DEFAULT 0,
  `stream_tags` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci,
  `allow_comments` boolean DEFAULT 1,
  `allow_chat` boolean DEFAULT 1,
  `recording_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `is_featured` boolean DEFAULT 0,
  `view_count` int DEFAULT 0,
  `like_count` int DEFAULT 0,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  KEY `live_streams_status_index` (`status`),
  KEY `live_streams_user_id_index` (`user_id`),
  KEY `live_streams_started_at_index` (`started_at`),
  KEY `live_streams_is_featured_index` (`is_featured`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Stream comments table

CREATE TABLE `stream_comments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `live_stream_id` bigint UNSIGNED NOT NULL,
  `commenter_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commenter_email` varchar(255) COLLATE utf8mb4_unicode_ci,
  `facebook_id` varchar(255) COLLATE utf8mb4_unicode_ci UNIQUE,
  `facebook_profile_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `commenter_avatar` varchar(255) COLLATE utf8mb4_unicode_ci,
  `comment_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` enum('facebook','website','anonymous') COLLATE utf8mb4_unicode_ci DEFAULT 'website',
  `is_verified` boolean DEFAULT 0,
  `is_pinned` boolean DEFAULT 0,
  `likes_count` int DEFAULT 0,
  `is_approved` boolean DEFAULT 1,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  `deleted_at` timestamp NULL,
  KEY `stream_comments_live_stream_id_index` (`live_stream_id`),
  KEY `stream_comments_facebook_id_index` (`facebook_id`),
  KEY `stream_comments_created_at_index` (`created_at`),
  KEY `stream_comments_is_pinned_index` (`is_pinned`),
  FOREIGN KEY (`live_stream_id`) REFERENCES `live_streams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Push subscriptions table

CREATE TABLE `push_subscriptions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `endpoint` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE,
  `public_key` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_ip` varchar(45) COLLATE utf8mb4_unicode_ci,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `is_active` boolean DEFAULT 1,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  KEY `push_subscriptions_is_active_index` (`is_active`),
  KEY `push_subscriptions_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Schema settings table

CREATE TABLE `schema_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `enable_news_article_schema` boolean DEFAULT 1,
  `enable_organization_schema` boolean DEFAULT 1,
  `enable_website_schema` boolean DEFAULT 1,
  `enable_breadcrumb_schema` boolean DEFAULT 1,
  `enable_person_schema` boolean DEFAULT 1,
  `enable_image_object_schema` boolean DEFAULT 1,
  `enable_video_object_schema` boolean DEFAULT 0,
  `enable_live_blog_schema` boolean DEFAULT 0,
  `enable_faq_schema` boolean DEFAULT 0,
  `enable_job_posting_schema` boolean DEFAULT 0,
  `enable_event_schema` boolean DEFAULT 0,
  `enable_claim_review_schema` boolean DEFAULT 0,
  `organization_name` varchar(255) COLLATE utf8mb4_unicode_ci,
  `organization_url` varchar(255) COLLATE utf8mb4_unicode_ci,
  `organization_logo` varchar(255) COLLATE utf8mb4_unicode_ci,
  `organization_contact_type` varchar(100) COLLATE utf8mb4_unicode_ci,
  `organization_contact_phone` varchar(20) COLLATE utf8mb4_unicode_ci,
  `organization_contact_email` varchar(255) COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permissions table (Spatie Laravel Permission)

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Roles table (Spatie Laravel Permission)

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL,
  UNIQUE KEY `roles_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Model has permissions pivot table

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`, `model_type`),
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Model has roles pivot table

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`),
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Role has permissions pivot table

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`),
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrations table

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin user (Email: admin@test.com, Password: 12345)

INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
('Admin User', 'admin@test.com', '$2y$12$Iq6w9qLpY8Z6Q7K8R9L0Mu1V2W3X4Y5Z6A7B8C9D0E1F2G3H4I5J6K7', 'admin', 1, NOW(), NOW());

COMMIT;
