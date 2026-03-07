# Sajeb NEWS - Bangladesh News Portal

A complete professional, dynamic, and SEO-optimized news portal platform built for Bangladesh. Built with **Laravel 11**, **Bootstrap 5**, and modern web technologies.

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

### 📰 News Management
- ✅ Hierarchical categories and tags system
- ✅ Multimedia support (images, videos, embeds)
- ✅ Draft, schedule, and published states
- ✅ Featured and breaking news controls
- ✅ Related news auto-suggestions
- ✅ Full-text search capability
- ✅ News analytics and performance tracking

### 🎨 Dynamic Pages (Editable from Admin)
- ✅ **About Page** - Rich text editor, fully customizable from `/admin/settings`
- ✅ **Contact Page** - Working contact form with email notifications
- ✅ **Privacy Policy** - GDPR compliant legal page
- ✅ **Terms & Conditions** - Complete service terms
- ✅ **Sitemap Page** - HTML sitemap with categories and recent news
- ✅ **Error Pages** - Custom 404, 419, 423, 500 error pages

### 🔍 Enterprise-Grade SEO
- ✅ **Dynamic Sitemaps** - XML & HTML sitemaps auto-generated
- ✅ **Meta Tags** - Title, Description, Keywords per page
- ✅ **Open Graph Tags** - Social media sharing optimization
- ✅ **Twitter Cards** - Twitter-specific social sharing
- ✅ **JSON-LD Schema** - Organization, News Article, Contact schemas
- ✅ **Canonical URLs** - Prevent duplicate content issues
- ✅ **Robots.txt** - Search engine crawling guidance
- ✅ **LLM.txt File** - AI/LLM friendly content format
- ✅ **Breadcrumb Navigation** - Improved user experience & SEO

### ⚡ Performance Features
- ✅ GZIP compression (all text assets)
- ✅ Browser caching strategies (1 year for static, 1 hour for dynamic)
- ✅ Image optimization service
- ✅ Redis caching support
- ✅ Database query optimization
- ✅ CDN-ready structure
- ✅ Lazy loading for images

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
- ✅ Bootstrap 5 grid system
- ✅ Touch-friendly interface
- ✅ Optimized for all devices
- ✅ Fast loading times
- ✅ Accessibility compliant

### 🌐 Multi-Language Support
- ✅ Bengali (বাংলা) fully supported
- ✅ English fallback
- ✅ Proper font handling (Noto Serif Bengali)
- ✅ Right-to-left text support ready

---

## 📦 Tech Stack

### Backend
- **Framework**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 8.0 / PostgreSQL
- **Cache**: Redis / Memcached
- **Queue**: Redis / Database
- **Storage**: Local / S3 Compatible

### Frontend
- **CSS Framework**: Bootstrap 5.3
- **JavaScript**: Vanilla JS + Quill Editor
- **Icons**: Bootstrap Icons + FontAwesome
- **Fonts**: Noto Serif Bengali, Google Fonts

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
- PHP 8.2 or higher
- MySQL 8.0 or PostgreSQL 13+
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

**Last Updated**: March 4, 2026
**Current Version**: 2.4
**Status**: ✅ Production Ready
**Maintained By**: Sajeb Bahadur Shil

