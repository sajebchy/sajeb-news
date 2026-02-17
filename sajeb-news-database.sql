-- Database dump for Sajeb News Portal
-- Generated: 2026-02-15 06:15:00
-- MySQL Version: 5.7+
-- SQLite to MySQL Conversion Completed

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `migrations` (`id` INT AUTO_INCREMENT PRIMARY KEY, `migration` VARCHAR(255), `batch` INT);
REPLACE INTO `migrations` VALUES(1,'0001_01_01_000000_create_users_table',1);
REPLACE INTO `migrations` VALUES(2,'0001_01_01_000001_create_cache_table',1);
REPLACE INTO `migrations` VALUES(3,'0001_01_01_000002_create_jobs_table',1);
REPLACE INTO `migrations` VALUES(4,'2026_02_03_104900_create_activity_logs_table',1);
REPLACE INTO `migrations` VALUES(5,'2026_02_03_104900_create_advertisements_table',1);
REPLACE INTO `migrations` VALUES(6,'2026_02_03_104900_create_categories_table',1);
REPLACE INTO `migrations` VALUES(7,'2026_02_03_104900_create_news_analytics_table',1);
REPLACE INTO `migrations` VALUES(8,'2026_02_03_104900_create_news_table',1);
REPLACE INTO `migrations` VALUES(9,'2026_02_03_104900_create_newsletter_subscribers_table',1);
REPLACE INTO `migrations` VALUES(10,'2026_02_03_104900_create_push_notifications_table',1);
REPLACE INTO `migrations` VALUES(11,'2026_02_03_104900_create_seo_settings_table',1);
REPLACE INTO `migrations` VALUES(12,'2026_02_03_105111_add_fields_to_users_table',1);
REPLACE INTO `migrations` VALUES(13,'2026_02_03_105159_create_permission_tables',1);
REPLACE INTO `migrations` VALUES(14,'2026_02_03_104901_create_tags_table',2);
REPLACE INTO `migrations` VALUES(15,'2026_02_03_120000_add_mobile_logo_to_seo_settings',3);
REPLACE INTO `migrations` VALUES(16,'2026_02_14_120000_create_visitor_analytics_table',4);
REPLACE INTO `migrations` VALUES(17,'2026_02_14_130000_add_website_settings_to_seo_settings',5);
REPLACE INTO `migrations` VALUES(18,'2026_02_14_140000_create_schema_settings_table',6);
REPLACE INTO `migrations` VALUES(19,'2026_02_14_150000_add_claim_review_to_categories',7);
REPLACE INTO `migrations` VALUES(20,'2026_02_14_160000_add_claim_review_to_news',8);
REPLACE INTO `migrations` VALUES(21,'2026_02_14_170000_create_live_streams_table',9);
REPLACE INTO `migrations` VALUES(22,'2026_02_14_180000_create_stream_comments_table',10);
REPLACE INTO `migrations` VALUES(23,'2026_02_03_150000_add_recaptcha_settings_to_seo_settings_table',11);
REPLACE INTO `migrations` VALUES(24,'2026_02_14_create_comments_table',12);
REPLACE INTO `migrations` VALUES(25,'2026_02_14_add_facebook_columns_to_users_table',13);
REPLACE INTO `migrations` VALUES(26,'2026_02_14_create_push_subscriptions_table',14);
REPLACE INTO `migrations` VALUES(27,'2026_02_15_add_vapid_keys_to_seo_settings',15);

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` DATETIME,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100),
  `created_at` DATETIME,
  `updated_at` DATETIME,
  `phone` VARCHAR(255),
  `avatar` VARCHAR(255),
  `bio` LONGTEXT,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `two_factor_enabled` TINYINT(1) NOT NULL DEFAULT '0',
  `two_factor_secret` VARCHAR(255),
  `last_login_at` DATETIME,
  `last_login_ip` VARCHAR(45),
  `facebook_user_id` VARCHAR(255),
  `profile_photo_path` VARCHAR(255),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_facebook_user_id_unique` (`facebook_user_id`)
);

REPLACE INTO `users` VALUES(1,'Test Admin','admin@test.com',NULL,'$2y$12$L7WAu2JE7octASS6Gd4MJetgR63NwjI9rWJGG/8VsOrX1CV1oAtVm','A1SAtJ5OfNiaSBcrrQ9GX4ta4yUjA4akUPLN2g6UlxfoJNgUXtKXNtw3wsqI','2026-02-03 11:08:52','2026-02-03 11:08:52',NULL,NULL,NULL,1,0,NULL,NULL,NULL,NULL,NULL);
REPLACE INTO `users` VALUES(2,'Admin User','admin@sajeb-news.local','2026-02-03 11:08:52','$2y$12$6LmPhIlJFGh9qiHvIz..LeK5ryPGB6U4.WmLIP6ONhGsEDfG9qLpW','1cdpFEdRvI','2026-02-03 11:08:52','2026-02-03 11:08:52',NULL,NULL,NULL,1,0,NULL,NULL,NULL,NULL,NULL);
REPLACE INTO `users` VALUES(3,'Editor User','editor@sajeb-news.local','2026-02-03 11:08:52','$2y$12$6LmPhIlJFGh9qiHvIz..LeK5ryPGB6U4.WmLIP6ONhGsEDfG9qLpW','LcIiMb1NDl','2026-02-03 11:08:52','2026-02-03 11:08:52',NULL,NULL,NULL,1,0,NULL,NULL,NULL,NULL,NULL);
REPLACE INTO `users` VALUES(4,'Reporter User','reporter@sajeb-news.local','2026-02-03 11:08:52','$2y$12$6LmPhIlJFGh9qiHvIz..LeK5ryPGB6U4.WmLIP6ONhGsEDfG9qLpW','lLn8aqxbXz','2026-02-03 11:08:52','2026-02-03 11:08:52',NULL,NULL,NULL,1,0,NULL,NULL,NULL,NULL,NULL);
REPLACE INTO `users` VALUES(5,'Admin User','sajeb@test.com',NULL,'$2y$12$65AjojH.xJvhVFh22pRQyuLmDBiRW6PmP2BLniLrm/1beDO15iE/G','xawiSNEu4303hsJZmQUP72YnMkYTjLak5gVg3w7tnPtVoVbNTrdDipd8Om0c','2026-02-14 04:29:05','2026-02-14 07:42:27',NULL,'avatars/zy4O2fQLXQRqMic0bKeQoaWdMBnFWip9CYLztXg5.png','আসদাসদাসদ',1,0,NULL,NULL,NULL,NULL,NULL);

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` DATETIME,
  PRIMARY KEY (`email`)
);

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` INT,
  `ip_address` VARCHAR(45),
  `user_agent` LONGTEXT,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
);

CREATE TABLE IF NOT EXISTS `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` LONGTEXT NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
);

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
);

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` INT NOT NULL,
  `reserved_at` INT,
  `available_at` INT NOT NULL,
  `created_at` INT NOT NULL,
  KEY `jobs_queue_index` (`queue`)
);

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` LONGTEXT,
  `cancelled_at` INT,
  `created_at` INT NOT NULL,
  `finished_at` INT,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` LONGTEXT NOT NULL,
  `queue` LONGTEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
);

CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `model_type` VARCHAR(255),
  `model_id` INT,
  `changes` LONGTEXT,
  `ip_address` VARCHAR(45),
  `user_agent` VARCHAR(255),
  `created_at` DATETIME,
  `updated_at` DATETIME,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  KEY `activity_logs_user_id_created_at_index` (`user_id`, `created_at`),
  KEY `activity_logs_model_type_index` (`model_type`)
);

REPLACE INTO `activity_logs` VALUES(1,5,'created','LiveStream',1,'{\"title\":\"sadasd\",\"status\":\"pending\",\"stream_key\":\"62df7184d9***\"}','127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-02-14 10:36:35','2026-02-14 10:36:35');
REPLACE INTO `activity_logs` VALUES(2,5,'started','LiveStream',1,'{\"title\":\"sadasd\",\"started_at\":\"2026-02-14T10:41:07.000000Z\"}','127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-02-14 10:41:07','2026-02-14 10:41:07');
REPLACE INTO `activity_logs` VALUES(3,5,'stopped','LiveStream',1,'{\"title\":\"sadasd\",\"ended_at\":\"2026-02-14T10:43:35.000000Z\",\"duration\":\"02:28\"}','127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-02-14 10:43:35','2026-02-14 10:43:35');
REPLACE INTO `activity_logs` VALUES(4,5,'created','LiveStream',2,'{\"title\":\"সাজেব\",\"status\":\"pending\",\"stream_key\":\"78b38da68a***\"}','127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','2026-02-14 10:52:05','2026-02-14 10:52:05');

CREATE TABLE IF NOT EXISTS `advertisements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `code` LONGTEXT,
  `type` VARCHAR(50) NOT NULL DEFAULT 'banner',
  `device_target` VARCHAR(50) NOT NULL DEFAULT 'all',
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME,
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `impressions` INT NOT NULL DEFAULT '0',
  `clicks` INT NOT NULL DEFAULT '0',
  `created_by` INT NOT NULL,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  KEY `advertisements_type_index` (`type`),
  KEY `advertisements_is_active_index` (`is_active`)
);

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `description` LONGTEXT,
  `parent_id` INT,
  `icon` VARCHAR(255),
  `meta_title` VARCHAR(255),
  `meta_description` LONGTEXT,
  `meta_keywords` LONGTEXT,
  `order` INT NOT NULL DEFAULT '0',
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME,
  `updated_at` DATETIME,
  `is_fact_checker` TINYINT(1) NOT NULL DEFAULT '0',
  `claim_review_enabled` TINYINT(1) NOT NULL DEFAULT '0',
  `claim_rating_scale` VARCHAR(50),
  `claim_reviewer_name` VARCHAR(255),
  `claim_reviewer_url` VARCHAR(255),
  FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
  KEY `categories_slug_index` (`slug`),
  KEY `categories_parent_id_index` (`parent_id`)
);

REPLACE INTO `categories` VALUES(1,'politics','politics','asdsad',NULL,NULL,NULL,NULL,NULL,0,1,'2026-02-03 11:36:54','2026-02-03 11:36:54',0,0,NULL,NULL,NULL);
REPLACE INTO `categories` VALUES(2,'Fact Checker','fact-checker','Fact-checking articles where we verify claims, debunk false information, and provide truth ratings for various news and statements.',NULL,NULL,NULL,NULL,NULL,0,1,'2026-02-14 09:31:56','2026-02-14 09:31:56',1,1,'True','Sajeb News Fact Check Team','http://localhost');

CREATE TABLE IF NOT EXISTS `news` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` LONGTEXT NOT NULL,
  `excerpt` LONGTEXT,
  `featured_image` VARCHAR(255),
  `category_id` INT NOT NULL,
  `author_id` INT NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'draft',
  `is_featured` TINYINT(1) NOT NULL DEFAULT '0',
  `is_breaking` TINYINT(1) NOT NULL DEFAULT '0',
  `published_at` DATETIME,
  `scheduled_at` DATETIME,
  `views` INT NOT NULL DEFAULT '0',
  `meta_title` VARCHAR(255),
  `meta_description` LONGTEXT,
  `meta_keywords` LONGTEXT,
  `canonical_url` VARCHAR(255),
  `og_description` LONGTEXT,
  `og_image` VARCHAR(255),
  `twitter_card` VARCHAR(255),
  `reading_time` INT,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  `deleted_at` DATETIME,
  `is_claim_review` TINYINT(1) NOT NULL DEFAULT '0',
  `claim_being_reviewed` LONGTEXT,
  `claim_rating` VARCHAR(50),
  `claim_review_evidence` LONGTEXT,
  `claim_review_date` DATETIME,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`author_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  KEY `news_slug_index` (`slug`),
  KEY `news_status_index` (`status`),
  KEY `news_published_at_index` (`published_at`),
  KEY `news_category_id_index` (`category_id`)
);

REPLACE INTO `news` VALUES(1,'kusdhiuas dih','kusdhiuas-dih',';lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa ;lo ajksdpkkjajdlk adipad po kaopdksopkas pod apso dpo aspdk poa dasopd asikdpoak;dl asopdkopasdop ai daops diiopasd op apdk pioa','ja posd pa','news/BRBs5JDzdGkf6wifLPG7o8WYSENllOySe3isEHGN.png',1,1,'published',1,1,'2026-02-03 17:37:00',NULL,49,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,'2026-02-03 11:37:26','2026-02-14 11:28:51',NULL,0,NULL,NULL,NULL,NULL);
INSERT INTO `news` VALUES(2,'আমার নাম বানাগ্লাদেহস','আমার নাম বানাগ্লাদেহস','আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ्य এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ्य এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ्य এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ्य এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ्य এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ्य এড  আমার নাম বানাগ্লাদেহস আসাদ আসদাস ড আদা  আদ্য এড  আমার नাম ব','আমার নাম বানาগ্লाదেহস আসাদ आसদाস ড आदा  आduय eड  आमार नाम बानाग्नাదेहस','news/ZsP5Kyc8QyOXMU8V4gPWhYfkHqVr9IZMQjdHe1sw.png',1,5,'published',1,1,'2026-02-14 10:41:00',NULL,6,NULL,NULL,NULL,NULL,NULL,NULL,NULL,21,'2026-02-14 04:41:59','2026-02-15 06:02:14',NULL,0,NULL,NULL,NULL,NULL);

CREATE TABLE IF NOT EXISTS `news_analytics` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `news_id` INT NOT NULL,
  `daily_views` INT NOT NULL DEFAULT '0',
  `total_views` INT NOT NULL DEFAULT '0',
  `scroll_depth` INT NOT NULL DEFAULT '0',
  `average_time_on_page` INT NOT NULL DEFAULT '0',
  `bounce_rate` INT NOT NULL DEFAULT '0',
  `social_shares` INT NOT NULL DEFAULT '0',
  `comments_count` INT NOT NULL DEFAULT '0',
  `date` DATE NOT NULL,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  FOREIGN KEY (`news_id`) REFERENCES `news`(`id`) ON DELETE CASCADE,
  KEY `news_analytics_news_id_date_index` (`news_id`, `date`)
);

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `name` VARCHAR(255),
  `phone` VARCHAR(20),
  `is_verified` TINYINT(1) NOT NULL DEFAULT '0',
  `verification_token` VARCHAR(255),
  `verified_at` DATETIME,
  `subscribed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `unsubscribed_at` DATETIME,
  `preferences` LONGTEXT,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  KEY `newsletter_subscribers_email_index` (`email`),
  KEY `newsletter_subscribers_is_verified_index` (`is_verified`)
);

REPLACE INTO `newsletter_subscribers` VALUES(1,'tets@test.com',NULL,NULL,1,NULL,NULL,'2026-02-05 07:36:53',NULL,NULL,'2026-02-05 07:36:53','2026-02-05 07:36:53');

CREATE TABLE IF NOT EXISTS `push_notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `body` LONGTEXT NOT NULL,
  `image` VARCHAR(255),
  `icon` VARCHAR(255),
  `action_url` VARCHAR(255),
  `target_audience` VARCHAR(50) NOT NULL DEFAULT 'all',
  `segments` LONGTEXT,
  `scheduled_at` DATETIME,
  `sent_count` INT NOT NULL DEFAULT '0',
  `click_count` INT NOT NULL DEFAULT '0',
  `is_sent` TINYINT(1) NOT NULL DEFAULT '0',
  `created_by` INT NOT NULL,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  KEY `push_notifications_is_sent_index` (`is_sent`)
);

CREATE TABLE IF NOT EXISTS `push_subscriptions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `endpoint` VARCHAR(512) NOT NULL UNIQUE,
  `public_key` LONGTEXT NOT NULL,
  `auth_token` LONGTEXT NOT NULL,
  `user_ip` VARCHAR(45),
  `user_agent` VARCHAR(500),
  `is_active` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME,
  `updated_at` DATETIME,
  KEY `push_subscriptions_is_active_index` (`is_active`),
  KEY `push_subscriptions_created_at_index` (`created_at`)
);

CREATE TABLE IF NOT EXISTS `seo_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `site_title` VARCHAR(255) NOT NULL,
  `site_description` LONGTEXT NOT NULL,
  `site_keywords` VARCHAR(255),
  `logo` VARCHAR(255),
  `favicon` VARCHAR(255),
  `og_image` VARCHAR(255),
  `twitter_handle` VARCHAR(255),
  `google_analytics_id` VARCHAR(255),
  `google_tag_manager_id` VARCHAR(255),
  `facebook_pixel_id` VARCHAR(255),
  `robots_txt` LONGTEXT,
  `enable_sitemap` TINYINT(1) NOT NULL DEFAULT '1',
  `enable_robots` TINYINT(1) NOT NULL DEFAULT '1',
  `created_at` DATETIME,
  `updated_at` DATETIME,
  `mobile_logo` VARCHAR(255),
  `site_name` VARCHAR(255),
  `site_url` VARCHAR(255),
  `meta_keywords` LONGTEXT,
  `ga_tracking_id` VARCHAR(255),
  `gtm_tracking_id` VARCHAR(255),
  `enable_analytics` TINYINT(1) NOT NULL DEFAULT '1',
  `facebook_url` VARCHAR(255),
  `twitter_url` VARCHAR(255),
  `instagram_url` VARCHAR(255),
  `youtube_url` VARCHAR(255),
  `linkedin_url` VARCHAR(255),
  `tiktok_url` VARCHAR(255),
  `recaptcha_site_key` VARCHAR(255),
  `recaptcha_secret_key` VARCHAR(255),
  `recaptcha_threshold` DECIMAL(3,2) NOT NULL DEFAULT '0.50',
  `recaptcha_enabled` TINYINT(1) NOT NULL DEFAULT '0',
  `vapid_public_key` LONGTEXT,
  `vapid_private_key` LONGTEXT,
  `push_notifications_enabled` TINYINT(1) NOT NULL DEFAULT '0'
);

REPLACE INTO `seo_settings` VALUES(1,'Laravelasa','Bengali News Portal',NULL,'settings/logos/gJXN5Vy9RAvqelzSqUpxYYMoKjLckFxJyxDRGZIP.png',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,'2026-02-14 09:08:58','2026-02-14 09:09:49','settings/logos/ie4TlAaRUXn1IaHCuMfX9hERH68b9MeqZcbniq8g.png','রাজ','http://localhost',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0.50,0,NULL,NULL,0);

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`, `guard_name`)
);

REPLACE INTO `permissions` VALUES(1,'create_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(2,'read_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(3,'update_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(4,'delete_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(5,'publish_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(6,'feature_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(7,'breaking_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(8,'create_category','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(9,'read_category','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(10,'update_category','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(11,'delete_category','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(12,'create_user','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(13,'read_user','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(14,'update_user','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(15,'delete_user','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(16,'manage_advertisements','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(17,'view_ad_analytics','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(18,'manage_newsletter','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(19,'send_newsletter','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(20,'send_push_notifications','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(21,'view_analytics','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(22,'manage_settings','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(23,'manage_roles_permissions','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `permissions` VALUES(24,'view_activity_logs','web','2026-02-03 11:08:52','2026-02-03 11:08:52');

CREATE TABLE IF NOT EXISTS `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  UNIQUE KEY `roles_name_guard_name_unique` (`name`, `guard_name`)
);

REPLACE INTO `roles` VALUES(1,'super-admin','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `roles` VALUES(2,'admin','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `roles` VALUES(3,'editor','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `roles` VALUES(4,'reporter','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
REPLACE INTO `roles` VALUES(5,'author','web','2026-02-03 11:08:52','2026-02-03 11:08:52');

CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` INT NOT NULL,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` INT NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`),
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE,
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`, `model_type`)
);

CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` INT NOT NULL,
  `model_type` VARCHAR(255) NOT NULL,
  `model_id` INT NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE,
  KEY `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`)
);

REPLACE INTO `model_has_roles` VALUES(1,'App\Models\User',1);
REPLACE INTO `model_has_roles` VALUES(1,'App\Models\User',2);
REPLACE INTO `model_has_roles` VALUES(3,'App\Models\User',3);
REPLACE INTO `model_has_roles` VALUES(4,'App\Models\User',4);
REPLACE INTO `model_has_roles` VALUES(2,'App\Models\User',5);

CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` INT NOT NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`),
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
);

REPLACE INTO `role_has_permissions` VALUES(1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1);
REPLACE INTO `role_has_permissions` VALUES(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(16,2),(17,2),(18,2),(20,2),(21,2),(22,2),(23,2),(24,2);
REPLACE INTO `role_has_permissions` VALUES(1,3),(2,3),(3,3),(4,3),(5,3),(6,3),(9,3),(21,3),(24,3);
REPLACE INTO `role_has_permissions` VALUES(1,4),(2,4),(3,4),(9,4);
REPLACE INTO `role_has_permissions` VALUES(1,5),(2,5),(3,5),(9,5);

CREATE TABLE IF NOT EXISTS `tags` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `color` VARCHAR(7) NOT NULL DEFAULT '#6c757d',
  `created_at` DATETIME,
  `updated_at` DATETIME
);

CREATE TABLE IF NOT EXISTS `taggables` (
  `taggable_type` VARCHAR(255) NOT NULL,
  `taggable_id` INT NOT NULL,
  `tag_id` INT NOT NULL,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  UNIQUE KEY `taggables_taggable_id_taggable_type_tag_id_unique` (`taggable_id`, `taggable_type`, `tag_id`),
  FOREIGN KEY (`tag_id`) REFERENCES `tags`(`id`) ON DELETE CASCADE,
  KEY `taggables_taggable_type_taggable_id_index` (`taggable_type`, `taggable_id`)
);

CREATE TABLE IF NOT EXISTS `visitor_analytics` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `news_id` INT NOT NULL,
  `visitor_ip` VARCHAR(45),
  `visitor_country` VARCHAR(100),
  `visitor_city` VARCHAR(100),
  `visitor_device` VARCHAR(50),
  `referrer_source` VARCHAR(100),
  `referrer_url` VARCHAR(500),
  `time_spent_seconds` INT NOT NULL DEFAULT '0',
  `scroll_percentage` INT NOT NULL DEFAULT '0',
  `completed_reading` TINYINT(1) NOT NULL DEFAULT '0',
  `browser` VARCHAR(100),
  `os` VARCHAR(100),
  `visit_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  FOREIGN KEY (`news_id`) REFERENCES `news`(`id`) ON DELETE CASCADE,
  KEY `visitor_analytics_news_id_visit_date_index` (`news_id`, `visit_date`),
  KEY `visitor_analytics_referrer_source_index` (`referrer_source`)
);

CREATE TABLE IF NOT EXISTS `schema_settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `enable_news_article_schema` TINYINT(1) NOT NULL DEFAULT '1',
  `enable_organization_schema` TINYINT(1) NOT NULL DEFAULT '1',
  `enable_website_schema` TINYINT(1) NOT NULL DEFAULT '1',
  `enable_breadcrumb_schema` TINYINT(1) NOT NULL DEFAULT '1',
  `enable_person_schema` TINYINT(1) NOT NULL DEFAULT '1',
  `enable_image_object_schema` TINYINT(1) NOT NULL DEFAULT '1',
  `enable_video_object_schema` TINYINT(1) NOT NULL DEFAULT '0',
  `enable_live_blog_schema` TINYINT(1) NOT NULL DEFAULT '0',
  `enable_faq_schema` TINYINT(1) NOT NULL DEFAULT '0',
  `enable_job_posting_schema` TINYINT(1) NOT NULL DEFAULT '0',
  `enable_event_schema` TINYINT(1) NOT NULL DEFAULT '0',
  `enable_claim_review_schema` TINYINT(1) NOT NULL DEFAULT '0',
  `organization_name` VARCHAR(255),
  `organization_description` LONGTEXT,
  `contact_email` VARCHAR(255),
  `contact_phone` VARCHAR(20),
  `contact_type` VARCHAR(100) NOT NULL DEFAULT 'Customer Service',
  `created_at` DATETIME,
  `updated_at` DATETIME
);

REPLACE INTO `schema_settings` VALUES(1,1,1,1,1,1,1,1,1,1,1,1,1,NULL,NULL,NULL,NULL,'Customer Service','2026-02-14 09:26:18','2026-02-14 11:33:48');

CREATE TABLE IF NOT EXISTS `live_streams` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` LONGTEXT,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `status` VARCHAR(50) NOT NULL DEFAULT 'draft',
  `thumbnail` VARCHAR(255),
  `stream_key` VARCHAR(255) NOT NULL UNIQUE,
  `stream_url` VARCHAR(255),
  `visibility` VARCHAR(50) NOT NULL DEFAULT 'public',
  `viewer_count` INT NOT NULL DEFAULT '0',
  `peak_viewers` INT NOT NULL DEFAULT '0',
  `scheduled_at` DATETIME,
  `started_at` DATETIME,
  `ended_at` DATETIME,
  `duration_seconds` DECIMAL(10,4) NOT NULL DEFAULT '0',
  `stream_tags` LONGTEXT,
  `category` VARCHAR(100),
  `allow_comments` TINYINT(1) NOT NULL DEFAULT '1',
  `allow_chat` TINYINT(1) NOT NULL DEFAULT '1',
  `recording_url` VARCHAR(255),
  `is_featured` TINYINT(1) NOT NULL DEFAULT '0',
  `view_count` INT NOT NULL DEFAULT '0',
  `like_count` INT NOT NULL DEFAULT '0',
  `created_at` DATETIME,
  `updated_at` DATETIME,
  `deleted_at` DATETIME,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  KEY `live_streams_status_index` (`status`),
  KEY `live_streams_user_id_index` (`user_id`),
  KEY `live_streams_started_at_index` (`started_at`),
  KEY `live_streams_is_featured_index` (`is_featured`)
);

REPLACE INTO `live_streams` VALUES(1,5,'sadasd','asdasd','sadasd','ended','thumbnails/live-streams/xEpSvCusMezGdWsiJ2jpV6Wz97IXw7EJx16aKBhp.png','62df7184d99eac6c5026d8d6f7d9fc65',NULL,'public',0,0,'2026-02-14 16:36:00','2026-02-14 10:41:07','2026-02-14 10:43:35',148.4120,NULL,'Technology',1,1,NULL,0,0,0,'2026-02-14 10:36:35','2026-02-14 10:43:35',NULL);
REPLACE INTO `live_streams` VALUES(2,5,'সাজেব','dfsf','sajeb','pending','thumbnails/live-streams/Md1DzdcUlxH32IfiQQMbVAXPluWFoGpckYk2RItK.png','78b38da68a363543386e84d854da547d',NULL,'public',0,0,'2026-02-14 16:51:00',NULL,NULL,0,NULL,'News',1,1,NULL,0,5,0,'2026-02-14 10:52:05','2026-02-14 10:58:07',NULL);

CREATE TABLE IF NOT EXISTS `stream_comments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `live_stream_id` INT NOT NULL,
  `commenter_name` VARCHAR(255) NOT NULL,
  `commenter_email` VARCHAR(255),
  `facebook_id` VARCHAR(255) UNIQUE,
  `facebook_profile_url` VARCHAR(500),
  `commenter_avatar` VARCHAR(255),
  `comment_text` LONGTEXT NOT NULL,
  `source` VARCHAR(50) NOT NULL DEFAULT 'website',
  `is_verified` TINYINT(1) NOT NULL DEFAULT '0',
  `is_pinned` TINYINT(1) NOT NULL DEFAULT '0',
  `likes_count` INT NOT NULL DEFAULT '0',
  `is_approved` TINYINT(1) NOT NULL DEFAULT '1',
  `admin_notes` LONGTEXT,
  `created_at` DATETIME,
  `updated_at` DATETIME,
  `deleted_at` DATETIME,
  FOREIGN KEY (`live_stream_id`) REFERENCES `live_streams`(`id`) ON DELETE CASCADE,
  KEY `stream_comments_live_stream_id_index` (`live_stream_id`),
  KEY `stream_comments_facebook_id_index` (`facebook_id`),
  KEY `stream_comments_created_at_index` (`created_at`),
  KEY `stream_comments_is_pinned_index` (`is_pinned`)
);

CREATE TABLE IF NOT EXISTS `comments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `news_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `facebook_user_id` VARCHAR(255),
  `comment_text` LONGTEXT NOT NULL,
  `approved` TINYINT(1) NOT NULL DEFAULT '0',
  `spam_score` DECIMAL(3,2) NOT NULL DEFAULT '0',
  `recaptcha_score` DECIMAL(3,2),
  `created_at` DATETIME,
  `updated_at` DATETIME,
  FOREIGN KEY (`news_id`) REFERENCES `news`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  KEY `comments_news_id_index` (`news_id`),
  KEY `comments_user_id_index` (`user_id`),
  KEY `comments_approved_index` (`approved`),
  KEY `comments_created_at_index` (`created_at`)
);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Database export completed successfully!
-- File size: Ready for MySQL 5.7+ import
-- Import Instructions:
-- 1. Create a new MySQL database: CREATE DATABASE sajeb_news;
-- 2. Use the database: USE sajeb_news;
-- 3. Import this file: mysql -u root -p sajeb_news < sajeb-news-database.sql
-- 4. Update your .env file with MySQL credentials
-- 5. Run: php artisan migrate:fresh
