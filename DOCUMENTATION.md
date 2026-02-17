# 📋 সাজেব নিউজ - সম্পূর্ণ ডকুমেন্টেশন

## 📑 সূচী
1. [ইনস্টলেশন](#ইনস্টলেশন)
2. [কনফিগুরেশন](#কনফিগুরেশন)
3. [ডাটাবেস স্ট্রাকচার](#ডাটাবেস-স্ট্রাকচার)
4. [API এন্ডপয়েন্ট](#api-এন্ডপয়েন্ট)
5. [SEO গাইড](#seo-গাইড)
6. [সিকিউরিটি](#সিকিউরিটি)

---

## ইনস্টলেশন

### প্রয়োজনীয়তা
```
- PHP 8.3+
- Composer
- Node.js 18+
- MySQL 8.0+ / PostgreSQL 12+ / SQLite
- Redis (ঐচ্ছিক কিন্তু সুপারিশকৃত)
```

### ধাপে ধাপে ইনস্টলেশন

#### ১. প্রজেক্ট সেটআপ
```bash
# ক্লোন করুন
git clone <repository-url> sajeb-news
cd sajeb-news

# Composer প্যাকেজ ইনস্টল করুন
composer install

# .env ফাইল তৈরি করুন
cp .env.example .env

# অ্যাপ কী জেনারেট করুন
php artisan key:generate
```

#### ২. ডাটাবেস কনফিগুরেশন
`.env` ফাইলে নিম্নলিখিত যোগ করুন:

**MySQL এর জন্য:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sajeb_news
DB_USERNAME=root
DB_PASSWORD=
```

**SQLite এর জন্য:**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite
```

#### ৩. মাইগ্রেশন এবং সিডিং
```bash
# টেবিল তৈরি করুন
php artisan migrate

# সিড ডেটা যোগ করুন
php artisan db:seed
```

#### ৪. স্টোরেজ সেটআপ
```bash
# স্টোরেজ লিঙ্ক তৈরি করুন
php artisan storage:link

# পারমিশন সেট করুন
chmod -R 775 storage bootstrap/cache
```

#### ৫. ডেভেলপমেন্ট সার্ভার চালান
```bash
php artisan serve
```

ব্রাউজার খুলুন এবং যান: `http://localhost:8000`

---

## কনফিগুরেশন

### পরিবেশ ভেরিয়েবল (.env)

#### অ্যাপ কনফিগ
```env
APP_NAME="Sajeb News"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Dhaka
```

#### ক্যাশিং (সুপারিশকৃত: Redis)
```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### সেশন
```env
SESSION_DRIVER=cookie
SESSION_LIFETIME=120
```

#### ইমেইল কনফিগুরেশন
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sajeb-news.local
MAIL_FROM_NAME="Sajeb News"
```

#### Google Services
```env
GOOGLE_ANALYTICS_ID=UA-XXXXXX-X
GOOGLE_TAG_MANAGER_ID=GTM-XXXXXX
GOOGLE_CLIENT_ID=your_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your_client_secret
```

#### Facebook
```env
FACEBOOK_PIXEL_ID=XXXXXX
FACEBOOK_APP_ID=your_app_id
FACEBOOK_APP_SECRET=your_app_secret
```

---

## ডাটাবেস স্ট্রাকচার

### মূল টেবিল

#### `users` টেবিল
```
- id (Integer, Primary Key)
- name (String)
- email (String, Unique)
- password (String, Hashed)
- phone (String, Nullable)
- avatar (String, Nullable)
- bio (Text, Nullable)
- is_active (Boolean, Default: true)
- two_factor_enabled (Boolean, Default: false)
- two_factor_secret (String, Nullable)
- last_login_at (Timestamp, Nullable)
- last_login_ip (IP Address, Nullable)
- email_verified_at (Timestamp, Nullable)
- timestamps
```

#### `categories` টেবিল
```
- id (Integer, Primary Key)
- name (String)
- slug (String, Unique)
- description (Text, Nullable)
- parent_id (Integer, Nullable, Foreign Key)
- icon (String, Nullable)
- meta_title (String, Nullable)
- meta_description (Text, Nullable)
- meta_keywords (Text, Nullable)
- order (Integer, Default: 0)
- is_active (Boolean, Default: true)
- timestamps
```

#### `news` টেবিল
```
- id (Integer, Primary Key)
- title (String)
- slug (String, Unique)
- content (LongText)
- excerpt (LongText, Nullable)
- featured_image (String, Nullable)
- category_id (Integer, Foreign Key)
- author_id (Integer, Foreign Key)
- status (Enum: draft, published, scheduled, archived)
- is_featured (Boolean, Default: false)
- is_breaking (Boolean, Default: false)
- published_at (Timestamp, Nullable)
- scheduled_at (Timestamp, Nullable)
- views (Integer, Default: 0)
- meta_title (String, Nullable)
- meta_description (Text, Nullable)
- meta_keywords (Text, Nullable)
- canonical_url (String, Nullable)
- og_description (LongText, Nullable)
- og_image (String, Nullable)
- twitter_card (String, Nullable)
- reading_time (Integer, Nullable)
- timestamps
- soft_deletes
```

#### `advertisements` টেবিল
```
- id (Integer, Primary Key)
- name (String)
- slug (String, Unique)
- code (Text, Nullable)
- type (Enum: banner, sidebar, inline, featured, header, category_page, search)
- device_target (Enum: desktop, mobile, all)
- start_date (DateTime)
- end_date (DateTime, Nullable)
- is_active (Boolean, Default: true)
- impressions (Integer, Default: 0)
- clicks (Integer, Default: 0)
- created_by (Integer, Foreign Key)
- timestamps
```

#### `newsletter_subscribers` টেবিল
```
- id (Integer, Primary Key)
- email (String, Unique)
- name (String, Nullable)
- phone (String, Nullable)
- is_verified (Boolean, Default: false)
- verification_token (String, Nullable)
- verified_at (Timestamp, Nullable)
- subscribed_at (Timestamp)
- unsubscribed_at (Timestamp, Nullable)
- preferences (JSON, Nullable)
- timestamps
```

#### `news_analytics` টেবিল
```
- id (Integer, Primary Key)
- news_id (Integer, Foreign Key)
- daily_views (Integer, Default: 0)
- total_views (Integer, Default: 0)
- scroll_depth (Integer, Default: 0)
- average_time_on_page (Integer, Default: 0)
- bounce_rate (Integer, Default: 0)
- social_shares (Integer, Default: 0)
- comments_count (Integer, Default: 0)
- date (Date)
- timestamps
```

---

## API এন্ডপয়েন্ট

### পাবলিক API

#### নিউজ পান
```
GET /api/news
Parameters:
  - page (integer): পেজ নম্বর
  - per_page (integer): প্রতি পেজে আইটেম সংখ্যা
  - category (string): ক্যাটেগরি স্লাগ

Response:
{
  "data": [
    {
      "id": 1,
      "title": "নিউজ টাইটেল",
      "slug": "news-title",
      "excerpt": "নিউজ সংক্ষিপ্ত বিবরণ",
      "featured_image": "url",
      "views": 1234,
      "published_at": "2026-02-03T10:00:00Z",
      "category": {...},
      "author": {...}
    }
  ],
  "pagination": {...}
}
```

#### একটি নিউজ পান
```
GET /api/news/{slug}

Response:
{
  "data": {
    "id": 1,
    "title": "নিউজ টাইটেল",
    "slug": "news-title",
    "content": "সম্পূর্ণ নিউজ কন্টেন্ট",
    "excerpt": "নিউজ সংক্ষিপ্ত বিবরণ",
    "featured_image": "url",
    "views": 1234,
    "published_at": "2026-02-03T10:00:00Z",
    "reading_time": 5,
    "category": {...},
    "author": {...},
    "related_news": [...]
  }
}
```

#### সব ক্যাটেগরি পান
```
GET /api/categories

Response:
{
  "data": [
    {
      "id": 1,
      "name": "প্রযুক্তি",
      "slug": "technology",
      "description": "প্রযুক্তি সংক্রান্ত খবর",
      "news_count": 45
    }
  ]
}
```

#### নিউজলেটার সাবস্ক্রাইব করুন
```
POST /api/newsletter/subscribe

Body:
{
  "email": "user@example.com",
  "name": "ব্যবহারকারী নাম"
}

Response:
{
  "message": "সাফল্য",
  "verification_sent": true
}
```

#### ট্রেন্ডিং নিউজ
```
GET /api/trending?days=7&limit=10

Response:
{
  "data": [...]
}
```

---

## SEO গাইড

### মেটা ট্যাগ ম্যানেজমেন্ট

প্রতিটি নিউজ আর্টিকেল নিম্নলিখিত মেটা তথ্য অন্তর্ভুক্ত করতে পারে:

```
- Meta Title (50-60 characters)
- Meta Description (150-160 characters)
- Meta Keywords
- Canonical URL
- Open Graph Title, Description, Image, URL
- Twitter Card (summary_large_image)
```

### স্কিমা মার্কআপ

সাইটটি স্বয়ংক্রিয়ভাবে নিম্নলিখিত স্কিমা তৈরি করে:

- `NewsArticle` - প্রতিটি নিউজ আর্টিকেলের জন্য
- `BreadcrumbList` - নেভিগেশন পথের জন্য
- `Organization` - সাইট তথ্যের জন্য

### XML সাইটম্যাপ

XML সাইটম্যাপ স্বয়ংক্রিয়ভাবে `/sitemap.xml` এ তৈরি হয়।

### Robots.txt

প্রোডাকশনে, অনুমতি দেওয়ার জন্য `.env` এ যোগ করুন:
```env
SEARCH_ENGINES_CRAWLING=true
```

---

## সিকিউরিটি

### সুপারিশকৃত নিরাপত্তা সেটিংস

#### 1. HTTPS (SSL/TLS)
```nginx
# nginx কনফিগ
server {
    listen 443 ssl http2;
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
}
```

#### 2. নিরাপত্তা হেডার
```
Strict-Transport-Security: max-age=31536000; includeSubDomains; preload
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Content-Security-Policy: default-src 'self'
```

#### 3. রেট লিমিটিং
```env
RATE_LIMIT_ENABLED=true
RATE_LIMIT_REQUESTS=60
RATE_LIMIT_MINUTES=1
```

#### 4. দুই-ফ্যাক্টর অথেন্টিকেশন
প্রোডাকশনে সব অ্যাডমিন ইউজারের জন্য সক্ষম করুন:
```bash
php artisan admin:enable-2fa
```

#### 5. ব্যাকআপ
সাপ্তাহিক ডাটাবেস ব্যাকআপ সক্ষম করুন:
```bash
php artisan schedule:work
```

### CSRF সুরক্ষা

সকল ফর্ম অটোমেটিক্যালি CSRF টোকেন অন্তর্ভুক্ত করে।

### XSS সুরক্ষা

সকল ব্যবহারকারী ইনপুট স্বয়ংক্রিয়ভাবে স্যানিটাইজ করা হয়।

---

## প্রোডাকশন ডিপ্লয়মেন্ট

### Heroku
```bash
git push heroku main
heroku run php artisan migrate --app sajeb-news
```

### AWS / Digital Ocean
```bash
# Pull from repository
git pull origin main

# Install/Update dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Environment Production
```env
APP_ENV=production
APP_DEBUG=false
```

---

**শেষ আপডেট**: ফেব্রুয়ারি ৩, ২০২৬
