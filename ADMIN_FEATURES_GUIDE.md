# News Portal Admin Dashboard - Quick Reference & Feature Guide

## 🚀 Getting Started

### Access the Admin Dashboard
1. Navigate to: `http://127.0.0.1:8001/dashboard`
2. Log in with your admin credentials
3. You'll see the main admin dashboard with all statistics

### Dashboard Overview
The dashboard displays:
- Total News Posts (with file icon)
- Total Views (with eye icon)
- Total Users (with people icon)
- Newsletter Subscribers (with envelope icon)
- Recent activities and top performing content

---

## 📰 News Management

### Create a New Article
1. Click **News** in sidebar → **Add New Post**
2. Fill in required fields:
   - **Title*** - Article headline
   - **Slug** - URL-friendly identifier (auto-generated)
   - **Excerpt** - Brief summary (max 500 chars)
   - **Content*** - Full article text
3. Select category and tags
4. Upload featured image
5. Choose status: Draft / Published / Scheduled
6. Click **Create News Post**

### Edit an Existing Article
1. Go to **News** → **List**
2. Find article in table
3. Click **Edit** (pencil icon)
4. Make changes
5. Click **Update News Post**

### Delete an Article
1. Go to **News** → **List**
2. Click **Delete** (trash icon)
3. Confirm deletion

### Search and Filter News
- **Search by Title** - Type article title
- **Filter by Status** - Draft, Published, or Scheduled
- **Filter by Category** - Select from dropdown
- Click **Search** to apply filters

### View Article Statistics
- **Views Count** - Shows number of views (eye icon)
- **Featured Badge** - Indicates featured article (star icon)
- **Breaking Badge** - Indicates breaking news (fire icon)
- **Published Date** - Shows publication date

---

## 📁 Category Management

### Create a New Category
1. Click **Categories** in sidebar → **Add Category**
2. Enter **Category Name*** (required)
3. **Slug** auto-generates from name (can edit)
4. Add **Description** (optional)
5. Click **Create Category**

### Edit a Category
1. Go to **Categories** → **List**
2. Click **Edit** (pencil icon)
3. Update name/slug/description
4. Click **Update Category**

### Delete a Category
1. Go to **Categories** → **List**
2. Click **Delete** (trash icon)
3. Confirm - associated news won't be affected

### View Category Statistics
- **Post Count** - Badge showing number of articles in category
- **Slug** - URL identifier displayed in code format

---

## 🏷️ Tag Management

### Create a New Tag
1. Click **Tags** in sidebar → **Add Tag**
2. Enter **Tag Name*** (required)
3. **Slug** auto-generates from name
4. Add **Description** (optional)
5. Click **Create Tag**

### Edit a Tag
1. Go to **Tags** → **List**
2. Click **Edit** (pencil icon)
3. Update name/slug/description
4. Click **Update Tag**

### Delete a Tag
1. Go to **Tags** → **List**
2. Click **Delete** (trash icon)
3. Confirm deletion

### View Tag Statistics
- **Uses Count** - Badge showing how many articles use this tag

---

## 👥 User Management

### Create a New Admin User
1. Click **Users** in sidebar → **Add User**
2. Enter user details
3. Set role/permissions
4. Verify email
5. Click **Create User**

### Edit a User
1. Go to **Users** → **List**
2. Click **Edit** (pencil icon)
3. Update user information
4. Click **Update User**

### Delete a User
1. Go to **Users** → **List**
2. Click **Delete** (trash icon)
3. Confirm - cannot delete yourself
4. User data will be deleted

### View User Statistics
- **Status** - Active or Inactive (color badges)
- **Join Date** - When user created account
- **Posts** - Number of articles written by user

### Search and Filter Users
- Search by name or email
- Filter by Active/Inactive status

---

## 📊 Analytics Dashboard

### Key Metrics
The analytics page shows four main stat cards:

1. **Total Views** <i class="bi bi-eye"></i>
   - All-time page views
   - Updates in real-time

2. **Total Posts** <i class="bi bi-file-text"></i>
   - Count of all articles
   - Includes draft and published

3. **Total Categories** <i class="bi bi-folder"></i>
   - Number of content categories

4. **Total Users** <i class="bi bi-people"></i>
   - Count of admin/editorial users

### Top Performing News
- Shows articles with highest views
- Displays category assignment
- Quick view of trending content
- Limited to top articles

### News by Category
- Breakdown of articles per category
- View count per category
- Identify popular topics
- Plan content strategy

### Recent Activities
- Latest admin actions
- User attribution
- Action type (created, updated, deleted)
- Timestamp and relative time

---

## 🔔 Activity Logs

### What Gets Logged
- **News Creation** - When articles are created
- **News Updates** - When articles are edited
- **News Deletion** - When articles are removed
- **Category Changes** - All category modifications
- **User Management** - User account changes
- **Settings Updates** - Configuration changes

### Search and Filter Activities
- **Search by User** - Find activities by person
- **Search by Action** - Find specific action types
- **Filter by Type** - News, Category, User, etc.
- **Sort by Date** - Newest first

### Activity Details
- **User Name & Email** - Who performed action
- **Action Type** - Color-coded badge (Created, Updated, Deleted, Viewed)
- **Description** - What was changed
- **Date & Time** - When action occurred
- **Relative Time** - "2 hours ago" format

---

## ⚙️ Settings

### Basic Settings
- **Site Name** - Your portal name (used in SEO)
- **Site Description** - Portal tagline/description
- **Meta Keywords** - Comma-separated for search engines

### Analytics Tracking
Add tracking codes for:
- **Google Analytics ID** - Format: `G-XXXXXXXXXX`
- **Google Tag Manager ID** - Format: `GTM-XXXXXXX`
- **Facebook Pixel ID** - Your pixel number

These track user behavior and conversions.

### Social Media Links
Add your social media URLs:
- Facebook
- Twitter/X
- Instagram
- YouTube
- LinkedIn

These may appear in website footer or sharing features.

### Save Settings
- Fill in desired fields
- Click **Save Settings** (check icon)
- Settings saved successfully
- Use in website frontend

---

## 🎨 UI Features & Icons

### Bootstrap Icons Used
All buttons and sections use Bootstrap Icons:

**Navigation Icons:**
- 🏠 House - Dashboard
- 📄 File-text - News
- 📁 Folder - Categories
- 🏷️ Tags - Tags
- 👥 People - Users
- 📈 Graph-up - Analytics
- ⏰ Clock-history - Activity Logs
- ⚙️ Gear - Settings

**Action Icons:**
- ✏️ Pencil - Edit
- 👁️ Eye - View
- 🗑️ Trash - Delete
- ➕ Plus - Create/Add
- ✓ Check - Confirm/Save
- ✕ X - Cancel
- 🔍 Search - Filter

**Status Badges:**
- Green (Success) - Published articles
- Blue (Info) - Drafts
- Yellow (Warning) - Scheduled
- Red (Danger) - Inactive/Deleted

---

## 🔐 Admin Panel Security

### Password Protection
- All admin pages require login
- Sessions expire after inactivity
- Secure password hashing

### CSRF Protection
- All forms protected against CSRF attacks
- Tokens automatically included

### Action Confirmation
- Delete actions require confirmation
- Prevent accidental deletions
- "Are you sure?" dialogs

### Self-Deletion Prevention
- Cannot delete your own account
- Prevents admin lockout
- Delete button disabled for current user

---

## 📱 Mobile Usage

### Responsive Design
The admin panel works perfectly on:
- **Desktop** (1024px+) - Full sidebar navigation
- **Tablet** (768px-1023px) - Collapsible sidebar
- **Mobile** (<768px) - Hamburger menu toggle

### Mobile Navigation
1. Click **hamburger icon** (three lines) in top-left
2. Sidebar slides from left
3. Tap menu items to navigate
4. Tap outside to close sidebar

### Mobile Tables
- Horizontal scroll for wide tables
- All columns accessible
- Touch-friendly buttons

---

## 🚨 Common Tasks

### Publish a Draft Article
1. Go to News → List
2. Find draft article
3. Click Edit
4. Change status to "Published"
5. Click Update

### Change Article Category
1. Go to News → List
2. Click Edit on article
3. Select new category from dropdown
4. Click Update

### Promote an Article to Featured
1. Go to News → List
2. Click Edit on article
3. Check "Featured" checkbox
4. Click Update

### Mark as Breaking News
1. Go to News → List
2. Click Edit on article
3. Check "Breaking News" checkbox
4. Click Update

### Monitor Site Traffic
1. Go to Analytics
2. Check "Total Views" stat card
3. Review "Top Performing News"
4. See trending topics in category breakdown

### Export User List
1. Go to Users
2. View all users in table
3. Note down user information
4. Users searchable and filterable

---

## 🔗 Direct Links

**Quick Access URLs:**

Dashboard: `/dashboard`
News: `/admin/news`
Create News: `/admin/news/create`
Categories: `/admin/categories`
Tags: `/admin/tags`
Users: `/admin/users`
Analytics: `/admin/analytics`
Activity Logs: `/admin/activities`
Settings: `/admin/settings`

---

## 💡 Pro Tips

1. **Auto-generate Slugs** - Leave slug field empty to auto-generate from title/name
2. **Search Everywhere** - Almost every list page has search functionality
3. **Bulk Operations** - Use filters to group similar items before actions
4. **Track Changes** - Check Activity Logs to see who changed what and when
5. **Social Sharing** - Configure social media links in Settings for website footer
6. **Analytics** - Regularly check analytics to understand content performance
7. **User Management** - Keep user list updated for security
8. **Backup Settings** - Note important settings before changes
9. **Mobile Friendly** - Admin panel fully responsive for on-the-go management
10. **Keyboard Shortcuts** - Forms support standard browser shortcuts (Tab, Enter, etc.)

---

**Admin Panel Version:** Bootstrap 5.3.3 + Bootstrap Icons 1.11.3
**Last Updated:** 2024
**Status:** ✅ Production Ready
