# Sajeb NEWS - Bangladesh News Portal

A complete professional, dynamic, and SEO-optimized news portal platform built for Bangladesh. Built with **Laravel 12**, **Tailwind CSS (Stitch Design System)**, and modern web technologies.

**Live Demo**: Coming Soon  
**Admin Panel**: `/admin` (Login credentials provided in server setup)

---

## ✨ Key Features

### 🚀 Complete Admin Dashboard
✅ **12+ Admin Modules** - Full CRUD operations for everything  
✅ **Role-Based Access Control** - Admin, Editor, Reporter, Author roles  
✅ **Advanced Analytics** - News views, visitor stats, performance tracking  
✅ **Activity Logging** - Track all user actions for security  
✅ **Push Notifications** - Send real-time notifications to subscribers  
✅ **Advertisement Management** - Multiple ad placements and scheduling  
✅ **Photo Card Maker** - Canvas-based social media photo card generator (5 sizes, 6 themes)  
✅ **File Manager** - Upload and manage media files  
✅ **Live Stream Management** - Embed-based live TV streaming  

### 📰 News Management
- ✅ Hierarchical categories and tags system
- ✅ Multimedia support (images, videos, embeds)
- ✅ Draft, schedule, and published states
- ✅ Featured and breaking news controls
- ✅ Related news auto-suggestions
- ✅ Full-text search capability
- ✅ News analytics and performance tracking
- ✅ Automatic WebP image optimization on upload
- ✅ Automated newsletter email on publish

### 🎨 Dynamic Pages (Editable from Admin)
- ✅ **About Page** - Rich text editor, fully customizable from `/admin/settings`
- ✅ **Contact Page** - Working contact form with email notifications
- ✅ **Privacy Policy** - GDPR compliant legal page
- ✅ **Terms & Conditions** - Complete service terms
- ✅ **Sitemap Page** - HTML sitemap with categories and recent news
- ✅ **Error Pages** - Custom 404, 419, 423, 500 error pages

### 🔍 Enterprise-Grade SEO / AEO / GEO
- ✅ **Dynamic Sitemaps** - XML & HTML sitemaps auto-generated
- ✅ **Meta Tags** - Title, Description, Keywords, Robots per page
- ✅ **Open Graph Tags** - og:locale, og:site_name, og:type, og:image on all pages
- ✅ **Twitter Cards** - Twitter-specific social sharing with site handle
- ✅ **JSON-LD Schema** - Organization, NewsArticle, BreadcrumbList, WebSite, CollectionPage
- ✅ **Canonical URLs** - Prevent duplicate content issues
- ✅ **Hreflang Tags** - Multi-language SEO (bn + x-default)
- ✅ **Robots.txt** - Dynamic, bot-specific crawling rules
- ✅ **LLM.txt File** - AI/LLM friendly content format (GEO)
- ✅ **Breadcrumb Navigation** - Improved user experience & SEO
- ✅ **Favicon & Apple Touch Icon** - From admin settings
- ✅ **Theme Color** - Mobile browser theming

### ⚡ Performance Features
- ✅ Non-render-blocking font loading (preload + onload pattern)
- ✅ DNS prefetch & preconnect hints for external origins
- ✅ Local Bengali font (SolaimanLipi) with font-display: swap
- ✅ Image lazy loading with explicit width/height attributes
- ✅ Hero image fetchpriority="high" for faster LCP
- ✅ Automatic WebP image conversion (GD library)
- ✅ GZIP compression (all text assets)
- ✅ Browser caching strategies (1 year for static, 1 hour for dynamic)
- ✅ Redis caching support
- ✅ Database query optimization
- ✅ CDN-ready structure

### 📊 Visitor Tracking & Analytics
- ✅ **Real-time Visitor Tracking** - Track every visitor's activity on news articles
- ✅ **Destination Tracking** - Monitor where visitors go after reading an article
- ✅ **Detailed Analytics** - Comprehensive data collection:
  - IP address tracking with proxy/Cloudflare support
  - Geographic location (country and city)
  - Device type detection (Mobile, Tablet, Desktop)
  - Browser and operating system information
  - Time spent on articles (in seconds)
  - Scroll depth percentage
  - Reading completion status
  - Referrer source tracking (Google, Facebook, Twitter, LinkedIn, WhatsApp, Bing, ChatGPT, etc.)
  - Screen resolution capture
  - Language preference detection
- ✅ **Advanced Visitor Dashboard** - Detailed analytics view in admin panel
- ✅ **Traffic Source Analysis** - Understand where your visitors come from
- ✅ **User Behavior Metrics** - Track engagement and reading patterns

### 🔒 Security Features
- ✅ CSRF token protection
- ✅ XSS prevention (Blade escaping)
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ Rate limiting
- ✅ Password hashing (bcrypt)
- ✅ Security headers (X-Frame-Options, X-Content-Type-Options, HSTS)
- ✅ Activity logging and audit trails

### 📱 Responsive Design
- ✅ Mobile-first approach
- ✅ Tailwind CSS responsive grid system
- ✅ Touch-friendly interface
- ✅ Sticky header with hamburger drawer navigation
- ✅ Mobile bottom navigation bar
- ✅ Optimized for all devices
- ✅ Accessibility compliant

### 🌐 Multi-Language Support
- ✅ Bengali (বাংলা) fully supported
- ✅ English fallback
- ✅ Proper font handling (SolaimanLipi — local, no CDN dependency)
- ✅ Right-to-left text support ready

---

## 📦 Tech Stack

### Backend
- **Framework**: Laravel 12 (PHP 8.3+)
- **Database**: MySQL 8.4 / PostgreSQL
- **Cache**: Redis / Memcached
- **Queue**: Redis / Database
- **Storage**: Local / S3 Compatible

### Frontend
- **CSS Framework**: Tailwind CSS (Stitch Design System)
- **JavaScript**: Vanilla JS + Quill Editor
- **Icons**: Material Symbols Outlined
- **Fonts**: SolaimanLipi (local), Libre Franklin, Work Sans

### Tools & Services
- **Email**: SMTP / Mailtrap / SendGrid
- **Analytics**: Google Analytics 4
- **Search**: Full-text MySQL search
- **Images**: Intervention Image Library
- **PDF**: TCPDF / Dompdf

---

## 🚀 Installation & Setup

### Prerequisites
```bash
- PHP 8.3 or higher
- MySQL 8.4 or PostgreSQL 13+
- Node.js 16+ (for assets)
- Composer
```

### Quick Start
```bash
# Clone the repository
git clone https://github.com/yourusername/sajeb-news.git
cd sajeb-news

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
# DATABASE_URL=mysql://user:pass@localhost/sajeb_news

# Option 1: Run migrations and seeders (Recommended for development)
php artisan migrate --seed

# Option 2: Import existing database dump (For production or quick setup)
# mysql -u root -p sajeb_news < sajeb_news_mysql.sql

# Compile assets
npm run build

# Start development server
php artisan serve

# Open in browser
http://localhost:8000
```

### Default Credentials (After Seeding)
- **Email**: admin@test.com
- **Password**: 12345
- **Admin Panel**: http://localhost:8000/admin

---

## 🗄️ Database Setup

### Using Database Migrations (Recommended for Development)
```bash
php artisan migrate --seed
```
This will create all tables and seed them with sample data.

### Using Database Dump (For Quick Setup or Production)
The project includes a complete MySQL database dump in `sajeb_news_mysql.sql`.

**Import via Command Line:**
```bash
mysql -u root -p sajeb_news < sajeb_news_mysql.sql
```

**Import via phpMyAdmin (cPanel):**
1. Create a new database named `sajeb_news`
2. Select the database and go to **Import**
3. Choose the file `sajeb_news_mysql.sql`
4. Click **Import**

**Database includes:**
- ✅ All tables with proper structure
- ✅ User roles and permissions
- ✅ Sample news articles
- ✅ Categories and tags
- ✅ Admin user account
- ✅ Settings configurations

---

## 🔐 Security Considerations

1. **Environment Variables**: Store sensitive data in `.env`
2. **File Permissions**: Ensure `storage/` and `bootstrap/cache/` are writable
3. **HTTPS**: Always use HTTPS in production
4. **Rate Limiting**: API endpoints have rate limiting enabled
5. **CORS**: Configured for specific domains only
6. **SQL Injection**: Uses parameterized queries via Eloquent
7. **XSS Protection**: All outputs are escaped by default

---

## 📈 Performance Tips

### For Production
```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize Composer ClassMap
composer dump-autoload --optimize

# Minify frontend assets
npm run build
```

### Caching
```php
// Enable query caching
CACHE_DRIVER=redis

// Enable session caching
SESSION_DRIVER=cookie

// Enable view caching
php artisan view:cache
```

---

## 🐛 Troubleshooting

### Common Issues

**1. Migration errors**
```bash
php artisan migrate:reset
php artisan migrate --seed
```

**2. Cache issues**
```bash
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

**3. Storage permission issues**
```bash
chmod -R 775 storage bootstrap/cache
```

**4. Asset not loading**
```bash
npm run build
php artisan storage:link
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License
© Sajib Bahadur Shil
This project is licensed under the MIT License - see the LICENSE file for details.

---

## 👨‍💻 Author

**Sajeb NEWS Team**
- GitHub: [@sajebchy](https://github.com/sajebchy/)
- Email: sajebbahadurraj@gmail.com
- Linkdin: https://www.linkedin.com/in/sajeb-bahadur-shil/

---

## 🙏 Support

Need help? Check these resources:
- 📖 [Laravel Documentation](https://laravel.com/docs)
- 🔍 [Bootstrap Documentation](https://getbootstrap.com/docs)
- 💬 [GitHub Issues](https://github.com/yourusername/sajeb-news/issues)
- 📧 Contact us at sajebbahadurraj@gmail.com

---

## 🎉 Changelog

---

### Version 3.1 (July 3, 2026) — Photo Card, Font Migration, SEO/Performance & Bug Fixes

---

#### 🖼️ Photo Card Maker (New Feature)
- **Canvas-based photo card generator** at `/admin/photo-card` for social media
- 5 preset sizes: Instagram Post (1080×1080), Instagram Story (1080×1920), Facebook (1200×630), Twitter (1200×675), YouTube (1280×720)
- 6 color themes with drag & drop image upload
- Calendar date picker with automatic Bengali date conversion
- Logo auto-loaded from admin settings (photo_card_logo)
- Logo positioned at image/headline junction for branding
- Website URL auto-populated from settings
- Crystal-clear PNG/JPG downloads at full resolution (canvas `imageSmoothingQuality: high`)
- SolaimanLipi font rendered via FontFace API preloading

#### 🔤 Font Migration: Noto Serif Bengali → SolaimanLipi
- Replaced Google Fonts (Noto Serif Bengali) with locally hosted **SolaimanLipi** font across entire website
- Removed all Google Fonts CDN dependencies for Bengali font — improved privacy and load time
- Font files served from `/public/fonts/` (normal + bold weights)
- Applied to all layouts: public, admin, guest, auth pages
- News article body content (`.article-prose`) now uses SolaimanLipi

#### 🔍 SEO / AEO / GEO Improvements
- Added `robots` meta tag with `max-image-preview:large, max-snippet:-1, max-video-preview:-1`
- Added `theme-color` and `msapplication-TileColor` meta tags
- Added `og:locale`, `og:site_name`, `og:type` to global layout (all pages)
- Added `og:title`, `og:description`, `og:url`, `og:image` section support in layout
- Added `twitter:site` from settings (twitter handle)
- Added `hreflang` tags (bn + x-default)
- Added `<link rel="sitemap">` for XML sitemap discovery
- Added `<link rel="alternate">` for LLM.txt discovery (GEO)
- Added favicon and apple-touch-icon from settings

#### ⚡ Performance / Lighthouse Improvements
- Google Fonts (Libre Franklin, Work Sans, Material Symbols) now loaded **non-render-blocking** via `preload` + `onload` pattern
- Added `dns-prefetch` and `preconnect` hints for fonts.googleapis.com, fonts.gstatic.com, cdn.tailwindcss.com
- SolaimanLipi font preloaded with `<link rel="preload" as="font">`
- All `@font-face` declarations use `font-display: swap`
- Homepage hero image uses `fetchpriority="high"` for faster LCP
- All non-hero images use `loading="lazy"` with explicit `width`/`height` attributes
- Added image dimensions to homepage cards (hero, secondary, category, sidebar)

#### 🐛 Bug Fixes
- **Fixed meta tags appearing inside `<body>`** — removed UTF-8 BOM from 23 Blade files that caused browser HTML parser to break `<head>` structure
- **Fixed homepage featured images not showing** — `$defaultFeaturedImage` now falls back to site logo when `default_featured_image` is not set; replaced all broken `asset('images/logo.png')` fallbacks across 7 public views
- **Fixed AdController image upload** — update method and AJAX upload now use ImageOptimizer for WebP conversion (was using raw `$file->move()`)
- **Fixed ad image validation** — reduced max upload size from 5MB to 1MB for advertisements

#### 🗄️ Database Changes
- Added `photo_card_logo` column to `seo_settings` table
- Added `photo_card_logo` to SeoSetting model's `$fillable` array
- Settings page: new "ফটোকার্ড লোগো" upload card in Logos & Images tab
- Updated `sajeb_news_backup.sql` with latest database state

---

### Version 3.0 (July 1, 2026) — Admin Redesign, Newsletter System & Ad Integration

This release introduces a full redesign of multiple admin panel pages using the Stitch Design System, an automated newsletter email system, Bengali demo content, a revamped homepage layout displaying all categories, and a fully functional advertisement system across all public pages.

---

#### 🎨 Admin Panel Redesigns

**Advertisements — List Page**
- Rebuilt with 4 summary stat cards: total ads, active ads, total impressions, total clicks
- Added search bar and dual filter (by placement type and status)
- Inline status toggle directly from the table — no page reload required
- Custom pagination component
- Bottom analytics strip showing top-performing ad, active rate, and overall CTR

**Advertisements — Edit Page**
- Migrated from Bootstrap 5 to Stitch / Tailwind two-column layout
- Left column: basic info, image upload, ad network settings with dynamic fields, UTM parameter builder
- Right sidebar: save controls, scheduling, advertiser details, budget settings, performance overview, internal notes
- Live URL + UTM preview updates as fields are filled

**Users — List Page**
- Added server-side search by name or email
- Role filter and account status filter (active / inactive)
- Each row shows avatar initials with a deterministic colour, role badge, article count, status indicator, and hover-reveal action buttons
- Three summary cards at the bottom: total users, active users, new today

**Newsletter Management — New Page**
- Brand-new page accessible from the admin sidebar
- Four stat cards: total subscribers, verified, unsubscribed, new today
- Subscriber table with search, status filter, avatar initials, status badge, and pagination
- Right panel: subscriber growth bars, recent campaign history, quick-action buttons

---

#### 📧 Automated Newsletter Email System

When a news article is published for the first time, an email is automatically dispatched to all verified, active subscribers. Key behaviours:

- Emails are only sent on the initial publish event (draft or scheduled → published). Subsequent edits do not re-trigger the email.
- Only subscribers who have verified their email address and have not unsubscribed receive the notification.
- The email includes the article title, category, excerpt, featured image, and a read-more link.
- A one-click unsubscribe link is included in every email footer in compliance with email best practices.
- Dispatch is handled via a background queue job for performance; the mail driver can be configured independently in the environment settings.

**New components added:**
- Queued mail job with automatic retry support and memory-efficient subscriber iteration
- Custom HTML email template (Bengali subject line, branded header, article preview, unsubscribe footer)

---

#### 🗂️ Demo Content Seeders

**News Seeder**
- Created 8 new Bengali categories: National, International, Economy, Sports, Technology, Entertainment, Health, Education
- Added 10 news articles per category (90 articles total) with Bengali titles, excerpts, and body content
- Articles have a mix of published, draft, and scheduled statuses; some are flagged as featured or breaking news

**Advertisements Seeder**
- Updated all 10 active ad slots with correctly sized placeholder images matching each placement's standard dimensions (header, homepage banner, sidebar, article inline, footer)

---

#### 🏠 Public Homepage — All Categories Now Displayed

Previously the homepage only showed categories that had a manually assigned featured order. This has been changed so that **every active category with at least one published article** appears automatically.

- Each category section uses a two-column layout: a large featured article card on the left and a compact list of recent articles on the right
- Each section header links to the full category page
- The sidebar now includes a Category Quick Links widget listing all active categories with their article counts

---

#### 📢 Advertisement System — Fully Active on Public Pages

Fixed a rendering bug where absolute image URLs were being incorrectly prefixed, causing ad images not to display.

Ad slots are now live across the following areas:

| Location | Placement |
|---|---|
| All pages — above the header | Header Top Banner |
| All pages — above the footer | Footer Banner |
| Homepage — above main content | Homepage Top Banner |
| Homepage sidebar | Medium Rectangle + Half Page |
| News article — end of content | Article Conclusion |
| News article sidebar | Medium Rectangle + Half Page |
| News article — mobile view | Inline Banner |

---

#### 📁 Files Changed

**New files added:** Newsletter queue job, newsletter mailable, HTML email template, newsletter admin view, news demo seeder, advertisements demo seeder.

**Modified files:** Advertisement helper, advertisement list view, advertisement edit view, user list view, newsletter controller, user controller, news controller (admin), news controller (public), admin layout, homepage view, public layout, article detail view.

---

### Version 2.4.2 (March 7, 2026) - Bug Fixes & Cleanup
**Bug Fixes:**
- 🎨 **Fixed Favicon Not Showing in Admin Panel**
  - Added favicon link to admin layout template (`resources/views/layouts/admin.blade.php`)
  - Now displays custom favicon from SeoSettings if available
  - Falls back to default `/favicon.ico` if no custom favicon is set
  - Applied same favicon logic as public layout for consistency

**Code Cleanup:**
- 🧹 **Removed Unnecessary Test Files**
  - Removed unused test directory following fresh Laravel Breeze template update
  - Deleted test files: `tests/Feature/Auth/*`, `tests/Feature/ExampleTest.php`, `tests/Unit/ExampleTest.php`
  - Tests will be re-added when custom test cases are needed for project

- 🖼️ **Removed Demo Screenshots**
  - Deleted outdated demo screenshot files from `/demo` folder to reduce repository size
  - Helps maintain a cleaner Git history

**Updates:**
- 📝 Updated `.gitignore` for better project organization

**Verification:**
- ✅ Admin panel favicon now displays correctly
- ✅ All existing features working as expected
- ✅ Repository cleanup complete

---

### Version 2.4.1 (March 7, 2026) - Security Hotfix
**Security Fixes:**
- 🔒 **Fixed league/commonmark DisallowedRawHtml Extension Bypass (XSS Vulnerability)**
  - Upgraded league/commonmark from v2.8.0 to v2.8.1
  - Fixed whitespace bypass vulnerability in HTML tag name validation
  - Issue: DisallowedRawHtml extension could be bypassed by inserting newline, tab, or ASCII whitespace between disallowed HTML tag name and closing `>` (e.g., `<script\n>`)
  - Solution: Changed regex character class `[ \/>]` to `[\s\/>]` to match all whitespace characters that browsers accept as valid tag name terminators
  - Impact: Prevents XSS attacks via markdown input processing in untrusted user content
  - All applications using DisallowedRawHtml extension to process untrusted markdown are now protected

**Verification:**
- ✅ `composer audit` - 0 security advisories found
- ✅ Composer lock file updated successfully
- ✅ All dependencies compatible - no breaking changes

---

### Version 2.5 (March 7, 2026) - Database Schema Enhancement
**Database Updates:**
- 🗄️ **Comprehensive visitor_analytics Table Restructure**
  - Enhanced with destination tracking capabilities
  - Added next_news_id for tracking visitor journey after reading
  - Added exit_time and exit_page for exit analysis
  - Added user_agent, language, screen_resolution for detailed device profiling
  - Optimized indexes for better query performance (visitor_ip, next_news_id, referrer_source)
  - Added proper foreign key constraints
  - Comprehensive documentation for all tracking fields

- 📧 **Email Marketing Integration (Feedify)**
  - Added feedify_enabled flag to seo_settings
  - Added feedify_api_key for API authentication
  - Added feedify_list_id for email list management
  - Enables seamless subscriber list building

**Technical Improvements:**
- ✅ Updated database migration (2026_03_03_add_destination_tracking)
- ✅ Enhanced VisitorAnalytic model with new relationships
- ✅ Proper field constraints and documentation in SQL schema
- ✅ Improved database performance with strategic indexes

**Benefits:**
- 🔍 Better understanding of visitor behavior flow
- 📊 More granular analytics data for insights
- 💼 Email marketing capabilities for subscriber management
- ⚡ Optimized database queries for analytics dashboard

---

### Version 2.4 (March 4, 2026) - Visitor Tracking & Advanced Analytics
**New Features:**
- 📊 **Real-time Visitor Tracking System**
  - Track every visitor's activity on news articles with comprehensive data collection
  - Captures IP addresses with proxy and Cloudflare support
  - Geographic location detection (country and city via IP geolocation)
  - Device type detection (Mobile, Tablet, Desktop)
  - Browser and operating system identification

- 🎯 **Advanced Analytics Metrics**
  - Time spent on articles (measured in seconds)
  - Scroll depth tracking (percentage of page scrolled)
  - Reading completion status
  - Referrer source analysis (Google, Facebook, Twitter, LinkedIn, WhatsApp, Bing, ChatGPT, etc.)
  - Screen resolution capture for device optimization insights
  - Language preference detection from browser headers

- 🗺️ **Destination Tracking**
  - Monitor visitor journey after reading articles
  - Track next article visited by readers
  - Exit page tracking for bounce analysis
  - Exit time recording

- 📈 **Visitor Analytics Dashboard**
  - New admin dashboard for detailed visitor analytics
  - Visitor detail view with comprehensive statistics
  - Traffic source breakdown and analysis
  - User behavior patterns and engagement metrics
  - Exportable analytics data for business intelligence

**Technical Updates:**
- ✅ New VisitorTrackingService for centralized tracking logic
- ✅ New TrackingController with validated API endpoints
- ✅ Enhanced VisitorAnalytic model with destination fields
- ✅ New database migration for destination tracking
- ✅ Client-side visitor tracking JavaScript library
- ✅ Comprehensive analytics views in admin panel

**Benefits:**
- 🔍 Deep insights into reader behavior and engagement
- 📊 Data-driven decision making for content strategy
- 🎯 Understand traffic sources and optimize marketing channels
- 📱 Device and browser insights for technical optimization
- 🌍 Geographic analytics for audience understanding

---

### Version 2.3 (March 2, 2026) - Security Hotfix
**Security Fixes:**
- 🔒 **Fixed Rollup 4 Path Traversal Vulnerability (CVE-2024-XXXXX)**
  - Upgraded Rollup from v4.57.1 to v4.59.0
  - Implemented strict filename sanitization to prevent arbitrary file write attacks
  - Added path traversal prevention in build configuration
  - Sanitizes `..` sequences, path separators, and hidden file references
  - Prevents attackers from overwriting files outside the build output directory

- 🔐 **Fixed firebase/php-jwt Weak Encryption Vulnerability**
  - Upgraded firebase/php-jwt from v6.11.1 to v7.0.3
  - Implemented improved encryption algorithms and security hardening
  - Enhanced JWT token generation and validation
  - Resolved weak encryption issue in authentication tokens

- ⚙️ **Dependency Updates**
  - All supporting packages updated for compatibility
  - No breaking changes detected
  - All tests passing - application stability maintained

**Verification:**
- ✅ `npm audit` - 0 vulnerabilities found
- ✅ `composer audit` - 0 security advisories found
- ✅ Build process verified and working correctly
- ✅ Laravel Framework v12.53.0 running smoothly

---

### Version 2.2 (February 25, 2026) - Homepage Design Phase
**New Features:**
- 🎨 **Responsive 70-30 Grid Section** - Added new grid layout on homepage:
  - Left column (70%): Content area with flexible placeholder
  - Right column (30%): Popular news widget showing top 5 news by views
  - Fully mobile responsive - stacks to single column on mobile devices
  - Clean card-based design with hover effects

**UI/UX Improvements:**
- ✨ Enhanced homepage layout with strategic content positioning
- 📱 Complete mobile responsiveness for all new sections
- 🎯 Better content organization with 70-30 split layout

---

### Version 2.1 (February 25, 2026)
**Fixes:**
- 🔧 **Fixed CSRF Token 419 Error** - Resolved token mismatch issue by:
  - Switched session driver from `database` to `cookie` for better reliability
  - Enabled session encryption (`SESSION_ENCRYPT=true`) for enhanced security
  - Configured `SESSION_SAME_SITE=lax` for proper CSRF protection
  - Set `SESSION_SECURE_COOKIE=false` for HTTP local development
  - Updated `APP_URL` to `http://localhost:8000` for correct domain matching
  - Removed database session dependency to avoid session validation failures

**Improvements:**
- ⚡ Improved session management reliability
- 🔒 Enhanced security with encrypted cookies
- 🚀 Better performance with cookie-based sessions

---

### Version 2.0 (February 22, 2026)
**Features Added:**
- ✅ Complete admin dashboard with 12+ modules
- ✅ Dynamic content pages (About, Contact, Privacy, Terms, Sitemap)
- ✅ Full SEO optimization (XML sitemaps, meta tags, schema)
- ✅ Rich text editor for About page (editable from admin)
- ✅ Contact form with email notifications
- ✅ Error pages (404, 419, 423, 500)
- ✅ Push notifications system
- ✅ Analytics and activity logs
- ✅ Multi-language support (Bengali & English)
- ✅ Production-ready security features

---

**Last Updated**: July 3, 2026
**Current Version**: 3.1
**Status**: ✅ Production Ready
**Maintained By**: Sajeb Bahadur Shil

