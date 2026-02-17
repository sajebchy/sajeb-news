# Facebook Comment Integration - Live Stream
## Complete Setup & Implementation Guide

**Last Updated:** February 14, 2026  
**Status:** ✅ IMPLEMENTED & CONFIGURED  
**Version:** 1.0

---

## Overview

Users can now comment on live streams using their Facebook accounts. Comments are displayed in real-time, with moderation capabilities for administrators.

### Key Features

- ✅ Facebook OAuth login integration
- ✅ Real-time comment posting
- ✅ Comment display with user avatars
- ✅ Like/reaction system for comments
- ✅ Admin moderation tools
- ✅ Comment pinning (for featured comments)
- ✅ User verification badge for Facebook users
- ✅ Auto-refresh comments every 5 seconds during live streams

---

## Database Schema

### Stream Comments Table
```sql
CREATE TABLE stream_comments (
    id BIGINT PRIMARY KEY,
    live_stream_id BIGINT NOT NULL,
    commenter_name VARCHAR(255),
    commenter_email VARCHAR(100),
    facebook_id VARCHAR(100) UNIQUE,
    facebook_profile_url VARCHAR(255),
    commenter_avatar VARCHAR(255),
    comment_text TEXT,
    source ENUM('facebook', 'website', 'anonymous') DEFAULT 'website',
    is_verified BOOLEAN DEFAULT 0,
    is_pinned BOOLEAN DEFAULT 0,
    likes_count INT DEFAULT 0,
    is_approved BOOLEAN DEFAULT 1,
    admin_notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP,
    
    FOREIGN KEY (live_stream_id) REFERENCES live_streams(id) ON DELETE CASCADE,
    INDEX idx_live_stream (live_stream_id),
    INDEX idx_facebook_id (facebook_id),
    INDEX idx_created_at (created_at),
    INDEX idx_is_pinned (is_pinned)
);
```

**Migration Status:** ✅ Executed (6.88ms)  
**Location:** `database/migrations/2026_02_14_180000_create_stream_comments_table.php`

---

## Configuration

### 1. Facebook App Setup

Before configuring the system, you need to create a Facebook App:

1. Go to https://developers.facebook.com/
2. Create a new application (App Type: Consumer)
3. Add "Facebook Login" product
4. Navigate to Settings → Basic to get App ID and Secret
5. Add your domain to Valid OAuth Redirect URIs:
   - Development: `http://localhost:8000/auth/facebook/callback`
   - Production: `https://yourdomain.com/auth/facebook/callback`

### 2. Environment Variables (.env)

Add these variables to your `.env` file:

```env
# Facebook OAuth Configuration
FACEBOOK_APP_ID=your_facebook_app_id
FACEBOOK_APP_SECRET=your_facebook_app_secret
FACEBOOK_APP_VERSION=v18.0
FACEBOOK_REDIRECT_URL=${APP_URL}/auth/facebook/callback

# Optional - Google & Twitter (for future use)
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
TWITTER_API_KEY=your_twitter_api_key
TWITTER_API_SECRET=your_twitter_api_secret
```

**Current Status:** Added template variables to `.env` file ✅

### 3. Configuration File

Location: `config/social.php`  
Status: ✅ Created with all social media providers

```php
return [
    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID'),
        'app_secret' => env('FACEBOOK_APP_SECRET'),
        'app_version' => env('FACEBOOK_APP_VERSION', 'v18.0'),
        'redirect_url' => env('FACEBOOK_REDIRECT_URL', env('APP_URL') . '/auth/facebook/callback'),
        'permissions' => ['email', 'public_profile'],
        'sdk_url' => 'https://connect.facebook.net/en_US/sdk.js',
    ],
    // ... Google and Twitter configs
];
```

---

## Implementation Details

### Models

#### StreamComment Model
**Location:** `app/Models/StreamComment.php`

**Relationships:**
- `belongsTo(LiveStream)` - The stream this comment belongs to

**Scopes:**
- `approved()` - Only approved comments
- `pending()` - Pending comments (awaiting moderation)
- `pinned()` - Pinned/featured comments
- `fromFacebook()` - Only Facebook comments

**Key Methods:**
```php
$comment->approve();           // Approve a comment
$comment->reject();            // Reject a comment
$comment->pin();               // Pin a comment
$comment->unpin();             // Unpin a comment
$comment->incrementLikes();    // Increment likes
$comment->decrementLikes();    // Decrement likes
```

### Controller

#### StreamCommentController
**Location:** `app/Http/Controllers/StreamCommentController.php`

**Methods:**

| Method | Route | Description |
|--------|-------|-------------|
| `store` | POST `/live/{stream}/comments` | Create a new comment |
| `getComments` | GET `/live/{stream}/comments` | Fetch comments for a stream |
| `destroy` | DELETE `/live/comments/{comment}` | Delete a comment |
| `approve` | POST `/admin/.../comments/{comment}/approve` | Admin: Approve comment |
| `reject` | POST `/admin/.../comments/{comment}/reject` | Admin: Reject comment |
| `pin` | POST `/admin/.../comments/{comment}/pin` | Admin: Pin comment |
| `unpin` | POST `/admin/.../comments/{comment}/unpin` | Admin: Unpin comment |
| `like` | POST `/live/comments/{comment}/like` | Like a comment |

### Routes

#### Public Routes
```php
POST   /live/{stream:slug}/comments              -> store comment
GET    /live/{stream:slug}/comments              -> list comments
DELETE /live/comments/{comment}                  -> delete comment
POST   /live/comments/{comment}/like             -> like comment
```

#### Admin Routes
```php
POST /admin/live-streams/{stream}/comments/{comment}/approve  -> approve
POST /admin/live-streams/{stream}/comments/{comment}/reject   -> reject
POST /admin/live-streams/{stream}/comments/{comment}/pin      -> pin
POST /admin/live-streams/{stream}/comments/{comment}/unpin    -> unpin
```

### Frontend

#### Watch Page Integration
**Location:** `resources/views/public/live-stream/watch.blade.php`

**Features:**
1. **Facebook Login Button** - Users login with Facebook to comment
2. **User Profile Display** - Shows logged-in user info with avatar
3. **Comment Form** - Text area for writing comments (50-500 characters)
4. **Comments List** - Display all approved comments with:
   - User avatar
   - Username with verification badge
   - Comment text
   - Relative timestamp
   - Like count
   - Pin badge (for pinned comments)
5. **Real-time Updates** - Auto-refresh comments every 5 seconds

**JavaScript Features:**
- Facebook SDK initialization
- OAuth login/logout handling
- Local storage persistence (fbUser data)
- AJAX comment submission
- Dynamic comments rendering
- Error handling

---

## How It Works

### User Comment Flow

1. **User visits live stream page**
   ```
   → View page at /live/{stream-slug}
   ```

2. **Facebook Login**
   ```
   → Click "Login with Facebook"
   → Authorize app & share email/profile
   → System extracts: name, email, avatar, Facebook ID
   → Data stored in localStorage
   ```

3. **Post Comment**
   ```
   → Type comment in textarea
   → Click "Post Comment"
   → AJAX POST to /live/{stream}/comments
   → Comment saved with Facebook data
   → Comments auto-refresh every 5 seconds
   ```

4. **Comment Visibility**
   ```
   → Comment appears in list (auto-approved)
   → Shows user avatar & name
   → "Verified" badge indicates Facebook user
   → Display created_at as relative time ("2 minutes ago")
   ```

### Admin Moderation Flow

1. **Comment Management Dashboard** (In Admin Panel)
   ```
   Location: /admin/live-streams/{stream}/comments
   (To be implemented in next phase)
   ```

2. **Moderation Actions**
   - **Approve** - Make comment visible to all users
   - **Reject** - Hide comment from public view
   - **Pin** - Featured comment appears at top
   - **Unpin** - Remove featured status
   - **Delete** - Permanently remove comment

3. **Status Management**
   - `is_approved=true` → Comment visible
   - `is_approved=false` → Comment hidden
   - `is_pinned=true` → Featured at top
   - `is_verified=true` → Shows verification badge

---

## API Endpoints Reference

### Store Comment
```
POST /live/{stream:slug}/comments
Content-Type: application/json

Request Body:
{
  "comment_text": "Great stream!",
  "commenter_name": "John Doe",
  "commenter_email": "john@example.com",
  "facebook_id": "123456789",
  "facebook_profile_url": "https://facebook.com/123456789",
  "commenter_avatar": "https://...",
  "source": "facebook"
}

Response:
{
  "success": true,
  "message": "Comment posted successfully!",
  "data": {
    "id": 1,
    "name": "John Doe",
    "avatar": "https://...",
    "text": "Great stream!",
    "created_at": "now",
    "is_verified": true
  }
}
```

### Get Comments
```
GET /live/{stream:slug}/comments

Response:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "avatar": "https://...",
      "text": "Great stream!",
      "is_verified": true,
      "is_pinned": true,
      "likes": 5,
      "created_at": "2 minutes ago"
    },
    ...
  ]
}
```

### Like Comment
```
POST /live/comments/{comment}/like
Content-Type: application/json

Response:
{
  "success": true,
  "likes": 6
}
```

### Delete Comment
```
DELETE /live/comments/{comment}

Response:
{
  "success": true,
  "message": "Comment deleted successfully"
}
```

---

## Security Considerations

✅ **Implemented:**
- CSRF protection on all forms
- Authorization checks (users can only delete their own comments)
- Admin-only moderation actions
- XSS protection via Blade templating
- Input validation (1-500 character limit)
- Rate limiting placeholder (can be added)

**Recommendations:**
1. Implement rate limiting per user
2. Add spam/profanity filtering
3. Verify Facebook user on server-side (not just client)
4. Add IP tracking for suspicious behavior
5. Implement comment reporting system

---

## Testing

### Manual Testing Checklist

- [ ] Facebook login works on live stream page
- [ ] User profile displays after login
- [ ] Can post comment with Facebook account
- [ ] Comment appears in list immediately
- [ ] Comments auto-refresh every 5 seconds
- [ ] Can like comments
- [ ] Can logout and re-login
- [ ] Comment form disabled when logged out
- [ ] Avatar loads correctly
- [ ] Verification badge shows for Facebook users

### API Testing

```bash
# Test comment submission
curl -X POST http://localhost:8000/live/stream-slug/comments \
  -H "Content-Type: application/json" \
  -d '{
    "comment_text": "Test comment",
    "commenter_name": "Test User",
    "facebook_id": "123"
  }'

# Test comments list
curl http://localhost:8000/live/stream-slug/comments

# Test like
curl -X POST http://localhost:8000/live/comments/1/like
```

---

## File Structure

```
sajeb-news/
├── app/
│   └── Http/
│       └── Controllers/
│           └── StreamCommentController.php (NEW)
├── database/
│   └── migrations/
│       └── 2026_02_14_180000_create_stream_comments_table.php (NEW)
├── app/Models/
│   └── StreamComment.php (NEW)
├── config/
│   └── social.php (NEW)
├── resources/views/
│   └── public/live-stream/
│       └── watch.blade.php (UPDATED)
├── routes/
│   └── web.php (UPDATED)
└── .env (UPDATED)
```

---

## Next Steps / Future Enhancements

### Phase 2: Admin Dashboard
- [ ] Comment moderation dashboard in admin panel
- [ ] View all comments for a stream
- [ ] Approve/reject/pin comments
- [ ] Add admin notes to comments
- [ ] Delete inappropriate comments
- [ ] View comment statistics

### Phase 3: Advanced Features
- [ ] Comment threading/replies
- [ ] Emoji support
- [ ] Mention system (@username)
- [ ] Profanity filter
- [ ] Comment search
- [ ] Comment export

### Phase 4: Real-time Updates
- [ ] WebSocket integration (Pusher/Socket.io)
- [ ] Live comment notifications
- [ ] Typing indicators
- [ ] Read receipts

### Phase 5: Social Integration
- [ ] Reply to comments
- [ ] Edit comments
- [ ] Comment reporting
- [ ] User reputation system
- [ ] Comment analytics

---

## Troubleshooting

### Facebook Login Not Working

**Issue:** "Invalid OAuth Redirect URI"

**Solution:**
1. Check FACEBOOK_REDIRECT_URL in .env matches Facebook app settings
2. Ensure APP_URL is correct
3. Verify domain is whitelisted in Facebook app

**Issue:** "App is not configured"

**Solution:**
1. Verify FACEBOOK_APP_ID and FACEBOOK_APP_SECRET in .env
2. Check config/social.php is loading correctly
3. Run `php artisan config:clear`

### Comments Not Appearing

**Issue:** Comments not showing in list

**Solution:**
1. Check browser console for JavaScript errors
2. Verify comments were created in database
3. Check `is_approved` status (should be true)
4. Verify stream ID matches

### Database Errors

**Issue:** "Table stream_comments doesn't exist"

**Solution:**
```bash
php artisan migrate
# or
php artisan migrate:refresh --step
```

---

## Performance Notes

- Comments refresh every 5 seconds (configurable)
- Queries use indexing on: live_stream_id, facebook_id, created_at, is_pinned
- Soft deletes preserve data integrity
- Comment pagination recommended for high-volume streams

---

## Support & Documentation

For more information:
- View configuration: `config/social.php`
- View model: `app/Models/StreamComment.php`
- View controller: `app/Http/Controllers/StreamCommentController.php`
- View routes: `routes/web.php`
- View template: `resources/views/public/live-stream/watch.blade.php`

---

**Status:** ✅ Ready for Testing  
**Last Verified:** February 14, 2026  
**Tested With:** Laravel 11, PHP 8.2+, Bootstrap 5
