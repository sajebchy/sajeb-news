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

# Run migrations and seeders
php artisan migrate --seed

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

## 📁 Project Structure

```
sajeb-news/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/              # Admin panel controllers
│   │   └── Public/             # Public-facing controllers
│   ├── Models/                 # Database models
│   ├── Mail/                   # Mailable classes
│   └── Services/               # Business logic services
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/              # Admin templates
│   │   ├── public/             # Public pages
│   │   │   ├── pages/          # Dynamic pages (About, Contact, etc)
│   │   │   └── errors/         # Error page templates
│   │   └── emails/             # Email templates
│   ├── css/                    # Stylesheet files
│   └── js/                     # JavaScript files
├── routes/
│   ├── web.php                 # Web routes
│   ├── api.php                 # API routes
│   └── auth.php                # Authentication routes
└── public/
    ├── storage/                # User uploads
    ├── sitemap.xml            # Dynamic XML sitemap
    ├── robots.txt             # SEO robots file
    └── llm.txt                # AI/LLM info file
```

---

## 🎯 Available Routes

### Public Routes
```
GET  /                          → Home page
GET  /news/{slug}               → News detail
GET  /category/{slug}           → Category feed
GET  /tag/{tag}                 → Tag based news
GET  /author/{id}               → Author profile
GET  /search                    → Search news

# Dynamic Pages
GET  /about                     → About page (editable from admin)
GET  /contact                   → Contact form
POST /contact                   → Submit contact form
GET  /privacy-policy            → Privacy policy
GET  /terms-and-conditions      → Terms of service
GET  /sitemap                   → HTML sitemap

# SEO Files
GET  /sitemap.xml               → XML sitemap (for search engines)
GET  /robots.txt                → Robot instructions
GET  /llm.txt                   → LLM information

# Live Streaming
GET  /live                      → Live streams list
GET  /live/{slug}               → Watch live stream
```

---

## 🛠️ Admin Settings Panel

The admin settings panel at `/admin/settings` includes:

### Basic Settings Tab
- Site name, URL, and title
- Meta description and keywords
- **About Page Content** (Rich text editor with Quill.js)
  - Full formatting support
  - Image and video embedding
  - Bengali language support
  - Code blocks
  - Headings, lists, blockquotes

### Additional Settings Tabs
- Logos & Images (desktop, mobile, OG image)
- Analytics (Google Analytics, GTM)
- Social Media links
- JSON-LD Schema configuration
- Push notification settings

---

## 📊 Database Schema

### Main Tables
- `users` - User accounts with roles
- `news` - News articles
- `categories` - News categories
- `tags` - News tags
- `comments` - News comments
- `activity_logs` - User activity tracking
- `seo_settings` - SEO and site configuration
- `advertisements` - Ad management
- `push_subscriptions` - Push notification subscriptions
- `newsletter_subscribers` - Newsletter subscribers

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

## 🎉 Latest Updates

### Version 2.0 (February 22, 2026)
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

**Last Updated**: February 22, 2026  
**Status**: ✅ Production Ready  
**Maintained By**: Sajeb Bahadur Shil

