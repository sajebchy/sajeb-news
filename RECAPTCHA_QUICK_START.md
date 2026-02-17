# Phase 18F: Quick Start Guide - reCAPTCHA Admin Settings

## 🚀 What's New?

A new "Security (reCAPTCHA)" tab in the admin settings panel allows you to configure reCAPTCHA v3 spam protection without editing code.

---

## ⚡ Quick Start (3 Steps)

### Step 1: Get reCAPTCHA Credentials
1. Visit: https://www.google.com/recaptcha/admin
2. Click **"+ Create"**
3. Fill form:
   - Label: `Sajeb News Live Comments`
   - Type: **reCAPTCHA v3**
   - Domains: Your domain (e.g., `sajebbd.news`, `127.0.0.1`)
4. Copy the displayed **Site Key** and **Secret Key**

### Step 2: Access Admin Settings
1. Log in to admin dashboard
2. Go to: **Settings** → **Admin Settings**
3. Click: **Security (reCAPTCHA)** tab

### Step 3: Configure Settings
1. **Site Key**: Paste from Google
2. **Secret Key**: Paste from Google
3. **Threshold**: `0.5` (recommended)
4. **Toggle**: Enable ✓
5. Click: **"Save Security Settings"**

---

## 📊 Form Fields Explained

| Field | Type | Purpose | Example |
|-------|------|---------|---------|
| **Site Key** | Text | Public key, shown in frontend | `6LeIxAcT4PF21-qN...` |
| **Secret Key** | Password | Private key, kept on server | `6LeIxAcT4PF21-qQ...` |
| **Threshold** | Number (0.0-1.0) | Spam strictness level | `0.5` |
| **Toggle** | Checkbox | Enable/Disable protection | ON/OFF |

### Threshold Guide
```
0.1 (Very Strict)  → Rejects ~90% of comments (many false positives)
0.5 (Balanced)     → Rejects ~50% of comments (recommended)
0.9 (Very Lenient) → Rejects ~10% of comments (allows spam)
```

---

## ✅ Verification Checklist

After saving, verify everything works:

- [ ] Form saved without errors
- [ ] Page shows "Settings updated successfully!"
- [ ] Post comment on a live stream
- [ ] Comment appears normally (if not spam)
- [ ] Check browser console (F12) - no errors
- [ ] Check logs: `storage/logs/laravel.log`

---

## 🔧 Settings Storage

- **Database Table**: `seo_settings`
- **Columns**:
  - `recaptcha_site_key` - Public key
  - `recaptcha_secret_key` - Secret key
  - `recaptcha_threshold` - Threshold value
  - `recaptcha_enabled` - On/Off status

---

## 📝 How It Works

```
User posts comment
    ↓
reCAPTCHA token generated (invisible)
    ↓
Backend receives token + comment
    ↓
Verify token with Google (using Secret Key)
    ↓
Get spam score (0.0 = bot, 1.0 = human)
    ↓
Compare to threshold:
  - If score > threshold → Approve comment
  - If score < threshold → Reject comment
    ↓
Run content analysis (keywords, duplicates, etc.)
    ↓
Final decision: Approve/Reject/Flag
```

---

## 🛑 Troubleshooting

### Problem: Form not saving
**Solution:**
1. Check validation errors on page
2. Ensure threshold is between 0.0 and 1.0
3. Check browser console (F12) for errors
4. Check server logs: `php artisan tail`

### Problem: Comments always rejected
**Solution:**
1. Verify Secret Key is correct (check for typos)
2. Increase threshold to 0.7 or 0.8
3. Check if reCAPTCHA is enabled
4. Verify Google service is working

### Problem: Too much spam getting through
**Solution:**
1. Decrease threshold to 0.3 or 0.4
2. Monitor spam patterns
3. Adjust manually based on spam volume

---

## 🔐 Security Notes

✅ **DO:**
- Keep Secret Key private
- Use HTTPS for admin panel
- Regularly check Google console for abuse

❌ **DON'T:**
- Share Secret Key publicly
- Use very low threshold (0.0)
- Disable reCAPTCHA on public sites

---

## 📚 Full Documentation

For detailed information, see:
- `RECAPTCHA_ADMIN_SETTINGS.md` - User guide
- `PHASE_18F_RECAPTCHA_ADMIN_SETTINGS_COMPLETE.md` - Implementation details
- `PHASE_18_COMPLETE_SUMMARY.md` - Full Phase 18 overview

---

## 🎯 What Gets Protected?

- ✅ Live stream comments
- ✅ User behavior analysis
- ✅ Spam keyword detection
- ✅ Duplicate comment prevention
- ✅ Rapid-fire comment detection

---

## ⏱️ Time to Configure

- Get credentials: **5 minutes**
- Enter in admin: **1 minute**
- Total: **~6 minutes**

---

**Status**: Ready to use! 🚀
