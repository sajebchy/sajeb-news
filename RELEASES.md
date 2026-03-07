# Release Notes - Sajeb NEWS

## [v2.5](https://github.com/sajebchy/sajeb-news/releases/tag/v2.5) - March 7, 2026 - Database Schema Enhancement

### 🗄️ Database Schema Updates

#### **Comprehensive visitor_analytics Table Restructure** ⭐
Major overhaul of the visitor analytics table with enhanced tracking and destination analysis.

**New Fields Added:**
- ✅ `next_news_id` - Track next article visited by visitor (foreign key to news table)
- ✅ `exit_time` - Timestamp of when visitor left the article
- ✅ `exit_page` - URL of the exit page for bounce analysis
- ✅ `user_agent` - Full user agent string for advanced device profiling
- ✅ `language` - Browser language preference from headers
- ✅ `screen_resolution` - Device screen resolution (WIDTHxHEIGHT format)

**Enhanced Indexes:**
- 🔍 `visitor_ip_index` - For IP-based visitor lookup
- 🔍 `next_news_id_index` - For destination tracking analysis
- 🔍 `referrer_source_index` - For traffic source analysis
- 🔍 `news_id_visit_date_index` - Composite index for date-range queries

**Field Documentation:**
- All tracking fields now include descriptive comments in database schema
- Clear documentation for analytics interpretation

#### **Email Marketing Integration (Feedify)**
New fields added to `seo_settings` table for email list building:
- ✅ `feedify_enabled` (boolean) - Enable/disable Feedify integration
- ✅ `feedify_api_key` (varchar) - API key for authentication
- ✅ `feedify_list_id` (varchar) - Email list ID for subscriber management

**Benefits:**
- Enable email marketing campaigns
- Build and manage subscriber lists
- Integrate with Feedify API for email automation

### 🔧 Technical Implementation
- ✅ Database migration: `2026_03_03_add_destination_tracking`
- ✅ Updated SQL schema file with complete table definitions
- ✅ Proper foreign key relationships and constraints
- ✅ Optimized indexes for analytics dashboard queries
- ✅ Backward compatible with existing data

### 📊 Impact on Analytics
- Enhanced visitor journey tracking (know where visitors go next)
- Better exit analysis for bounce rate calculation
- Improved device profiling for technical optimization
- Email marketing foundation for subscriber engagement

### 📁 Updated Files
- `sajeb_news_mysql.sql` - Complete schema with all updates
- Database migrations - v2.5 migration included
- Model documentation - Updated VisitorAnalytic model

---

## [v2.2](https://github.com/sajebchy/sajeb-news/releases/tag/v2.2) - February 25, 2026 - Homepage Design Phase

### 🎨 Major UI/UX Updates

#### **New 70-30 Grid Section** ⭐ RESPONSIVE DESIGN
A new strategic homepage layout combining content area with popular news sidebar.

**Features:**
- ✅ Responsive 70-30 grid layout
  - **Left Column (70%)** - Content area with flexible content placeholder
  - **Right Column (30%)** - Popular news widget showing top 5 trending news
- ✅ Dynamically displays most viewed news articles
- ✅ Full mobile responsiveness - stacks to single column on tablets/mobile
- ✅ Smooth hover effects and transitions
- ✅ Integrated with existing news database views tracking

**Design Details:**
- Clean white cards with subtle shadows
- Numbered badges (1-5) for popular news ranking
- View count display for each popular article
- Consistent with existing design system
- Proper spacing and responsive gap handling

**Mobile Responsiveness:**
- Desktop: 70% left | 30% right side-by-side
- Tablet/Mobile: Single column layout (fully responsive stacking)
- Adjusted padding and margins for mobile devices
- Mobile-optimized text sizes and spacing

### 🏗️ Homepage Layout Structure
The homepage now features:
1. Subscribe Modal (top)
2. Categories Section (📂 বিভাগসমূহ)
3. Featured News (⭐ প্রধান খবর) - 3 cards
4. **New: 70-30 Grid Section** (📌 বিশেষ বিভাগ)
5. Advertisement (Home Top)
6. Main Content Grid + Sidebar
7. News Ticker (fixed bottom)

### 🔧 Technical Implementation
- Pure CSS Grid layout (no JavaScript required)
- Media query breakpoint at 768px for mobile
- Leverages existing News model for popular articles
- Integrated with existing styling system

---

## [v2.1](https://github.com/sajebchy/sajeb-news/releases/tag/v2.1) - February 25, 2026

### 🔧 Major Fixes

#### **Fixed CSRF Token 419 Error** ⭐ CRITICAL FIX
The persistent 419 CSRF token mismatch error during login has been permanently resolved.

**Root Cause:**
- Database session driver had reliability issues with localhost:8000 URLs
- Session validation was failing due to domain/port mismatch
- CSRF token wasn't being properly regenerated in sessions

**Solution Implemented:**
- Switched session driver from `database` to `cookie` for better reliability
- Enabled session encryption (SESSION_ENCRYPT=true) to protect session data
- Configured SESSION_SAME_SITE=lax for proper CSRF token handling
- Set SESSION_SECURE_COOKIE=false for HTTP local development
- Updated APP_URL to http://localhost:8000 for correct domain matching
- Removed database session dependency to avoid validation failures

### ⚡ Performance Improvements
- Encrypted cookie-based sessions are faster than database lookups
- Reduced database query overhead for session management
- Self-contained session data in cookies (no external validation needed)

### 🔒 Security Enhancements
- Session data is now encrypted before being stored in cookies
- SESSION_SAME_SITE=lax provides proper CSRF protection
- SESSION_HTTP_ONLY=true prevents JavaScript access to session cookies
- Middleware properly handles encrypted session data

---

## [v2.0](https://github.com/sajebchy/sajeb-news/releases/tag/v2.0) - February 22, 2026

### ✨ Major Features Added

#### Complete Admin Dashboard
- 12+ Admin Modules with full CRUD operations
- Role-Based Access Control (Admin, Editor, Reporter, Author)
- Advanced Analytics (News views, visitor stats, performance tracking)
- Activity Logging for security and audit trails
- Push Notifications system for subscribers

#### News Management System
- Hierarchical categories and tags system
- Multimedia support (images, videos, embeds)
- Draft, schedule, and published states
- Featured and breaking news controls
- Full-text search capability

#### Enterprise-Grade SEO
- Dynamic XML &HTML Sitemaps
- Meta Tags (Title, Description, Keywords)
- Open Graph Tags for social sharing
- JSON-LD Schema support
- Canonical URLs
- Robots.txt

---

## Git Commit History

### v2.5
- **Commit**: `0637917` - feat: Update database schema with visitor tracking and advanced analytics

### v2.2
- **Commit**: `0937223` - feat: Add 70-30 grid section with mobile responsive design

### v2.1
- **Commit**: `95ee91b` - Fix: Resolve CSRF Token 419 Error & Update Documentation

### v2.0
- Multiple development commits for feature implementation

---

**Last Updated**: March 7, 2026  
**Current Version**: 2.5  
**Status**: ✅ Production Ready
