# Multi-Network Ad Support - Complete Integration Guide

**Status**: ✅ COMPLETE & PRODUCTION READY  
**Date**: February 19, 2026  
**Networks Supported**: 11+ Premium Ad Networks

---

## 📋 Supported Ad Networks

Your Sajeb News platform now supports 12 different ad monetization networks:

| Network | Type | Best For | Priority |
|---------|------|----------|----------|
| **Google AdSense** | Display Ads | Beginners, General Content | ⭐⭐⭐⭐⭐ |
| **Media.net** | Native/Display | Medium Traffic Blogs | ⭐⭐⭐⭐ |
| **Ezoic** | AI Optimization | Growing Publishers | ⭐⭐⭐⭐ |
| **PropellerAds** | Pop-under/Banners | High Traffic Sites | ⭐⭐⭐⭐ |
| **Mediavine** | Premium Display | 25K/month+ visitors | ⭐⭐⭐ |
| **Raptive** (formerly AdThrive) | Premium Display | Content Heavy Sites | ⭐⭐⭐ |
| **Monumetric** | Premium Display | Qualified Publishers | ⭐⭐⭐ |
| **Adsterra** | Popup/Display/Native | Performance Marketing | ⭐⭐⭐⭐ |
| **Monetag** | Native Ads | High Engagement Sites | ⭐⭐⭐⭐ |
| **Infolinks** | In-Text Links | Text-Heavy Content | ⭐⭐⭐ |
| **Taboola/Outbrain** | Content Discovery | End-of-Article Ads | ⭐⭐⭐⭐ |
| **Amazon Associates** | Affiliate Links | Review/Product Content | ⭐⭐⭐⭐ |

---

## 🚀 How to Add Multiple Ad Networks

### Step 1: Access Admin Dashboard
```
Admin → Advertisements → Create New Advertisement
```

### Step 2: Fill Basic Information
```
Name: e.g., "Media.net Homepage Banner"
Placement: Select placement (Homepage, Sidebar, etc.)
Type: Select type (Banner, Inline, etc.)
Device Target: Select devices to target
```

### Step 3: Choose Ad Type
```
Under "Ad Type" dropdown, select one of:
- Standard Ad (Image/Text)
- Image Ad
- Video Ad
- Google AdSense
```

### Step 4: Select Ad Network (NEW!)
```
If you want to use an alternative network:
1. The "Alternative Ad Networks" section appears
2. Select your preferred network from dropdown
3. Required fields for that network appear automatically
4. Fill in network-specific credentials
```

### Step 5: Schedule & Save
```
Start Date: When ad goes live
End Date: When ad expires (optional)
Click "Create Advertisement"
```

---

## 📊 Network-Specific Setup Instructions

### 1. Google AdSense ✅

**Required Fields:**
- AdSense Code (complete script)
- Ad Slot ID (from ad unit)
- Publisher ID (pub-XXXXXXXXXXXXXXXX)

**Setup:**
```
1. Go to adsense.google.com
2. Create an ad unit
3. Copy code from ad unit settings
4. Extract slot ID from code
5. Add in Sajeb News admin panel
```

**Code Example:**
```html
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-xxxxxxxxxxxxxxxx"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-xxxxxxxxxxxxxxxx"
     data-ad-slot="1234567890"
     data-ad-format="auto"></ins>
```

**Tips:**
- Max 3 ads per page
- Min 300 words content required
- Must follow AdSense policies

---

### 2. Media.net

**Required Fields:**
- Media.net Script Code
- ZIP ID

**Setup:**
```
1. Go to media.net
2. Sign up as publisher
3. Get your siteid
4. Copy script code from dashboard
5. Add to Sajeb News
```

**Code Location:**
- Login → Publisher Dashboard → Site Settings
- Copy the script code from "Ad Code" section

**Performance:**
- Good for mid-size blogs
- Instant payouts available
- CPM: $0.50-$5+

---

### 3. Ezoic

**Required Fields:**
- Ezoic Namespace
- Zone ID

**Setup:**
```
1. Register at ezoic.com
2. Add your domain
3. Get namespace from dashboard
4. Create zones for ad placements
5. Add zone IDs in admin panel
```

**Advantages:**
- AI-powered ad optimization
- Real-time analytics
- Higher revenue potential
- Free setup

---

### 4. PropellerAds

**Required Fields:**
- Zone ID

**Setup:**
```
1. Go to propellerads.com
2. Create account (verified publishers)
3. Create zone for placement
4. Copy zone ID
5. Add to Sajeb News
```

**Ad Types:**
- Pop-under ads
- Banner ads
- Interstitial ads
- Sticky ads

**Payment:**
- CPC model
- $50+ monthly average

---

### 5. Mediavine

**Required Fields:**
- Site ID

**Setup:**
```
1. Apply at mediavine.com (requires 25K/month+ traffic)
2. Wait for approval (48-72 hours typically)
3. Get site ID from dashboard
4. Add to Sajeb News
```

**Requirements:**
- Minimum 25,000 pageviews/month
- Ad-friendly content
- 6+ months old site

**Revenue:**
- $$$ Higher rates (premium)
- Dedicated support
- Advanced analytics

---

### 6. Raptive (AdThrive)

**Required Fields:**
- Site ID

**Setup:**
```
1. Apply at raptive.com (requires minimum traffic)
2. Get approval
3. Retrieve site ID from account
4. Add to Sajeb News
```

**Similar to:**
- Mediavine (premium tier)
- Requires minimum requirements
- High revenue potential

---

### 7. Monumetric

**Required Fields:**
- Site ID

**Setup:**
```
1. Join at monumetric.com
2. Connect domain
3. Get Site ID from dashboard
4. Add to Sajeb News
```

**Features:**
- Advanced header bidding
- Price flooring
- 24/7 support
- $25/month minimum earnout

---

### 8. Adsterra

**Required Fields:**
- Zone ID
- Adsterra Script Code

**Setup:**
```
1. Register at adsterra.com
2. Create campaign/zone
3. Get zone ID and code
4. Add both to Sajeb News
```

**Ad Types:**
- Pop-up ads
- Pop-under ads
- In-page push
- Native ads

**Payment:**
- Flexible payment models
- CPM or CPC
- Weekly payouts

---

### 9. Monetag

**Required Fields:**
- Zone ID

**Setup:**
```
1. Go to monetag.com
2. Create publisher account
3. Create zones for placements
4. Copy zone ID
5. Add to Sajeb News
```

**Best For:**
- Native ad placements
- High engagement sites
- Content discovery
- $100+ monthly average

---

### 10. Infolinks

**Required Fields:**
- Site ID
- Infolinks Script Code

**Setup:**
```
1. Register at infolinks.com
2. Add site
3. Get Script ID
4. Add to Sajeb News
```

**Ad Types:**
- In-text ads (highlighted links)
- In-message ads
- In-tag ads
- Sponsored links

**Best For:**
- Text-heavy content
- Blog articles
- Reviews

---

### 11. Taboola & Outbrain

**Required Fields:**
- Container ID
- Placement ID
- Script Code

**Setup:**
```
1. Register at taboola.com or outbrain.com
2. Create campaign
3. Get container and placement IDs
4. Add to Sajeb News
```

**Placement:**
- End-of-article recommendations
- Content discovery widget
- Native format ads

**Revenue:**
- $1,000+ per campaign typical
- Content-friendly
- Good user experience

---

### 12. Amazon Associates

**Required Fields:**
- Store ID
- Ad Unit ID

**Setup:**
```
1. Sign up at affiliate-program.amazon.com
2. Create ad units
3. Get Store ID and Ad Unit ID
4. Add to Sajeb News
```

**Best For:**
- Product reviews
- Recommendations
- E-commerce content

**Commission:**
- 4-10% depending on category
- Tracked conversions
- Monthly payouts

---

## 📐 Form Fields Reference

### Network-Specific Fields That Appear Dynamically

**Google AdSense:**
```
✓ AdSense Code (textarea)
✓ Ad Slot ID (text)
✓ Publisher ID (text)
```

**Media.net:**
```
✓ Media.net Script Code (textarea)
✓ ZIP ID (text)
```

**Ezoic:**
```
✓ Ezoic Namespace (text)
✓ Zone ID (text)
```

**PropellerAds:**
```
✓ Zone ID (text)
```

**Mediavine:**
```
✓ Site ID (text)
```

**Raptive:**
```
✓ Site ID (text)
```

**Monumetric:**
```
✓ Site ID (text)
```

**Adsterra:**
```
✓ Zone ID (text)
✓ Script Code (textarea)
```

**Monetag:**
```
✓ Zone ID (text)
```

**Infolinks:**
```
✓ Site ID (text)
✓ Script Code (textarea)
```

**Taboola/Outbrain:**
```
✓ Container ID (text)
✓ Placement ID (text)
✓ Script Code (textarea)
```

**Amazon Associates:**
```
✓ Store ID (text)
✓ Ad Unit ID (text)
```

---

## 💾 Database Schema

### New Columns Added

```sql
ALTER TABLE advertisements ADD COLUMN (
    ad_network VARCHAR(100) NULL,        -- Network type
    network_config JSON NULL              -- Network-specific config
);

CREATE INDEX ad_network_index ON advertisements(ad_network);
```

### Example JSON Structure

```json
{
  "code": "<script>...</script>",
  "zone_id": "12345",
  "site_id": "example.com",
  "slot_id": "1234567890",
  "publisher_id": "pub-0123456789012345"
}
```

---

## 🎯 Model Methods for Developers

### Get All Supported Networks
```php
$networks = Advertisement::getSupportedNetworks();
// Returns: [
//     'adsense' => 'Google AdSense',
//     'media_net' => 'Media.net',
//     ...
// ]
```

### Get Network-Specific Fields
```php
$fields = Advertisement::getNetworkFields();
// Returns field requirements for each network
```

### Check if Ad Uses Specific Network
```php
$ad = Advertisement::find($id);
if ($ad->isNetwork('media_net')) {
    // Ad uses Media.net
}
```

### Get Network Configuration
```php
$code = $ad->getNetworkConfig('code');
$zoneId = $ad->getNetworkConfig('zone_id');
```

### Query Ads by Network
```php
$mediaNetAds = Advertisement::byNetwork('media_net')->get();
$allEzoicAds = Advertisement::getByNetwork('ezoic');
```

### Validate Network Config
```php
if ($ad->isNetworkConfigValid()) {
    // All required fields are filled
}
```

### Get Validation Errors
```php
$errors = $ad->getNetworkConfigErrors();
// Returns array of validation errors
```

---

## 🔄 Frontend Display (Example)

### Display Network Ads in Templates

```php
@php
    // Get all active ads for a network
    $mediaNetAds = \App\Models\Advertisement::getByNetwork('media_net');
    $ezoicAds = \App\Models\Advertisement::getByNetwork('ezoic');
@endphp

@foreach($mediaNetAds as $ad)
    @if($ad->isNetworkConfigValid())
        <div class="ad-container">
            <!-- Display ad code -->
            {!! $ad->getNetworkConfig('code') !!}
        </div>
    @endif
@endforeach
```

---

## ✅ Best Practices

### Revenue Optimization

1. **Mix Networks**
   - Use Google AdSense as primary
   - Add alternative networks for fallback
   - Maximum 3 ads per page (AdSense policy)

2. **Placement Strategy**
   - Homepage: High-visibility locations
   - Articles: Between paragraphs, end of article
   - Sidebar: Non-intrusive placements

3. **Content Requirements**
   - Minimum 300 words per page
   - Original, quality content
   - Proper formatting and readability
   - Mobile-friendly design

4. **Compliance**
   - Follow each network's policies
   - Don't mislead visitors
   - Disclose ads clearly
   - Remove prohibited content

### Revenue Comparison (Approximate)

```
Premium (Mediavine, Raptive): $10-50 per 1K views
Standard (AdSense, Media.net): $1-10 per 1K views
Performance (Propeller, Taboola): $0.50-5 per 1K views
Affiliate (Amazon): 4-10% commission
```

---

## 🐛 Troubleshooting

### Problem: Network fields not showing
**Solution:** Make sure you selected an ad network from the dropdown

### Problem: Auth/validation errors
**Solution:** Double-check credentials and formatting

### Problem: Ads not displaying
**Solutions:**
- Verify network is enabled/active
- Check if page meets minimum requirements
- Validate network config is complete
- Check browser console for errors

### Problem: Low revenue
**Solution:**
- Mix multiple networks
- Improve content quality
- Increase traffic
- Optimize ad placements
- Test different networks

---

## 📞 Support

### For Each Network

- **Google AdSense**: support.google.com
- **Media.net**: Contact publisher support
- **Ezoic**: ezoic.com/support
- **PropellerAds**: support.propellerads.com
- **Mediavine**: mediavine.com/contact
- **Raptive**: raptive.com/contact
- **Monumetric**: monumetric.com/support
- **Adsterra**: adsterra.com/contact
- **Monetag**: monetag.com/support
- **Infolinks**: infolinks.com/support
- **Taboola**: taboola.com/contact
- **Outbrain**: outbrain.com/contact
- **Amazon Associates**: amazon.com/associates

---

## 📈 Analytics & Monitoring

### Track Multiple Networks

```php
// Get stats by network
$stats = Advertisement::where('ad_network', 'media_net')
    ->selectRaw('SUM(clicks) as total_clicks, SUM(views) as total_views')
    ->first();
```

### Compare Performance

```php
// Compare network performance
$networks = ['adsense', 'media_net', 'ezoic'];
foreach ($networks as $network) {
    $ads = Advertisement::byNetwork($network)->get();
    $stats = $ads->sum('clicks') / ($ads->sum('views') ?: 1) * 100;
    echo "$network: {$stats}% CTR";
}
```

---

## 🎓 Next Steps

1. **Test Multiple Networks**
   - Start with AdSense (safest)
   - Add Media.net after 1 month
   - Test other networks based on traffic

2. **Monitor Performance**
   - Track earnings by network
   - Monitor CTR (click-through rate)
   - Adjust placements based on data

3. **Optimize Revenue**
   - Use header bidding (Ezoic, Monumetric)
   - Mix text/display/native ads
   - A/B test placements

4. **Compliance**
   - Regular policy audits  
   - Keep content quality high
   - Respond to violations promptly
   - Build long-term relationships

---

## 📋 Checklist for Setup

- [ ] Create ad in admin panel
- [ ] Select network type
- [ ] Fill network-specific fields
- [ ] Configure placement and scheduling
- [ ] Test on staging/development
- [ ] Deploy to production
- [ ] Monitor performance
- [ ] Optimize based on metrics

---

**Last Updated**: February 19, 2026  
**Version**: 1.0  
**Status**: ✅ Production Ready

For additional information, see [ADSENSE_INTEGRATION_GUIDE.md](./ADSENSE_INTEGRATION_GUIDE.md) and [ADVERTISEMENTS_COMPLETE_GUIDE.md](./ADVERTISEMENTS_COMPLETE_GUIDE.md).
