PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "migrations" ("id" integer primary key autoincrement not null, "migration" varchar not null, "batch" integer not null);
INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2026_02_03_104900_create_activity_logs_table',1);
INSERT INTO migrations VALUES(5,'2026_02_03_104900_create_advertisements_table',1);
INSERT INTO migrations VALUES(6,'2026_02_03_104900_create_categories_table',1);
INSERT INTO migrations VALUES(7,'2026_02_03_104900_create_news_analytics_table',1);
INSERT INTO migrations VALUES(8,'2026_02_03_104900_create_news_table',1);
INSERT INTO migrations VALUES(9,'2026_02_03_104900_create_newsletter_subscribers_table',1);
INSERT INTO migrations VALUES(10,'2026_02_03_104900_create_push_notifications_table',1);
INSERT INTO migrations VALUES(11,'2026_02_03_104900_create_seo_settings_table',1);
INSERT INTO migrations VALUES(12,'2026_02_03_105111_add_fields_to_users_table',1);
INSERT INTO migrations VALUES(13,'2026_02_03_105159_create_permission_tables',1);
INSERT INTO migrations VALUES(14,'2026_02_03_104901_create_tags_table',2);
INSERT INTO migrations VALUES(15,'2026_02_03_120000_add_mobile_logo_to_seo_settings',3);
INSERT INTO migrations VALUES(16,'2026_02_14_120000_create_visitor_analytics_table',4);
INSERT INTO migrations VALUES(17,'2026_02_14_130000_add_website_settings_to_seo_settings',5);
INSERT INTO migrations VALUES(18,'2026_02_14_140000_create_schema_settings_table',6);
INSERT INTO migrations VALUES(19,'2026_02_14_150000_add_claim_review_to_categories',7);
INSERT INTO migrations VALUES(20,'2026_02_14_160000_add_claim_review_to_news',8);
INSERT INTO migrations VALUES(21,'2026_02_14_170000_create_live_streams_table',9);
INSERT INTO migrations VALUES(22,'2026_02_14_180000_create_stream_comments_table',10);
INSERT INTO migrations VALUES(23,'2026_02_03_150000_add_recaptcha_settings_to_seo_settings_table',11);
INSERT INTO migrations VALUES(24,'2026_02_14_create_comments_table',12);
INSERT INTO migrations VALUES(25,'2026_02_14_add_facebook_columns_to_users_table',13);
INSERT INTO migrations VALUES(26,'2026_02_14_create_push_subscriptions_table',14);
INSERT INTO migrations VALUES(27,'2026_02_15_add_vapid_keys_to_seo_settings',15);
INSERT INTO migrations VALUES(28,'2026_02_16_add_featured_order_to_categories',16);
INSERT INTO migrations VALUES(29,'2026_02_19_000001_add_missing_advertisement_columns',17);
INSERT INTO migrations VALUES(30,'2026_02_16_064923_create_sessions_table',18);
INSERT INTO migrations VALUES(31,'2026_02_19_000000_enhance_advertisements_table',18);
INSERT INTO migrations VALUES(32,'2026_02_19_000002_add_adsense_fields_to_advertisements',19);
INSERT INTO migrations VALUES(33,'2026_02_19_000003_add_ad_network_support',20);
INSERT INTO migrations VALUES(34,'2026_02_19_000004_remove_adsense_from_ad_type_enum',21);
INSERT INTO migrations VALUES(35,'2026_02_19_000005_add_ad_source_to_advertisements',22);
CREATE TABLE IF NOT EXISTS "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime, "phone" varchar, "avatar" varchar, "bio" text, "is_active" tinyint(1) not null default '1', "two_factor_enabled" tinyint(1) not null default '0', "two_factor_secret" varchar, "last_login_at" datetime, "last_login_ip" varchar, "facebook_user_id" varchar, "profile_photo_path" varchar);
INSERT INTO users VALUES(11,'Admin User','admin@example.com','2026-02-16 08:27:09','$2y$12$klj0COXlQ4mFSgp.s/zTdO87VmQ8a5wyBXUviXNturxg5n6.7LDmu','Wie4tSgKiIYYm1kgVbjUjLSi77yCX43mBHpWufAnXGaXyyeHx1Strm9tb8oW','2026-02-16 08:27:09','2026-02-16 11:45:44',NULL,'avatars/cJaCcVajFSDqTIP9kjpkA7eTUnRKC9PyNvKwgT3m.png','amadiasd asdp japlsd jp',1,0,NULL,NULL,NULL,NULL,NULL);
INSERT INTO users VALUES(12,'test','test@test.com',NULL,'$2y$12$.SWhCk2/o6w0vccB/j68eONofL9YGpVfNQEuwXaaE5dwYad140hjq',NULL,'2026-02-16 08:44:48','2026-02-16 08:44:48',NULL,NULL,NULL,1,0,NULL,NULL,NULL,NULL,NULL);
CREATE TABLE IF NOT EXISTS "password_reset_tokens" ("email" varchar not null, "token" varchar not null, "created_at" datetime, primary key ("email"));
CREATE TABLE IF NOT EXISTS "sessions" ("id" varchar not null, "user_id" integer, "ip_address" varchar, "user_agent" text, "payload" text not null, "last_activity" integer not null, primary key ("id"));
INSERT INTO sessions VALUES('Lf9NHlQUhA3xGzMLNWMozAIF3GMZV2jz4yNcXE3M',5,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQkx4Y0VQVG50NEI4NkZ4YjNrd05GNnBKMVJwemFHYlRGTTJtWjBxTCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2VycyI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4udXNlcnMuaW5kZXgiO31zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTt9',1771044507);
INSERT INTO sessions VALUES('2wcAoc2A8XU5MmuansQGg1QGtRwoSLqDcmol6HVA',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNU9PYTVFa1RxWTdrSHNkNkZQY3Rrb3cxWVp1SVYycnBCVnpjNkQ5dCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbj9pZD1mZDBkZDhjYy02ZDY4LTRmMTQtODQ4YS1jNzY1OTQyOWQ3NDImdnNjb2RlQnJvd3NlclJlcUlkPTE3NzEwNDM0NzE2MDIiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoxMDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbj9pZD1mZDBkZDhjYy02ZDY4LTRmMTQtODQ4YS1jNzY1OTQyOWQ3NDImdnNjb2RlQnJvd3NlclJlcUlkPTE3NzEwNDM0NzE2MDIiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771043471);
INSERT INTO sessions VALUES('yVxnKyEMTBR61ahWAzgMEeefvVZzBXwR25t3S7NK',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNDFjUTRRVmJVcTN3QVM0ZlRXS3N6dXo3eHdiNkFUTGx3d2JEbkZEYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771043471);
INSERT INTO sessions VALUES('UHlfYCnJjML3rfpFy8kE6hgk3dPLWbp0GRlbuq4V',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoialBFYUpoQ3MwTXE2Mkhvcnc4TXRqNlBVdFZ5MGx4UkRKeDZhQ2NkUiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbj9pZD1mZDBkZDhjYy02ZDY4LTRmMTQtODQ4YS1jNzY1OTQyOWQ3NDImdnNjb2RlQnJvd3NlclJlcUlkPTE3NzEwNDM0NzIwOTkiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoxMDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbj9pZD1mZDBkZDhjYy02ZDY4LTRmMTQtODQ4YS1jNzY1OTQyOWQ3NDImdnNjb2RlQnJvd3NlclJlcUlkPTE3NzEwNDM0NzIwOTkiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771043472);
INSERT INTO sessions VALUES('ZEE9riOmKa5jlEboXH5PGli7mEoCWKbmAIJnUpu5',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiejZzOFJkYWZLczRXT04za2tOZ3RDZEhIVGNYZmZmS3VHc2I1clMwcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771043472);
INSERT INTO sessions VALUES('pfmV0FTJz5YBS9hFFODTEKiN6GtOBuBaXqa2ZMbv',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoidk5kVHdvNHFZa1FMb0FzV2NOVExoR21pc1FxUVRyMEt4UXVGVjlzbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771043488);
INSERT INTO sessions VALUES('GjbfXqHerB70UiTPRI8QqTb367Cf4zWIEbScQw6s',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWE1GblRKZFpBNE5uc1NVdE55VFE4N0RGMDBSOFo3UE01M0QwZUdFdCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxMDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbj9pZD1mZDBkZDhjYy02ZDY4LTRmMTQtODQ4YS1jNzY1OTQyOWQ3NDImdnNjb2RlQnJvd3NlclJlcUlkPTE3NzEwNDM3NDU0NzUiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoxMDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9hZG1pbj9pZD1mZDBkZDhjYy02ZDY4LTRmMTQtODQ4YS1jNzY1OTQyOWQ3NDImdnNjb2RlQnJvd3NlclJlcUlkPTE3NzEwNDM3NDU0NzUiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771043745);
INSERT INTO sessions VALUES('x9s0TpjhsABLxuSiY3iJ4NDRI36p0SiYzLpvM0aM',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOEJmd2tXb21YUjN4bGYzUXEzajVtQmlDNzFIaFlrS0NNVWhjREY5eSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMS9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771043745);
INSERT INTO sessions VALUES('9WkhlwkRh9exFuhERQ1iZ3SD2NaPPk19iYxJj5iU',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZk4wWm5Pc0h4dDBlS0VuSG1PaFUzaVlVWEVkMzRoenNnOEh3MzZMbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTAwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvbG9naW4/aWQ9ZmQwZGQ4Y2MtNmQ2OC00ZjE0LTg0OGEtYzc2NTk0MjlkNzQyJnZzY29kZUJyb3dzZXJSZXFJZD0xNzcxMDQzNzUyMzExIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771043752);
INSERT INTO sessions VALUES('fRkeIiQYoHEHud31TOhWczdSN0whPvmkzsMw5IzM',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoicE1zMlNkYU1VOGpPc0RsSDZnZHlrcW9EYTh4V1pRbXVsVTlNaGJwTCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771043761);
INSERT INTO sessions VALUES('LiNAMtUxMevoxOISzGXdXH4Cv7CNzeClETJstrAk',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUWdoVmVkQXVXNDdsR3h4T3dqM2Jlb2QyT2ZONVJPb0FmVHVxOWRvTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTAwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvbG9naW4/aWQ9ZmQwZGQ4Y2MtNmQ2OC00ZjE0LTg0OGEtYzc2NTk0MjlkNzQyJnZzY29kZUJyb3dzZXJSZXFJZD0xNzcxMDQ0NDkxNTM1IjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771044491);
INSERT INTO sessions VALUES('6JwyV7ootR0gimQ2LJTnT5yZOAlPPjZU39AL9aVA',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNElLc1l1ZUdZY0NHalFIUzZBUGZnWXo1UDVZbXREckhvdklvcWhBUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTAwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvbG9naW4/aWQ9ZmQwZGQ4Y2MtNmQ2OC00ZjE0LTg0OGEtYzc2NTk0MjlkNzQyJnZzY29kZUJyb3dzZXJSZXFJZD0xNzcxMDQ0NDkyMjk2IjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771044492);
INSERT INTO sessions VALUES('JUbYvncpvTz7D10np3X24REsWZr8skSetQoaycvq',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmRVdmxzaEREQ1VOaXc3Rm5kY2QwcllkRXROQWpSSGdpdmZLbDhSYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTAwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvbG9naW4/aWQ9ZmQwZGQ4Y2MtNmQ2OC00ZjE0LTg0OGEtYzc2NTk0MjlkNzQyJnZzY29kZUJyb3dzZXJSZXFJZD0xNzcxMDQ0NTIxMDkxIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771044521);
INSERT INTO sessions VALUES('ELtaBk18B0ain8I2k7oNxncZvWLcsXbrofauTpdX',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQlJ5Y3dJbno1UDREMG9QdWtseHRpNGNXdmR5QzlLVmM0d1lUSktzUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTAwOiJodHRwOi8vMTI3LjAuMC4xOjgwMDEvbG9naW4/aWQ9ZmQwZGQ4Y2MtNmQ2OC00ZjE0LTg0OGEtYzc2NTk0MjlkNzQyJnZzY29kZUJyb3dzZXJSZXFJZD0xNzcxMDQ0NTIxNjI3IjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771044521);
INSERT INTO sessions VALUES('FHsZm8aKuoc1LWN78sBKcZPtNW2BRJhq4I1h4rTe',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.103.2 Chrome/138.0.7204.100 Electron/37.2.3 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoibGdIa2c1MjNteXVzcE5aRmFsS0FXMGZxRzlodjk4YkZhdjB1NzM0QyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771044545);
INSERT INTO sessions VALUES('IMj0WMzuHqcTBRqzIdp8PReiVKWs3MKB7bg0RFFN',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.109.3 Chrome/142.0.7444.265 Electron/39.3.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNmR0SGFJU2pKRm9WaVdVdjVGUTJINGppVkVEN05CY0cwRVNlTUFKZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6OTU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/aWQ9YzQyOGY0MmUtOTQwZi00NDkxLWI3OTAtMWRkZGRiYjZiMzBmJnZzY29kZUJyb3dzZXJSZXFJZD0xNzcxMjI0NDk0MjI5IjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1771224494);
INSERT INTO sessions VALUES('9SDCVjQBEYuN27btLQOQkmfrVvnveFWxJyYmIlsB',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.109.3 Chrome/142.0.7444.265 Electron/39.3.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiODlSVFhleEM1UG9TMTk3WTVzRGRBNUtnZUJHaUtqN3duZDVlSnc2biI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvbGl2ZS9hY3RpdmUiO3M6NToicm91dGUiO3M6MTU6ImFwaS5saXZlLmFjdGl2ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771224494);
INSERT INTO sessions VALUES('Xnh7RyuQYJuvmKGhE6f9yYVtSQBhD2V1Z1hKQtBa',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.109.3 Chrome/142.0.7444.265 Electron/39.3.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWhIUE9sbVFUYlpYRzZaMXNvdkxFSDBmb1pOY1dxdW9tdHBwdHlzRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771224498);
INSERT INTO sessions VALUES('0aesFzqUCyPJO5o8CBS8YwCzG2MdFzjJs4SDQnEU',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.109.3 Chrome/142.0.7444.265 Electron/39.3.0 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoidEdQdzFROEVZRkExcDZtM0ZDdmRBQlVKbTcyUzdXb3dweTRGbnBVcSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1771224516);
CREATE TABLE IF NOT EXISTS "cache" ("key" varchar not null, "value" text not null, "expiration" integer not null, primary key ("key"));
INSERT INTO cache VALUES('sajeb-news-cache-spatie.permission.cache','a:3:{s:5:"alias";a:4:{s:1:"a";s:2:"id";s:1:"b";s:4:"name";s:1:"c";s:10:"guard_name";s:1:"r";s:5:"roles";}s:11:"permissions";a:24:{i:0;a:4:{s:1:"a";i:1;s:1:"b";s:11:"create_news";s:1:"c";s:3:"web";s:1:"r";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;}}i:1;a:4:{s:1:"a";i:2;s:1:"b";s:9:"read_news";s:1:"c";s:3:"web";s:1:"r";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;}}i:2;a:4:{s:1:"a";i:3;s:1:"b";s:11:"update_news";s:1:"c";s:3:"web";s:1:"r";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;}}i:3;a:4:{s:1:"a";i:4;s:1:"b";s:11:"delete_news";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:4;a:4:{s:1:"a";i:5;s:1:"b";s:12:"publish_news";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:5;a:4:{s:1:"a";i:6;s:1:"b";s:12:"feature_news";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:6;a:4:{s:1:"a";i:7;s:1:"b";s:13:"breaking_news";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:"a";i:8;s:1:"b";s:15:"create_category";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:"a";i:9;s:1:"b";s:13:"read_category";s:1:"c";s:3:"web";s:1:"r";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;}}i:9;a:4:{s:1:"a";i:10;s:1:"b";s:15:"update_category";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:"a";i:11;s:1:"b";s:15:"delete_category";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:"a";i:12;s:1:"b";s:11:"create_user";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:"a";i:13;s:1:"b";s:9:"read_user";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:"a";i:14;s:1:"b";s:11:"update_user";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:"a";i:15;s:1:"b";s:11:"delete_user";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:"a";i:16;s:1:"b";s:21:"manage_advertisements";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:4:{s:1:"a";i:17;s:1:"b";s:17:"view_ad_analytics";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:"a";i:18;s:1:"b";s:17:"manage_newsletter";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:18;a:4:{s:1:"a";i:19;s:1:"b";s:15:"send_newsletter";s:1:"c";s:3:"web";s:1:"r";a:1:{i:0;i:1;}}i:19;a:4:{s:1:"a";i:20;s:1:"b";s:23:"send_push_notifications";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:"a";i:21;s:1:"b";s:14:"view_analytics";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:21;a:4:{s:1:"a";i:22;s:1:"b";s:15:"manage_settings";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:22;a:4:{s:1:"a";i:23;s:1:"b";s:24:"manage_roles_permissions";s:1:"c";s:3:"web";s:1:"r";a:2:{i:0;i:1;i:1;i:2;}}i:23;a:4:{s:1:"a";i:24;s:1:"b";s:18:"view_activity_logs";s:1:"c";s:3:"web";s:1:"r";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}}s:5:"roles";a:5:{i:0;a:3:{s:1:"a";i:1;s:1:"b";s:11:"super-admin";s:1:"c";s:3:"web";}i:1;a:3:{s:1:"a";i:2;s:1:"b";s:5:"admin";s:1:"c";s:3:"web";}i:2;a:3:{s:1:"a";i:3;s:1:"b";s:6:"editor";s:1:"c";s:3:"web";}i:3;a:3:{s:1:"a";i:4;s:1:"b";s:8:"reporter";s:1:"c";s:3:"web";}i:4;a:3:{s:1:"a";i:5;s:1:"b";s:6:"author";s:1:"c";s:3:"web";}}}',1771562122);
CREATE TABLE IF NOT EXISTS "cache_locks" ("key" varchar not null, "owner" varchar not null, "expiration" integer not null, primary key ("key"));
CREATE TABLE IF NOT EXISTS "jobs" ("id" integer primary key autoincrement not null, "queue" varchar not null, "payload" text not null, "attempts" integer not null, "reserved_at" integer, "available_at" integer not null, "created_at" integer not null);
CREATE TABLE IF NOT EXISTS "job_batches" ("id" varchar not null, "name" varchar not null, "total_jobs" integer not null, "pending_jobs" integer not null, "failed_jobs" integer not null, "failed_job_ids" text not null, "options" text, "cancelled_at" integer, "created_at" integer not null, "finished_at" integer, primary key ("id"));
CREATE TABLE IF NOT EXISTS "failed_jobs" ("id" integer primary key autoincrement not null, "uuid" varchar not null, "connection" text not null, "queue" text not null, "payload" text not null, "exception" text not null, "failed_at" datetime not null default CURRENT_TIMESTAMP);
CREATE TABLE IF NOT EXISTS "activity_logs" ("id" integer primary key autoincrement not null, "user_id" integer not null, "action" varchar not null, "model_type" varchar, "model_id" integer, "changes" text, "ip_address" varchar, "user_agent" varchar, "created_at" datetime, "updated_at" datetime, foreign key("user_id") references "users"("id") on delete cascade);
INSERT INTO activity_logs VALUES(5,11,'created','News',3,'"{\"title\":\"\\u098f\\u0995\\u09cd\\u09b8\\u099a\\u09b8\\u0995\\u09cd\\u09b8\\u09af\\u099a\\u09af\\u0995\\u09cd\\u09b8\\u099a\",\"category\":\"politics\",\"status\":\"published\"}"','127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','2026-02-16 09:18:41','2026-02-16 09:18:41');
INSERT INTO activity_logs VALUES(6,11,'created','LiveStream',3,'"{\"title\":\"\\u09b8\\u09a6\\u09be\\u0986\\u09a6\",\"status\":\"pending\",\"stream_key\":\"363eca67a2***\"}"','127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','2026-02-18 12:13:27','2026-02-18 12:13:27');
INSERT INTO activity_logs VALUES(7,11,'started','LiveStream',3,'"{\"title\":\"\\u09b8\\u09a6\\u09be\\u0986\\u09a6\",\"started_at\":\"2026-02-18T12:13:29.000000Z\"}"','127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36 Edg/145.0.0.0','2026-02-18 12:13:29','2026-02-18 12:13:29');
CREATE TABLE IF NOT EXISTS "categories" ("id" integer primary key autoincrement not null, "name" varchar not null, "slug" varchar not null, "description" text, "parent_id" integer, "icon" varchar, "meta_title" varchar, "meta_description" text, "meta_keywords" text, "order" integer not null default '0', "is_active" tinyint(1) not null default '1', "created_at" datetime, "updated_at" datetime, "is_fact_checker" tinyint(1) not null default '0', "claim_review_enabled" tinyint(1) not null default '0', "claim_rating_scale" varchar check ("claim_rating_scale" in ('True', 'Mostly True', 'Partly False', 'False', 'Unproven')), "claim_reviewer_name" varchar, "claim_reviewer_url" varchar, "featured_order" integer, foreign key("parent_id") references "categories"("id") on delete cascade);
INSERT INTO categories VALUES(1,'politics','politics','asdsad',NULL,NULL,NULL,NULL,NULL,0,1,'2026-02-03 11:36:54','2026-02-03 11:36:54',0,0,NULL,NULL,NULL,NULL);
INSERT INTO categories VALUES(2,'Fact Checker','fact-checker','Fact-checking articles where we verify claims, debunk false information, and provide truth ratings for various news and statements.',NULL,NULL,NULL,NULL,NULL,0,1,'2026-02-14 09:31:56','2026-02-14 09:31:56',1,1,'True','Sajeb News Fact Check Team','http://localhost',NULL);
INSERT INTO categories VALUES(3,'international','international',NULL,NULL,NULL,NULL,NULL,NULL,0,1,'2026-02-16 09:01:39','2026-02-16 11:08:21',0,0,NULL,NULL,NULL,1);
CREATE TABLE IF NOT EXISTS "news_analytics" ("id" integer primary key autoincrement not null, "news_id" integer not null, "daily_views" integer not null default '0', "total_views" integer not null default '0', "scroll_depth" integer not null default '0', "average_time_on_page" integer not null default '0', "bounce_rate" integer not null default '0', "social_shares" integer not null default '0', "comments_count" integer not null default '0', "date" date not null, "created_at" datetime, "updated_at" datetime, foreign key("news_id") references "news"("id") on delete cascade);
CREATE TABLE IF NOT EXISTS "news" ("id" integer primary key autoincrement not null, "title" varchar not null, "slug" varchar not null, "content" text not null, "excerpt" text, "featured_image" varchar, "category_id" integer not null, "author_id" integer not null, "status" varchar check ("status" in ('draft', 'published', 'scheduled', 'archived')) not null default 'draft', "is_featured" tinyint(1) not null default '0', "is_breaking" tinyint(1) not null default '0', "published_at" datetime, "scheduled_at" datetime, "views" integer not null default '0', "meta_title" varchar, "meta_description" text, "meta_keywords" text, "canonical_url" varchar, "og_description" text, "og_image" varchar, "twitter_card" varchar, "reading_time" integer, "created_at" datetime, "updated_at" datetime, "deleted_at" datetime, "is_claim_review" tinyint(1) not null default '0', "claim_being_reviewed" text, "claim_rating" varchar check ("claim_rating" in ('True', 'Mostly True', 'Partly False', 'False', 'Unproven')), "claim_review_evidence" text, "claim_review_date" datetime, foreign key("category_id") references "categories"("id") on delete cascade, foreign key("author_id") references "users"("id") on delete cascade);
INSERT INTO news VALUES(3,'এক্সচসক্সযচযক্সচ','দসাসদ','<p><span class="ql-font-serif-bengali">আসদাসদ আসদাসদ  এড আস দাসদ এড এ আস এ এ </span>আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ আসদাসদ আসদাসদ এড আস দাসদ এড এ আস এ এ </p>','আসদসাদ  আদস আশ্বাস','news/gyZkrFkjE5D9E1i2MQjzMoCaau7Z8AefN3XAvn8q.png',1,11,'published',1,1,'2026-02-16 15:18:00',NULL,37,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,'2026-02-16 09:18:41','2026-02-19 08:06:58',NULL,0,NULL,NULL,NULL,NULL);
CREATE TABLE IF NOT EXISTS "newsletter_subscribers" ("id" integer primary key autoincrement not null, "email" varchar not null, "name" varchar, "phone" varchar, "is_verified" tinyint(1) not null default '0', "verification_token" varchar, "verified_at" datetime, "subscribed_at" datetime not null default '2026-02-03 11:08:52', "unsubscribed_at" datetime, "preferences" text, "created_at" datetime, "updated_at" datetime);
INSERT INTO newsletter_subscribers VALUES(1,'tets@test.com',NULL,NULL,1,NULL,NULL,'2026-02-05 07:36:53',NULL,NULL,'2026-02-05 07:36:53','2026-02-05 07:36:53');
CREATE TABLE IF NOT EXISTS "push_notifications" ("id" integer primary key autoincrement not null, "title" varchar not null, "body" text not null, "image" varchar, "icon" varchar, "action_url" varchar, "target_audience" varchar check ("target_audience" in ('all', 'subscribers', 'segments')) not null default 'all', "segments" text, "scheduled_at" datetime, "sent_count" integer not null default '0', "click_count" integer not null default '0', "is_sent" tinyint(1) not null default '0', "created_by" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("created_by") references "users"("id") on delete cascade);
CREATE TABLE IF NOT EXISTS "seo_settings" ("id" integer primary key autoincrement not null, "site_title" varchar not null, "site_description" text not null, "site_keywords" varchar, "logo" varchar, "favicon" varchar, "og_image" varchar, "twitter_handle" varchar, "google_analytics_id" varchar, "google_tag_manager_id" varchar, "facebook_pixel_id" varchar, "robots_txt" text, "enable_sitemap" tinyint(1) not null default '1', "enable_robots" tinyint(1) not null default '1', "created_at" datetime, "updated_at" datetime, "mobile_logo" varchar, "site_name" varchar, "site_url" varchar, "meta_keywords" text, "ga_tracking_id" varchar, "gtm_tracking_id" varchar, "enable_analytics" tinyint(1) not null default '1', "facebook_url" varchar, "twitter_url" varchar, "instagram_url" varchar, "youtube_url" varchar, "linkedin_url" varchar, "tiktok_url" varchar, "recaptcha_site_key" varchar, "recaptcha_secret_key" varchar, "recaptcha_threshold" numeric not null default '0.5', "recaptcha_enabled" tinyint(1) not null default '0', "vapid_public_key" text, "vapid_private_key" text, "push_notifications_enabled" tinyint(1) not null default '0');
INSERT INTO seo_settings VALUES(1,'Laravelasa','Bengali News Portal',NULL,'settings/logos/gJXN5Vy9RAvqelzSqUpxYYMoKjLckFxJyxDRGZIP.png',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,'2026-02-14 09:08:58','2026-02-16 10:08:27','settings/logos/ie4TlAaRUXn1IaHCuMfX9hERH68b9MeqZcbniq8g.png','রাজ','http://localhost',NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,'your_recaptcha_site_key','your_recaptcha_secret_key',0.5,1,NULL,NULL,0);
CREATE TABLE IF NOT EXISTS "permissions" ("id" integer primary key autoincrement not null, "name" varchar not null, "guard_name" varchar not null, "created_at" datetime, "updated_at" datetime);
INSERT INTO permissions VALUES(1,'create_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(2,'read_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(3,'update_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(4,'delete_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(5,'publish_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(6,'feature_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(7,'breaking_news','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(8,'create_category','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(9,'read_category','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(10,'update_category','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(11,'delete_category','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(12,'create_user','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(13,'read_user','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(14,'update_user','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(15,'delete_user','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(16,'manage_advertisements','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(17,'view_ad_analytics','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(18,'manage_newsletter','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(19,'send_newsletter','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(20,'send_push_notifications','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(21,'view_analytics','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(22,'manage_settings','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(23,'manage_roles_permissions','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO permissions VALUES(24,'view_activity_logs','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
CREATE TABLE IF NOT EXISTS "roles" ("id" integer primary key autoincrement not null, "name" varchar not null, "guard_name" varchar not null, "created_at" datetime, "updated_at" datetime);
INSERT INTO roles VALUES(1,'super-admin','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO roles VALUES(2,'admin','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO roles VALUES(3,'editor','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO roles VALUES(4,'reporter','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
INSERT INTO roles VALUES(5,'author','web','2026-02-03 11:08:52','2026-02-03 11:08:52');
CREATE TABLE IF NOT EXISTS "model_has_permissions" ("permission_id" integer not null, "model_type" varchar not null, "model_id" integer not null, foreign key("permission_id") references "permissions"("id") on delete cascade, primary key ("permission_id", "model_id", "model_type"));
CREATE TABLE IF NOT EXISTS "model_has_roles" ("role_id" integer not null, "model_type" varchar not null, "model_id" integer not null, foreign key("role_id") references "roles"("id") on delete cascade, primary key ("role_id", "model_id", "model_type"));
INSERT INTO model_has_roles VALUES(1,'App\Models\User',1);
INSERT INTO model_has_roles VALUES(1,'App\Models\User',2);
INSERT INTO model_has_roles VALUES(3,'App\Models\User',3);
INSERT INTO model_has_roles VALUES(4,'App\Models\User',4);
INSERT INTO model_has_roles VALUES(2,'App\Models\User',5);
INSERT INTO model_has_roles VALUES(1,'App\Models\User',6);
INSERT INTO model_has_roles VALUES(1,'App\Models\User',8);
INSERT INTO model_has_roles VALUES(1,'App\Models\User',9);
INSERT INTO model_has_roles VALUES(1,'App\Models\User',10);
INSERT INTO model_has_roles VALUES(1,'App\Models\User',11);
INSERT INTO model_has_roles VALUES(1,'App\Models\User',12);
CREATE TABLE IF NOT EXISTS "role_has_permissions" ("permission_id" integer not null, "role_id" integer not null, foreign key("permission_id") references "permissions"("id") on delete cascade, foreign key("role_id") references "roles"("id") on delete cascade, primary key ("permission_id", "role_id"));
INSERT INTO role_has_permissions VALUES(1,1);
INSERT INTO role_has_permissions VALUES(2,1);
INSERT INTO role_has_permissions VALUES(3,1);
INSERT INTO role_has_permissions VALUES(4,1);
INSERT INTO role_has_permissions VALUES(5,1);
INSERT INTO role_has_permissions VALUES(6,1);
INSERT INTO role_has_permissions VALUES(7,1);
INSERT INTO role_has_permissions VALUES(8,1);
INSERT INTO role_has_permissions VALUES(9,1);
INSERT INTO role_has_permissions VALUES(10,1);
INSERT INTO role_has_permissions VALUES(11,1);
INSERT INTO role_has_permissions VALUES(12,1);
INSERT INTO role_has_permissions VALUES(13,1);
INSERT INTO role_has_permissions VALUES(14,1);
INSERT INTO role_has_permissions VALUES(15,1);
INSERT INTO role_has_permissions VALUES(16,1);
INSERT INTO role_has_permissions VALUES(17,1);
INSERT INTO role_has_permissions VALUES(18,1);
INSERT INTO role_has_permissions VALUES(19,1);
INSERT INTO role_has_permissions VALUES(20,1);
INSERT INTO role_has_permissions VALUES(21,1);
INSERT INTO role_has_permissions VALUES(22,1);
INSERT INTO role_has_permissions VALUES(23,1);
INSERT INTO role_has_permissions VALUES(24,1);
INSERT INTO role_has_permissions VALUES(1,2);
INSERT INTO role_has_permissions VALUES(2,2);
INSERT INTO role_has_permissions VALUES(3,2);
INSERT INTO role_has_permissions VALUES(4,2);
INSERT INTO role_has_permissions VALUES(5,2);
INSERT INTO role_has_permissions VALUES(6,2);
INSERT INTO role_has_permissions VALUES(7,2);
INSERT INTO role_has_permissions VALUES(8,2);
INSERT INTO role_has_permissions VALUES(9,2);
INSERT INTO role_has_permissions VALUES(10,2);
INSERT INTO role_has_permissions VALUES(11,2);
INSERT INTO role_has_permissions VALUES(12,2);
INSERT INTO role_has_permissions VALUES(13,2);
INSERT INTO role_has_permissions VALUES(14,2);
INSERT INTO role_has_permissions VALUES(15,2);
INSERT INTO role_has_permissions VALUES(16,2);
INSERT INTO role_has_permissions VALUES(17,2);
INSERT INTO role_has_permissions VALUES(18,2);
INSERT INTO role_has_permissions VALUES(20,2);
INSERT INTO role_has_permissions VALUES(21,2);
INSERT INTO role_has_permissions VALUES(22,2);
INSERT INTO role_has_permissions VALUES(23,2);
INSERT INTO role_has_permissions VALUES(24,2);
INSERT INTO role_has_permissions VALUES(1,3);
INSERT INTO role_has_permissions VALUES(2,3);
INSERT INTO role_has_permissions VALUES(3,3);
INSERT INTO role_has_permissions VALUES(4,3);
INSERT INTO role_has_permissions VALUES(5,3);
INSERT INTO role_has_permissions VALUES(6,3);
INSERT INTO role_has_permissions VALUES(9,3);
INSERT INTO role_has_permissions VALUES(21,3);
INSERT INTO role_has_permissions VALUES(24,3);
INSERT INTO role_has_permissions VALUES(1,4);
INSERT INTO role_has_permissions VALUES(2,4);
INSERT INTO role_has_permissions VALUES(3,4);
INSERT INTO role_has_permissions VALUES(9,4);
INSERT INTO role_has_permissions VALUES(1,5);
INSERT INTO role_has_permissions VALUES(2,5);
INSERT INTO role_has_permissions VALUES(3,5);
INSERT INTO role_has_permissions VALUES(9,5);
CREATE TABLE IF NOT EXISTS "tags" ("id" integer primary key autoincrement not null, "name" varchar not null, "slug" varchar not null, "color" varchar not null default '#6c757d', "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "taggables" ("taggable_type" varchar not null, "taggable_id" integer not null, "tag_id" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("tag_id") references "tags"("id") on delete cascade);
CREATE TABLE IF NOT EXISTS "visitor_analytics" ("id" integer primary key autoincrement not null, "news_id" integer not null, "visitor_ip" varchar, "visitor_country" varchar, "visitor_city" varchar, "visitor_device" varchar, "referrer_source" varchar, "referrer_url" varchar, "time_spent_seconds" integer not null default '0', "scroll_percentage" integer not null default '0', "completed_reading" tinyint(1) not null default '0', "browser" varchar, "os" varchar, "visit_date" datetime not null default CURRENT_TIMESTAMP, "created_at" datetime, "updated_at" datetime, foreign key("news_id") references "news"("id") on delete cascade);
CREATE TABLE IF NOT EXISTS "schema_settings" ("id" integer primary key autoincrement not null, "enable_news_article_schema" tinyint(1) not null default '1', "enable_organization_schema" tinyint(1) not null default '1', "enable_website_schema" tinyint(1) not null default '1', "enable_breadcrumb_schema" tinyint(1) not null default '1', "enable_person_schema" tinyint(1) not null default '1', "enable_image_object_schema" tinyint(1) not null default '1', "enable_video_object_schema" tinyint(1) not null default '0', "enable_live_blog_schema" tinyint(1) not null default '0', "enable_faq_schema" tinyint(1) not null default '0', "enable_job_posting_schema" tinyint(1) not null default '0', "enable_event_schema" tinyint(1) not null default '0', "enable_claim_review_schema" tinyint(1) not null default '0', "organization_name" varchar, "organization_description" text, "contact_email" varchar, "contact_phone" varchar, "contact_type" varchar not null default 'Customer Service', "created_at" datetime, "updated_at" datetime);
INSERT INTO schema_settings VALUES(1,1,1,1,1,1,1,1,1,1,1,1,1,NULL,NULL,NULL,NULL,'Customer Service','2026-02-14 09:26:18','2026-02-14 11:33:48');
CREATE TABLE IF NOT EXISTS "live_streams" ("id" integer primary key autoincrement not null, "user_id" integer not null, "title" varchar not null, "description" text, "slug" varchar not null, "status" varchar not null default 'draft', "thumbnail" varchar, "stream_key" varchar not null, "stream_url" varchar, "visibility" varchar check ("visibility" in ('public', 'private', 'unlisted')) not null default 'public', "viewer_count" integer not null default '0', "peak_viewers" integer not null default '0', "scheduled_at" datetime, "started_at" datetime, "ended_at" datetime, "duration_seconds" integer not null default '0', "stream_tags" text, "category" varchar, "allow_comments" tinyint(1) not null default '1', "allow_chat" tinyint(1) not null default '1', "recording_url" varchar, "is_featured" tinyint(1) not null default '0', "view_count" integer not null default '0', "like_count" integer not null default '0', "created_at" datetime, "updated_at" datetime, "deleted_at" datetime, foreign key("user_id") references "users"("id") on delete cascade);
INSERT INTO live_streams VALUES(3,11,'সদাআদ','sdsadas','sdaad','live','thumbnails/live-streams/lc6E51wbSr8NxTKjoE9EJi8DqgFlYUoQoYJn79BP.jpg','363eca67a2b9c3960bac80c28852cb63',NULL,'public',0,0,'2026-02-18 18:13:00','2026-02-18 12:13:29',NULL,0,NULL,'Education',1,1,NULL,0,1,0,'2026-02-18 12:13:27','2026-02-18 12:13:40',NULL);
CREATE TABLE IF NOT EXISTS "stream_comments" ("id" integer primary key autoincrement not null, "live_stream_id" integer not null, "commenter_name" varchar not null, "commenter_email" varchar, "facebook_id" varchar, "facebook_profile_url" varchar, "commenter_avatar" varchar, "comment_text" text not null, "source" varchar check ("source" in ('facebook', 'website', 'anonymous')) not null default 'website', "is_verified" tinyint(1) not null default '0', "is_pinned" tinyint(1) not null default '0', "likes_count" integer not null default '0', "is_approved" tinyint(1) not null default '1', "admin_notes" text, "created_at" datetime, "updated_at" datetime, "deleted_at" datetime, foreign key("live_stream_id") references "live_streams"("id") on delete cascade);
CREATE TABLE IF NOT EXISTS "comments" ("id" integer primary key autoincrement not null, "news_id" integer not null, "user_id" integer not null, "facebook_user_id" varchar, "comment_text" text not null, "approved" tinyint(1) not null default '0', "spam_score" numeric not null default '0', "recaptcha_score" numeric, "created_at" datetime, "updated_at" datetime, foreign key("news_id") references "news"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);
INSERT INTO comments VALUES(1,3,11,NULL,'Test comment',1,0,NULL,'2026-02-16 11:20:19','2026-02-16 11:20:19');
INSERT INTO comments VALUES(2,3,11,NULL,'Test comment from form',1,0,NULL,'2026-02-16 11:21:52','2026-02-16 11:21:52');
CREATE TABLE IF NOT EXISTS "push_subscriptions" ("id" integer primary key autoincrement not null, "endpoint" varchar not null, "public_key" text not null, "auth_token" text not null, "user_ip" varchar, "user_agent" varchar, "is_active" tinyint(1) not null default '1', "created_at" datetime, "updated_at" datetime);
CREATE TABLE IF NOT EXISTS "advertisements" ("id" integer primary key autoincrement not null, "name" varchar not null, "slug" varchar not null, "code" text, "type" varchar not null default ('banner'), "device_target" varchar not null default ('all'), "start_date" datetime not null, "end_date" datetime, "is_active" tinyint(1) not null default ('1'), "impressions" integer not null default ('0'), "clicks" integer not null default ('0'), "created_by" integer not null, "created_at" datetime, "updated_at" datetime, "placement" varchar not null default ('sidebar'), "image_url" varchar, "ad_url" varchar, "alt_text" varchar, "utm_source" varchar, "utm_medium" varchar, "utm_campaign" varchar, "utm_term" varchar, "utm_content" varchar, "views" integer not null default ('0'), "target_categories" text, "target_tags" text, "display_order" integer not null default ('0'), "show_on_mobile" tinyint(1) not null default ('1'), "show_on_desktop" tinyint(1) not null default ('1'), "daily_impression_limit" integer, "max_clicks_per_day" integer, "cpc_amount" numeric, "cpm_amount" numeric, "total_spent" numeric not null default ('0'), "advertiser_name" varchar, "advertiser_email" varchar, "advertiser_phone" varchar, "notes" text, "ad_type" varchar, "adsense_code" text, "adsense_slot_id" varchar, "adsense_publisher_id" varchar, "is_adsense_enabled" tinyint(1) not null default ('0'), "disable_page_limit" integer not null default ('3'), "minimum_content_length" integer not null default ('300'), "ad_network" varchar, "network_config" text, "ad_source" varchar check ("ad_source" in ('offline', 'online')) not null default 'offline', foreign key("created_by") references users("id") on delete cascade on update no action);
CREATE TABLE advertisements_new(
  id INT,
  name TEXT,
  slug TEXT,
  code TEXT,
  type TEXT,
  device_target TEXT,
  start_date NUM,
  end_date NUM,
  is_active INT,
  impressions INT,
  clicks INT,
  created_by INT,
  created_at NUM,
  updated_at NUM,
  placement TEXT,
  image_url TEXT,
  ad_url TEXT,
  alt_text TEXT,
  utm_source TEXT,
  utm_medium TEXT,
  utm_campaign TEXT,
  utm_term TEXT,
  utm_content TEXT,
  views INT,
  target_categories TEXT,
  target_tags TEXT,
  display_order INT,
  show_on_mobile INT,
  show_on_desktop INT,
  daily_impression_limit INT,
  max_clicks_per_day INT,
  cpc_amount NUM,
  cpm_amount NUM,
  total_spent NUM,
  advertiser_name TEXT,
  advertiser_email TEXT,
  advertiser_phone TEXT,
  notes TEXT,
  ad_type TEXT,
  adsense_code TEXT,
  adsense_slot_id TEXT,
  adsense_publisher_id TEXT,
  is_adsense_enabled INT,
  disable_page_limit INT,
  minimum_content_length INT,
  ad_network TEXT,
  network_config TEXT
);
INSERT INTO sqlite_sequence VALUES('migrations',35);
INSERT INTO sqlite_sequence VALUES('permissions',24);
INSERT INTO sqlite_sequence VALUES('roles',5);
INSERT INTO sqlite_sequence VALUES('users',12);
INSERT INTO sqlite_sequence VALUES('categories',3);
INSERT INTO sqlite_sequence VALUES('news',3);
INSERT INTO sqlite_sequence VALUES('newsletter_subscribers',1);
INSERT INTO sqlite_sequence VALUES('seo_settings',1);
INSERT INTO sqlite_sequence VALUES('schema_settings',1);
INSERT INTO sqlite_sequence VALUES('live_streams',3);
INSERT INTO sqlite_sequence VALUES('activity_logs',7);
INSERT INTO sqlite_sequence VALUES('comments',2);
INSERT INTO sqlite_sequence VALUES('advertisements',0);
CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");
CREATE INDEX "sessions_user_id_index" on "sessions" ("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions" ("last_activity");
CREATE INDEX "cache_expiration_index" on "cache" ("expiration");
CREATE INDEX "cache_locks_expiration_index" on "cache_locks" ("expiration");
CREATE INDEX "jobs_queue_index" on "jobs" ("queue");
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs" ("uuid");
CREATE INDEX "activity_logs_user_id_created_at_index" on "activity_logs" ("user_id", "created_at");
CREATE INDEX "activity_logs_model_type_index" on "activity_logs" ("model_type");
CREATE INDEX "categories_slug_index" on "categories" ("slug");
CREATE INDEX "categories_parent_id_index" on "categories" ("parent_id");
CREATE UNIQUE INDEX "categories_slug_unique" on "categories" ("slug");
CREATE INDEX "news_analytics_news_id_date_index" on "news_analytics" ("news_id", "date");
CREATE INDEX "news_slug_index" on "news" ("slug");
CREATE INDEX "news_status_index" on "news" ("status");
CREATE INDEX "news_published_at_index" on "news" ("published_at");
CREATE INDEX "news_category_id_index" on "news" ("category_id");
CREATE UNIQUE INDEX "news_slug_unique" on "news" ("slug");
CREATE INDEX "newsletter_subscribers_email_index" on "newsletter_subscribers" ("email");
CREATE INDEX "newsletter_subscribers_is_verified_index" on "newsletter_subscribers" ("is_verified");
CREATE UNIQUE INDEX "newsletter_subscribers_email_unique" on "newsletter_subscribers" ("email");
CREATE INDEX "push_notifications_is_sent_index" on "push_notifications" ("is_sent");
CREATE UNIQUE INDEX "permissions_name_guard_name_unique" on "permissions" ("name", "guard_name");
CREATE UNIQUE INDEX "roles_name_guard_name_unique" on "roles" ("name", "guard_name");
CREATE INDEX "model_has_permissions_model_id_model_type_index" on "model_has_permissions" ("model_id", "model_type");
CREATE INDEX "model_has_roles_model_id_model_type_index" on "model_has_roles" ("model_id", "model_type");
CREATE UNIQUE INDEX "tags_name_unique" on "tags" ("name");
CREATE UNIQUE INDEX "tags_slug_unique" on "tags" ("slug");
CREATE INDEX "taggables_taggable_type_taggable_id_index" on "taggables" ("taggable_type", "taggable_id");
CREATE UNIQUE INDEX "taggables_taggable_id_taggable_type_tag_id_unique" on "taggables" ("taggable_id", "taggable_type", "tag_id");
CREATE INDEX "visitor_analytics_news_id_visit_date_index" on "visitor_analytics" ("news_id", "visit_date");
CREATE INDEX "visitor_analytics_referrer_source_index" on "visitor_analytics" ("referrer_source");
CREATE INDEX "live_streams_status_index" on "live_streams" ("status");
CREATE INDEX "live_streams_user_id_index" on "live_streams" ("user_id");
CREATE INDEX "live_streams_started_at_index" on "live_streams" ("started_at");
CREATE INDEX "live_streams_is_featured_index" on "live_streams" ("is_featured");
CREATE UNIQUE INDEX "live_streams_slug_unique" on "live_streams" ("slug");
CREATE UNIQUE INDEX "live_streams_stream_key_unique" on "live_streams" ("stream_key");
CREATE INDEX "stream_comments_live_stream_id_index" on "stream_comments" ("live_stream_id");
CREATE INDEX "stream_comments_facebook_id_index" on "stream_comments" ("facebook_id");
CREATE INDEX "stream_comments_created_at_index" on "stream_comments" ("created_at");
CREATE INDEX "stream_comments_is_pinned_index" on "stream_comments" ("is_pinned");
CREATE UNIQUE INDEX "stream_comments_facebook_id_unique" on "stream_comments" ("facebook_id");
CREATE INDEX "comments_news_id_index" on "comments" ("news_id");
CREATE INDEX "comments_user_id_index" on "comments" ("user_id");
CREATE INDEX "comments_approved_index" on "comments" ("approved");
CREATE INDEX "comments_created_at_index" on "comments" ("created_at");
CREATE UNIQUE INDEX "users_facebook_user_id_unique" on "users" ("facebook_user_id");
CREATE INDEX "push_subscriptions_is_active_index" on "push_subscriptions" ("is_active");
CREATE INDEX "push_subscriptions_created_at_index" on "push_subscriptions" ("created_at");
CREATE UNIQUE INDEX "push_subscriptions_endpoint_unique" on "push_subscriptions" ("endpoint");
CREATE INDEX "advertisements_ad_network_index" on "advertisements" ("ad_network");
CREATE INDEX "advertisements_is_active_index" on "advertisements" ("is_active");
CREATE UNIQUE INDEX "advertisements_slug_unique" on "advertisements" ("slug");
CREATE INDEX "advertisements_type_index" on "advertisements" ("type");
COMMIT;
