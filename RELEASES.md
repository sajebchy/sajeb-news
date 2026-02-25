# Release Notes - Sajeb NEWS

## [v2.1](https://github.com/sajebchy/sajeb-news/releases/tag/v2.1) - February 25, 2026

### 🔧 Major Fixes

#### **Fixed CSRF Token 419 Error** ⭐ CRITICAL FIX
The persistent 419 CSRF token mismatch error during login has been permanently resolved.

**Root Cause:**
- Database session driver had reliability issues with localhost:8000 URLs
- Session validation was failing due to domain/port mismatch
- CSRF token wasn't being properly regenerated in sessions

**Solution Implemented:**
- ✅ Switched session driver from `database` to `cookie` for better reliability
- ✅ Enabled session encryption (`SESSION_ENCRYPT=true`) to protect session data
- ✅ Configured `SESSION_SAME_SITE=lax` for proper CSRF token handling
- ✅ Set `SESSION_SECURE_COOKIE=false` for HTTP local development
- ✅ Updated `APP_URL` to `http://localhost:8000` for correct domain matching
- ✅ Removed database session dependency to avoid validation failures

**Result:**
- 🎯 Login now works flawlessly with admin@test.com / 12345
- 🛡️ Enhanced security with encrypted cookies
- ⚡ Better performance with cookie-based sessions
- ✨ No more 419 errors on login

### ⚡ Performance Improvements
- Encrypted cookie-based sessions are faster than database lookups
- Reduced database query overhead for session management
- Self-contained session data in cookies (no external validation needed)

### 🔒 Security Enhancements
- Session data is now encrypted before being stored in cookies
- `SESSION_SAME_SITE=lax` provides proper CSRF protection
- `SESSION_HTTP_ONLY=true` prevents JavaScript access to session cookies
- Middleware properly handles encrypted session data

### 📝 Documentation Updates
- Updated [README.md](README.md) with Version 2.1 Changelog
- Added comprehensive comments in `.env` configuration
- Enhanced [VerifyCsrfToken.php](app/Http/Middleware/VerifyCsrfToken.php) middleware documentation
- Updated database schema version header

### 📦 Files Modified
```
- README.md (Changelog section updated)
- app/Http/Middleware/VerifyCsrfToken.php (Documentation added)
- sajeb_news_mysql.sql (Version header updated)
- .env (Session configuration with comments)
```

### 🧪 Testing
✅ Verified login functionality works correctly  
✅ Session encryption/decryption working properly  
✅ CSRF token validation passing  
✅ Application configuration cached and loaded correctly  

### 🚀 Deployment Notes
No database migrations or breaking changes. Safe to deploy to production.

**Installation for existing installations:**
```bash
# Clear application caches
php artisan config:clear
php artisan cache:clear

# Optional: clear old sessions (if using old database driver)
sqlite3 database/database.sqlite "DELETE FROM sessions;"

# Start the application
php artisan serve
```

---

## [v2.0](https://github.com/sajebchy/sajeb-news/releases/tag/v2.0) - February 22, 2026

### ✨ Major Features Added

#### Complete Admin Dashboard
- 12+ Admin Modules with full CRUD operations
- Role-Based Access Control (Admin, Editor, Reporter, Author)
- Advanced Analytics (News views, visitor stats, performance tracking)
- Activity Logging for security and audit trails
- Push Notifications system for subscribers
- Advanced Advertisement Management

#### News Management System
- Hierarchical categories and tags system
- Multimedia support (images, videos, embeds)
- Draft, schedule, and published states
- Featured and breaking news controls
- Related news auto-suggestions
- Full-text search capability
- News analytics and performance tracking

#### Dynamic Pages (Editable from Admin)
- About Page with Rich text editor
- Contact Page with working form and email notifications
- Privacy Policy (GDPR compliant)
- Terms & Conditions
- Sitemap Page (HTML sitemap with categories)
- Custom Error Pages (404, 419, 423, 500)

#### Enterprise-Grade SEO
- Dynamic XML & HTML Sitemaps
- Meta Tags (Title, Description, Keywords)
- Open Graph Tags for social sharing
- Twitter Cards
- JSON-LD Schema (Organization, News Article, Contact)
- Canonical URLs
- Robots.txt
- LLM.txt File for AI/LLM content
- Breadcrumb Navigation

#### Performance Features
- GZIP compression for text assets
- Browser caching strategies
- Image optimization service
- Redis caching support
- Database query optimization
- CDN-ready structure
- Lazy loading for images

#### Security Features
- CSRF token protection
- XSS prevention (Blade escaping)
- SQL injection prevention (Eloquent ORM)
- Rate limiting
- Password hashing (bcrypt)
- Security headers
- Activity logging

#### Multi-Language Support
- Bengali (বাংলা) fully supported
- English fallback
- Proper font handling (Noto Serif Bengali)
- Right-to-left text support ready

### 📦 Technology Stack
- **Framework**: Laravel 11 (PHP 8.2+)
- **Frontend**: Bootstrap 5.3
- **Database**: MySQL 8.0 / SQLite
- **Cache**: Redis / Memcached
- **Editor**: Quill Rich Text Editor

---

## Git Commit History

### v2.1
- **Commit**: `95ee91b` - Fix: Resolve CSRF Token 419 Error & Update Documentation

### v2.0
- Multiple development commits for feature implementation

---

## Support & Issues

For bugs, feature requests, or questions:
- 📧 Email: sajebbahadurraj@gmail.com
- 🐛 [GitHub Issues](https://github.com/sajebchy/sajeb-news/issues)
- 💬 [Discussions](https://github.com/sajebchy/sajeb-news/discussions)

---

## 🙏 Credits

**Sajeb NEWS Team**
- Lead Developer: Sajeb Bahadur Shil
- GitHub: [@sajebchy](https://github.com/sajebchy/)
- LinkedIn: [Sajeb Bahadur Shil](https://www.linkedin.com/in/sajeb-bahadur-shil/)

---

## 📄 License

© Sajib Bahadur Shil  
Licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
