# reCAPTCHA Admin Settings Configuration Guide

## Overview
This guide explains how to configure reCAPTCHA v3 credentials through the Sajeb News admin settings panel at `/admin/settings`.

## Features
- ✅ Manage reCAPTCHA Site Key and Secret Key from admin panel
- ✅ Configure spam detection threshold (0.0 - 1.0)
- ✅ Enable/disable reCAPTCHA protection with a toggle
- ✅ Real-time settings update without code changes
- ✅ Secure storage in database with password-masked form fields
- ✅ Form validation for all fields

## Getting Started

### Step 1: Access Admin Settings
1. Log in to your admin dashboard
2. Navigate to **Settings** → **Admin Settings**
3. Look for the new **Security (reCAPTCHA)** tab

### Step 2: Obtain reCAPTCHA Credentials
1. Visit [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Sign in with your Google account
3. Click **"+ Create"** to create a new site
4. Fill in the form:
   - **Label**: Sajeb News Live Comments
   - **reCAPTCHA type**: reCAPTCHA v3
   - **Domains**: Your domain (e.g., sajebbd.news, 127.0.0.1)
5. Accept terms and click **Create**
6. You'll receive:
   - **Site Key** (public, safe to display)
   - **Secret Key** (keep private, never share)

### Step 3: Configure in Admin Panel
1. Click the **Security (reCAPTCHA)** tab
2. Fill in the form fields:

#### Field: reCAPTCHA Site Key
- **Value**: Copy from Google reCAPTCHA Admin Console
- **Purpose**: Used on frontend to identify your site
- **Visibility**: Public (shown in page source)
- **Example**: `6LeIxAcT4PF21-qNgqHxyHVMPNjNgVVwFHuGRCWg`

#### Field: reCAPTCHA Secret Key
- **Value**: Copy from Google reCAPTCHA Admin Console
- **Purpose**: Used on backend to verify reCAPTCHA responses
- **Visibility**: Private (stored as password field in UI)
- **Example**: `6LeIxAcT4PF21-qQQQ-H7jjjjjjjj_g45rNvSvGg`

#### Field: Spam Detection Threshold
- **Range**: 0.0 to 1.0
- **Recommended**: 0.5 (balanced)
- **Lower values** (0.1): Strict - rejects more comments (more false positives)
- **Higher values** (0.9): Lenient - allows more comments (more spam gets through)

**Threshold Guidance:**
| Score | Likelihood | Recommended For |
|-------|-----------|-----------------|
| 0.0-0.3 | Very likely spam | High-traffic public forums |
| 0.3-0.5 | Likely spam | Most news sites |
| 0.5-0.7 | Balanced | Moderate spam expected |
| 0.7-1.0 | Likely human | Low spam, friendly community |

#### Field: Enable reCAPTCHA
- **Toggle**: On/Off switch
- **Purpose**: Quickly disable/enable spam protection without removing credentials
- **Use Cases**: 
  - Enable during testing
  - Disable temporarily for maintenance
  - Keep enabled in production

### Step 4: Save Configuration
1. Click **"Save Security Settings"** button
2. You'll see a success message: "Settings updated successfully!"
3. Settings are now stored in the database

### Step 5: Verify Configuration
1. Settings are automatically loaded in live stream comment forms
2. Users will see invisible reCAPTCHA protection on comment submission
3. Spam detection will begin immediately if enabled

## Database Storage

The reCAPTCHA settings are stored in the `seo_settings` table:

```sql
SELECT recaptcha_site_key, recaptcha_secret_key, recaptcha_threshold, recaptcha_enabled 
FROM seo_settings 
LIMIT 1;
```

**Columns:**
- `recaptcha_site_key` (VARCHAR): Your site's public key
- `recaptcha_secret_key` (VARCHAR): Your site's secret key (stored plaintext)
- `recaptcha_threshold` (DECIMAL 3,1): Threshold value (0.0-1.0)
- `recaptcha_enabled` (TINYINT): Boolean flag (0 or 1)

## Configuration File

Settings are accessible via `config('social.recaptcha')`:

```php
// Access in application code
$siteKey = config('social.recaptcha.site_key');
$secretKey = config('social.recaptcha.secret_key');
$threshold = config('social.recaptcha.threshold');
$enabled = config('social.recaptcha.enabled');
```

## Form Validation Rules

The admin panel applies the following validation:

```php
'recaptcha_site_key' => 'nullable|string|max:255',
'recaptcha_secret_key' => 'nullable|string|max:255',
'recaptcha_threshold' => 'nullable|numeric|min:0|max:1',
'recaptcha_enabled' => 'nullable|boolean',
```

**Validation Details:**
- All fields are optional (nullable)
- Site & secret keys: String, max 255 characters
- Threshold: Numeric value between 0.0 and 1.0
- Enabled: Boolean (0 or 1, or "on" for checkbox)

## Frontend Integration

The reCAPTCHA v3 script is automatically loaded in live stream watch pages:

```html
<script src="https://www.google.com/recaptcha/api.js?render=YOUR_SITE_KEY"></script>
<script>
    grecaptcha.execute('YOUR_SITE_KEY', {action: 'submit'})
        .then(function(token) {
            document.getElementById('recaptcha_token').value = token;
        });
</script>
```

**Token Generation:**
- Token generated automatically before comment submission
- Token sent to backend in hidden form field
- Token verified against secret key on server

## Backend Spam Detection

The `SpamDetectionService` uses reCAPTCHA tokens for multi-layer protection:

1. **reCAPTCHA Score Verification**: Google verifies token, returns score (0-1)
2. **Threshold Comparison**: Score compared to your configured threshold
3. **Content Analysis**: Keywords and patterns checked
4. **Behavior Scoring**: User spam score calculated (0-100)
5. **Duplicate Detection**: Comments checked in 5-minute window

**Comment Flow:**
```
User submits comment → reCAPTCHA token generated → Backend receives token
→ Verify with Google (uses secret key) → Get score → Compare to threshold
→ Run content analysis → Check duplicates → Calculate user score
→ Decision: Approve/Reject/Flag for review
```

## Testing Configuration

### Test with Valid Credentials
1. Enter valid Site and Secret keys from Google reCAPTCHA Admin
2. Set threshold to 0.5 (balanced)
3. Enable the toggle
4. Save settings
5. Try posting a comment on a live stream
6. Verify comment is processed

### Test with Invalid Credentials
1. Enter incorrect secret key
2. Save settings
3. Try posting a comment
4. Should see error in logs (check `storage/logs/laravel.log`)

### Test Spam Detection
1. Try posting same comment twice within 5 minutes
2. Try posting 5+ comments within 10 minutes
3. Try posting with spam keywords: viagra, casino, lottery, etc.
4. Comments should be rejected based on threshold and detection method

## Troubleshooting

### Issue: "Settings updated successfully" but reCAPTCHA not working
**Solution:**
1. Verify Site Key matches in Google console
2. Check domain is whitelisted in Google reCAPTCHA settings
3. Ensure Secret Key is correct (typo prevention)
4. Check that reCAPTCHA is enabled (toggle = ON)
5. Clear browser cache and try again

### Issue: All comments rejected as spam
**Solution:**
1. Your threshold is too strict (too low)
2. Increase threshold to 0.7 or 0.8
3. Check if Google reCAPTCHA service is down
4. Disable reCAPTCHA temporarily, post test comment, re-enable

### Issue: Too many spam comments getting through
**Solution:**
1. Decrease threshold to 0.3 or 0.4
2. Verify Google console shows reCAPTCHA service is active
3. Check if spam keywords are configured in `config/social.php`
4. Review comment moderation logs for patterns

### Issue: Form not saving after clicking "Save Security Settings"
**Solution:**
1. Check browser console for JavaScript errors (F12 → Console)
2. Check validation errors:
   - Site Key must be < 255 characters
   - Secret Key must be < 255 characters
   - Threshold must be between 0.0 and 1.0
3. Ensure user has admin role with settings permission
4. Check `storage/logs/laravel.log` for database errors

## Security Best Practices

✅ **DO:**
- Keep Secret Key in password-protected admin panel
- Use reCAPTCHA v3 (invisible, user-friendly)
- Set threshold between 0.3-0.7 for balance
- Monitor spam patterns and adjust threshold
- Enable only when database is secure
- Regularly check Google reCAPTCHA Admin console for fraud patterns

❌ **DON'T:**
- Don't share Secret Key in code repositories
- Don't hardcode credentials in `.env` (use admin panel)
- Don't set threshold to 0.0 (blocks everyone)
- Don't set threshold to 1.0 (allows all spam)
- Don't use reCAPTCHA v2 (old, shows puzzle)
- Don't disable HTTPS for admin panel

## System Architecture

### Data Flow
```
Admin Panel Form
    ↓
SettingController::update()
    ↓
Validate reCAPTCHA fields
    ↓
Save to seo_settings table
    ↓
Load in config('social.recaptcha')
    ↓
Frontend receives Site Key
    ↓
User posts comment with token
    ↓
Backend verifies with Secret Key
    ↓
Compare score to threshold
    ↓
Spam detection layers
    ↓
Approve/Reject comment
```

### Related Files

**Configuration:**
- `config/social.php` - reCAPTCHA config loader

**Controllers:**
- `app/Http/Controllers/Admin/SettingController.php` - Handles form submission

**Models:**
- `app/Models/SeoSetting.php` - Database model

**Migrations:**
- `database/migrations/*_add_recaptcha_settings_to_seo_settings_table.php`

**Views:**
- `resources/views/admin/settings/index.blade.php` - Settings form (Security tab)
- `resources/views/live-streams/watch.blade.php` - Comment form with reCAPTCHA

**Services:**
- `app/Services/SpamDetectionService.php` - Spam detection logic

## Support & Resources

- [Google reCAPTCHA Documentation](https://developers.google.com/recaptcha/docs/v3)
- [reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
- [Integration Test Results](./RECAPTCHA_FINAL_SUMMARY.md)
- [Spam Detection Guide](./RECAPTCHA_SPAM_DETECTION_GUIDE.md)

## Version Information

- **Feature Added**: Phase 18F - Admin Settings Integration
- **reCAPTCHA Version**: v3 (Invisible)
- **Laravel Version**: 11.x
- **Database**: SQLite / MySQL / PostgreSQL
- **Last Updated**: 2026-02-03

---

**Status**: ✅ **COMPLETE** - reCAPTCHA admin configuration fully implemented and tested
