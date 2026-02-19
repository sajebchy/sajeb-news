# Google AdSense Integration Guide

## বাংলা (Bengali): গুগল এডসেন্স ইন্টিগ্রেশন গাইড

---

## Overview

গুগল এডসেন্স ইন্টিগ্রেশন Sajeb News এর বিজ্ঞাপন ম্যানেজমেন্ট সিস্টেমে সম্পূর্ণভাবে যোগ করা হয়েছে। এখন আপনি:

✅ Google AdSense কোড সরাসরি admin panel থেকে যোগ করতে পারবেন
✅ AdSense policy compliance স্বয়ংক্রিয়ভাবে পরীক্ষা করা হবে
✅ Standard ads এবং AdSense ads আলাদাভাবে পরিচালনা করতে পারবেন
✅ AdSense নির্দিষ্ট settings সংরক্ষণ করতে পারবেন

---

## Feature Summary

### 1. Ad Type Selection
নতুন বিজ্ঞাপন তৈরি করার সময়, **Ad Type** নির্বাচন করতে পারবেন:

- **Standard Ad (Image/Text)**: সাধারণ ইমেজ/টেক্সট বিজ্ঞাপন
- **Image Ad**: শুধু ইমেজ বিজ্ঞাপন
- **Video Ad**: ভিডিও বিজ্ঞাপন
- **Google AdSense**: Google AdSense স্ক্রিপ্ট

### 2. AdSense-Specific Fields

যখন আপনি **Google AdSense** নির্বাচন করবেন, তখন নতুন fields দেখা যাবে:

```
┌─────────────────────────────────────────┐
│  Google AdSense Configuration          │
├─────────────────────────────────────────┤
│                                         │
│  • AdSense Code (Required)              │
│    └─ Google এর সম্পূর্ণ script code   │
│                                         │
│  • Ad Slot ID (Required)                │
│    └─ AdSense ad unit থেকে পাওয়া     │
│                                         │
│  • Publisher ID (Required)              │
│    └─ Format: pub-XXXXXXXXXXXXXXXX     │
│                                         │
│  • Enable AdSense Checkbox              │
│    └─ এডসেন্স enable/disable করুন    │
│                                         │
└─────────────────────────────────────────┘
```

### 3. AdSense Policy Compliance

সিস্টেম স্বয়ংক্রিয়ভাবে এই নিয়মগুলো পরীক্ষা করে:

📋 **Policy Requirements:**
- ✓ Maximum 3 ads per page (সর্বাধিক 3টি বিজ্ঞাপন প্রতি পেজে)
- ✓ Minimum 300 words of content (কমপক্ষে 300 শব্দ কন্টেন্ট)
- ✓ No prohibited content (বর্জিত কন্টেন্ট নেই)
- ✓ Valid AdSense code format (বৈধ এডসেন্স কোড ফরম্যাট)
- ✓ Valid Publisher ID format (pub-16 digits) (বৈধ publisher ID)

---

## Step-by-Step Guide

### Step 1: Access Admin Dashboard
```
Dashboard → Advertisements
```

### Step 2: Create New Advertisement
```
Click "Create New Advertisement" button
```

### Step 3: Fill Basic Information
```
Name: e.g., "Homepage AdSense Banner"
Placement: Select desired placement
Type: Select any (banner, sidebar, etc.)
Device Target: Select device type
Ad Type: Select "Google AdSense" ⭐
```

### Step 4: Enter AdSense Details

**AdSense Code:**
Google AdSense account থেকে এই কোড কপি করুন এবং paste করুন:

```html
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-xxxxxxxxxxxxxxxx"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-xxxxxxxxxxxxxxxx"
     data-ad-slot="xxxxxxxxxx"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
```

**Ad Slot ID:**
- AdSense ad code এ `data-ad-slot="XXXXXXXXXX"` থেকে slot ID নিন
- Example: `1234567890123456`

**Publisher ID:**
- AdSense code এ `ca-pub-xxxxxxxxxxxxxxxx` থেকে নিন
- Remove "ca-" prefix, use format: `pub-xxxxxxxxxxxxxxxx`
- Example: `pub-0123456789012345`

### Step 5: Schedule & Save
```
Start Date: বিজ্ঞাপন শুরু হওয়ার সময়
End Date: বিজ্ঞাপন শেষ হওয়ার সময় (optional)
Enable AdSense checkbox: চেক করুন
Click "Create Advertisement"
```

---

## Database Schema

### New Columns Added

```sql
ALTER TABLE advertisements ADD COLUMN (
    ad_type ENUM('standard', 'image', 'video', 'adsense') DEFAULT 'standard',
    adsense_code TEXT,
    adsense_slot_id VARCHAR(50),
    adsense_publisher_id VARCHAR(50),
    is_adsense_enabled BOOLEAN DEFAULT FALSE,
    disable_page_limit INTEGER DEFAULT 3,
    minimum_content_length INTEGER DEFAULT 300
);
```

---

## Model Methods

### Check if Ad is AdSense

```php
$ad = Advertisement::find(1);

// Check if this is an active AdSense ad
if ($ad->isAdSense()) {
    echo "This is an AdSense ad";
}
```

### Get AdSense Code

```php
$code = $ad->getAdSenseCode();
// Returns sanitized AdSense code or null
```

### Validate AdSense Code Format

```php
if ($ad->isValidAdSenseCode()) {
    echo "Valid AdSense code format";
}
```

### Check Compliance Status

```php
$compliance = $ad->checkAdSenseCompliance();
// Returns: [
//     'is_compliant' => true/false,
//     'issues' => ['issue1', 'issue2'],
//     'warnings' => ['warning1']
// ]
```

### Get AdSense Policies

```php
$policies = Advertisement::getAdSensePolicies();
/**
 * Returns:
 * {
 *     'max_ads_per_page': 3,
 *     'min_content_length': 300,
 *     'prohibited_content': [...],
 *     'must_have': [...]
 * }
 */
```

### Query AdSense Ads Only

```php
$adsenseAds = Advertisement::adSenseOnly()->get();
```

---

## Validation Rules

### Controller Validation (store/update methods)

```php
'ad_type' => 'nullable|in:standard,image,video,adsense',
'adsense_code' => 'required_if:ad_type,adsense|nullable|string',
'adsense_slot_id' => 'required_if:ad_type,adsense|nullable|string|max:50',
'adsense_publisher_id' => 'required_if:ad_type,adsense|nullable|string|regex:/^pub-[0-9]{16}$/',
'is_adsense_enabled' => 'boolean',
```

### Additional Validation

System automatically checks:
✓ AdSense code contains 'adsbygoogle' reference
✓ Publisher ID format: `pub-` followed by exactly 16 digits
✓ Slot ID is not empty when ad_type is adsense

---

## Key Features Implemented

### ✅ Complete AdSense Support
- AdSense code storage and retrieval
- Publisher ID validation
- Slot ID management
- AdSense enable/disable toggle

### ✅ Policy Compliance Checking
- Automatic validation of AdSense code format
- Policy guideline enforcement
- Compliance status reporting
- Warning system for potential issues

### ✅ User Interface Enhancements
- Conditional field visibility (shows AdSense fields only when AdSense type is selected)
- Clear policy guidelines displayed in form
- Real-time validation feedback
- Bootstrap-styled form sections with proper alerts

### ✅ Database Support
- New columns for AdSense data storage
- Proper indexing for performance
- Nullable fields for flexibility
- Default values for policy settings

### ✅ Model Methods
- `isAdSense()` - Check if ad is AdSense type
- `getAdSenseCode()` - Get sanitized code
- `isValidAdSenseCode()` - Validate code format
- `checkAdSenseCompliance()` - Full compliance check
- `getAdSensePolicies()` - Get policy guidelines
- `adSenseOnly()` - Query scope for AdSense ads

---

## Important Notes

⚠️ **Before Using AdSense:**
1. Ensure your website is approved by Google AdSense
2. Install AdSense code properly from your AdSense account
3. Test ads in preview mode before publishing
4. Check Google AdSense policies regularly

⚠️ **Content Requirements:**
- Minimum 300 words per article (configurable)
- No prohibited content (violence, adult content, etc.)
- Original, quality content
- Proper formatting and readability

⚠️ **Ad Placement Limits:**
- Maximum 3 ads per page (Google AdSense policy)
- Proper spacing between ads
- Visibility over the fold
- User-friendly layouts

---

## Frontend Integration

### For Displaying AdSense Ads in Frontend

Use the placement system to show AdSense ads:

```php
// In your blade template
@php
    $ads = \App\Models\Advertisement::adSenseOnly()
        ->where('placement', 'homepage_banner')
        ->where('is_active', true)
        ->get();
@endphp

@foreach($ads as $ad)
    @if($ad->isAdSense() && $ad->isValidAdSenseCode())
        <div class="ad-container">
            {!! $ad->getAdSenseCode() !!}
        </div>
    @endif
@endforeach
```

---

## Troubleshooting

### Problem: AdSense fields not showing
**Solution:** Make sure to select "Google AdSense" from the Ad Type dropdown.

### Problem: Publisher ID validation fails
**Solution:** Ensure format is `pub-` followed by exactly 16 digits. Example: `pub-0123456789012345`

### Problem: AdSense code not saving
**Solution:** Ensure code contains 'adsbygoogle' reference and is valid HTML/JavaScript.

### Problem: Ads not displaying on frontend
**Solution:** Check if ad is enabled, compliance is met, and placement is configured correctly.

---

## Testing the System

### Test 1: Create AdSense Ad
```
1. Go to Admin → Advertisements → Create
2. Fill basic info
3. Select Ad Type = "Google AdSense"
4. Fill AdSense fields
5. Click Create
6. Verify ad appears in list
```

### Test 2: Edit AdSense Ad
```
1. Click edit on AdSense ad
2. Modify AdSense fields
3. Save changes
4. Verify updates in database
```

### Test 3: Compliance Check
```
1. Create ad with invalid Publisher ID
2. System should reject with error
3. Fix format: pub-XXXXXXXXXXXXXXXX
4. Save successfully
```

---

## Related Documentation

- [ADVERTISEMENTS_COMPLETE_GUIDE.md](./ADVERTISEMENTS_COMPLETE_GUIDE.md) - Complete ad system guide
- [ADVERTISEMENT_QUICK_START.md](./ADVERTISEMENT_QUICK_START.md) - Quick start guide
- [README_ADVERTISEMENTS.md](./README_ADVERTISEMENTS.md) - Advertisement system overview

---

## Support Information

For issues or questions:
1. Check [ADVERTISEMENTS_COMPLETE_GUIDE.md](./ADVERTISEMENTS_COMPLETE_GUIDE.md)
2. Review model methods in `app/Models/Advertisement.php`
3. Check controller validation in `app/Http/Controllers/Admin/AdController.php`
4. Review view templates in `resources/views/admin/advertisements/`

---

## Version Information

- **Implementation Date**: February 19, 2026
- **Framework**: Laravel 11
- **Database**: SQLite
- **Google AdSense Policy Compliance**: ✅ Included

---

**Last Updated**: February 19, 2026
**Status**: ✅ Complete and Ready for Production
