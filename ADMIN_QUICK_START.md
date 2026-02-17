# Quick Start Guide - Admin Dashboard

## 🔐 Admin Login
- **URL**: `http://127.0.0.1:8000/login`
- **Email**: `admin@test.com`
- **Password**: `12345`
- **Role**: Super Admin (Full Access)

---

## 📍 Dashboard Sections

### 1. **Home Dashboard** `/admin`
Shows overview with:
- 4 stat cards (News, Views, Users, Subscribers)
- Monthly views chart
- Category distribution
- Recent posts list
- Activity log

### 2. **News Management** `/admin/news`
**Create News Post**:
- Title, Slug, Category
- Content with excerpt
- Featured image upload
- Status (Draft, Published, Scheduled)
- Featured/Breaking badges
- Tags (comma-separated)
- Auto-save support

**Edit News**:
- Same form structure
- Keep or replace featured image
- Change publish date
- Update tags and categories

### 3. **Categories** `/admin/categories`
**Features**:
- Add parent categories
- Create subcategories
- Color coding (custom colors)
- Font Awesome icons
- Hierarchical structure

### 4. **Tags** `/admin/tags`
**Features**:
- Color picker for each tag
- Tag descriptions
- Usage tracking
- Bulk management

### 5. **Users** `/admin/users`
**Manage Users**:
- View all users and roles
- Edit user details
- Change roles:
  - **Super Admin** - Full access
  - **Admin** - Content management
  - **Editor** - Publish/edit posts
  - **Reporter** - Create/edit own posts
  - **Author** - Create posts only
- Activate/deactivate users
- Delete users (except self)

### 6. **Analytics** `/admin/analytics`
**View Metrics**:
- Total views across platform
- Total clicks
- Engagement score
- Average read time
- Top performing news
- Category performance
- Views per category

### 7. **Activity Logs** `/admin/activities`
**Track**:
- User actions (50 per page)
- Timestamps (relative & absolute)
- Action types
- User information
- Full action descriptions

### 8. **Settings** `/admin/settings`
**Configure**:
- Site name & description
- Meta keywords
- OG image for social sharing
- Google Analytics 4 ID
- Google Tag Manager ID
- Facebook Pixel ID
- Social media links
- Robots.txt content

---

## 🎯 Common Tasks

### Add a News Post
1. Go to `/admin/news`
2. Click "Add New Post"
3. Fill in title, content, category
4. Upload featured image (optional)
5. Select status (Draft/Published)
6. Click "Create Post"
7. Edit to add tags

### Create a Category
1. Go to `/admin/categories`
2. Click "Add Category"
3. Enter name (slug auto-generates)
4. Select parent (optional)
5. Pick a color
6. Choose Font Awesome icon
7. Click "Create Category"

### Manage Users
1. Go to `/admin/users`
2. Click edit button (pencil icon)
3. Change name, email, phone
4. Select roles (check boxes)
5. Toggle active status
6. Save changes

### Configure Site Settings
1. Go to `/admin/settings`
2. Enter site name & description
3. Add analytics IDs (GA4, GTM, FB Pixel)
4. Add social media URLs
5. Edit robots.txt if needed
6. Upload OG image
7. Save settings

---

## 🎨 UI Features

### Sidebar Navigation
- Always visible on desktop
- Collapsible on mobile
- Active page highlighting
- Quick logout button
- Profile link
- View live site option

### Top Navigation
- Page title display
- User dropdown menu
- Settings quick access
- Notifications area (ready for enhancement)

### Status Indicators
- 🟢 Published (Green badge)
- 🟡 Draft (Gray badge)
- 🟠 Scheduled (Orange badge)
- 🟢 Active (Green)
- 🔴 Inactive (Red)

### Actions
- ✏️ Edit button
- 👁️ View button
- 🗑️ Delete button (with confirmation)
- ➕ Add/Create buttons

---

## ⚙️ Technical Details

### Validation Rules

**News**:
- Title: Required, unique, max 255 chars
- Content: Required
- Category: Required
- Image: Max 5MB, image types only
- Status: draft|published|scheduled

**Categories**:
- Name: Required, unique
- Slug: Auto-generated from name
- Color: Hex format (#RRGGBB)

**Tags**:
- Name: Required, unique, max 100
- Color: Optional hex color

**Users**:
- Name: Required, max 255
- Email: Required, unique
- Phone: Optional
- Roles: At least one role required

---

## 🔔 Notifications

### Success Messages
- "✅ Item created/updated successfully!"
- Green banner with dismiss button

### Error Messages
- Red banner with detailed error list
- Form fields highlighted in red
- Inline field error messages

### Delete Confirmation
- "Are you sure?" dialog
- Prevents accidental deletion
- No undo (data permanently deleted)

---

## 📊 Database Relations

```
News
├─ Category (belongs to)
├─ User/Author (belongs to)
├─ Tags (many to many)
└─ Analytics (one to many)

User
├─ Roles (many to many)
└─ News Articles (one to many)

Category
├─ Parent Category (self-reference)
└─ News Posts (one to many)

Tag
└─ News Posts (many to many)
```

---

## 🚀 Performance Optimizations

- **Pagination**: 15 news items, 20 categories/tags, 50 activity logs per page
- **Lazy Loading**: Images use lazy load attributes
- **Query Optimization**: Uses eager loading with `with()`
- **Caching Ready**: Settings can be cached
- **Asset Compression**: CSS/JS minified in production

---

## 🔒 Security Notes

1. **CSRF Protection**: All forms use `@csrf` token
2. **Authentication**: All routes require login + email verification
3. **Authorization**: Role-based permissions via Spatie
4. **Input Validation**: Server-side validation on all forms
5. **File Upload**: Type and size validation
6. **SQL Injection**: Parameterized queries via Eloquent ORM
7. **XSS Protection**: Blade templating escapes output

---

## 📞 Support

For detailed documentation, see:
- `ADMIN_PANEL_DOCUMENTATION.md` - Complete feature guide
- `DOCUMENTATION.md` - Full project documentation
- `FEATURES.md` - Feature checklist
- `PROJECT_STATUS.md` - Development roadmap

---

**Last Updated**: February 3, 2026
**Version**: 1.0
**Status**: Production Ready ✅
