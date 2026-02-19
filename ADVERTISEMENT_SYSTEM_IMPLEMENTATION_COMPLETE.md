# বিজ্ঞাপন ম্যানেজমেন্ট সিস্টেম - বাস্তবায়ন সম্পূর্ণ

**প্রকল্প**: Sajeb News বিজ্ঞাপন ম্যানেজমেন্ট সিস্টেম
**তারিখ**: ১৯ ফেব্রুয়ারি ২০২৬
**অবস্থা**: ✅ সম্পূর্ণ

---

## 📋 সারসংক্ষেপ

আপনার Sajeb News প্ল্যাটফর্মের জন্য একটি সম্পূর্ণ বিজ্ঞাপন ম্যানেজমেন্ট সিস্টেম তৈরি করা হয়েছে যা:

✅ Multiple ad placements সমর্থন করে
✅ Google Analytics UTM tracking সক্ষম করে
✅ Real-time performance analytics প্রদান করে
✅ Admin Dashboard থেকে সম্পূর্ণ নিয়ন্ত্রণ অফার করে
✅ Frontend-এ সহজ integration প্রদান করে

---

## 🎯 বাস্তবায়িত বৈশিষ্ট্যসমূহ

### 1. **বহুস্থানীয় বিজ্ঞাপন প্লেসমেন্ট** (8টি অবস্থান)
```
✅ within_news           - নিউজ আর্টিকেলের মধ্যে
✅ homepage_banner      - হোমপেজ ব্যানার
✅ homepage_popup       - হোমপেজ পপআপ
✅ homepage_header      - হোমপেজ হেডার
✅ homepage_footer      - হোমপেজ ফুটার
✅ category_page        - ক্যাটাগরি পেজ
✅ sidebar              - সাইডবার
✅ between_comments     - মন্তব্যের মধ্যে
```

### 2. **UTM Campaign Tracking**
- UTM Source (উৎস)
- UTM Medium (মাধ্যম)
- UTM Campaign (ক্যাম্পেইন নাম)
- UTM Term (কীওয়ার্ড)
- UTM Content (বিষয়বস্তু)
- Auto-generated complete URL with all UTM parameters

### 3. **Device Targeting**
- All Devices (সব ডিভাইস)
- Desktop Only (শুধু ডেস্কটপ)
- Mobile Only (শুধু মোবাইল)

### 4. **Performance Tracking**
- Views (দর্শন সংখ্যা)
- Clicks (ক্লিক সংখ্যা)
- CTR (ক্লিক-থ্রু রেট)
- Daily impressions/clicks limits

### 5. **Pricing Models**
- CPC (Cost Per Click) - প্রতি ক্লিক
- CPM (Cost Per 1000 Impressions) - প্রতি হাজার ভিউ
- Total spent tracking

---

## 📁 তৈরিকৃত ফাইল এবং কম্পোনেন্ট

### Database & Models
```
✅ app/Models/Advertisement.php (Enhanced model)
✅ database/migrations/2026_02_19_000000_enhance_advertisements_table.php
✅ database/migrations/2026_02_19_000001_add_missing_advertisement_columns.php
```

### Controllers
```
✅ app/Http/Controllers/Admin/AdController.php
   - index()          - সব বিজ্ঞাপন দেখান
   - create()         - নতুন বিজ্ঞাপন ফর্ম
   - store()          - বিজ্ঞাপন সংরক্ষণ
   - show()           - বিজ্ঞাপনের বিস্তারিত
   - edit()           - সম্পাদনা ফর্ম
   - update()         - বিজ্ঞাপন আপডেট
   - destroy()        - বিজ্ঞাপন মুছুন
   - toggleStatus()   - স্ট্যাটাস পরিবর্তন
   - statistics()     - পরিসংখ্যান দেখান
   - export()         - CSV তে রপ্তানি

✅ app/Http/Controllers/Public/AdController.php (API)
   - getByPlacement() - বিজ্ঞাপন পান placement অনুসারে
   - recordClick()    - ক্লিক রেকর্ড করুন
   - recordImpression() - ইম্প্রেশন রেকর্ড করুন
   - getRandomForPlacement() - র‍্যান্ডম বিজ্ঞাপন পান
```

### Views (Blade Templates)
```
✅ resources/views/admin/advertisements/index.blade.php
   - সব বিজ্ঞাপনের তালিকা
   - পরিসংখ্যান কার্ড
   - টেবিল ভিউ

✅ resources/views/admin/advertisements/create.blade.php
   - নতুন বিজ্ঞাপন তৈরির ফর্ম
   - UTM Builder সহ
   - ইমেজ প্রিভিউ
   - URL প্রিভিউ

✅ resources/views/admin/advertisements/edit.blade.php
   - বিজ্ঞাপন সম্পাদন করুন
   - পারফরম্যান্স স্ট্যাটিস্টিক

✅ resources/views/admin/advertisements/show.blade.php
   - বিজ্ঞাপনের বিস্তারিত তথ্য
   - পরিসংখ্যান ড্যাশবোর্ড
```

### Components
```
✅ resources/views/components/advertisement.blade.php
   - Reusable ad display component
   - Support for all placements
   - Responsive design
```

### Services
```
✅ app/Services/AdService.php
   - getAdsByPlacement()      - Placement অনুসারে বিজ্ঞাপন পান
   - getRandomAdForPlacement() - র‍্যান্ডম বিজ্ঞাপন
   - recordView()              - ভিউ রেকর্ড করুন
   - recordClick()             - ক্লিক রেকর্ড করুন
   - getStatistics()           - পরিসংখ্যান পান
   - hasReachedDailyLimit()   - দৈনিক সীমা চেক করুন
   - calculateCost()          - খরচ গণনা করুন
   - getDashboardStats()      - ড্যাশবোর্ড স্ট্যাটিস্টিক
```

### Routes
```
✅ routes/web.php (Admin routes)
   POST   /admin/advertisements           - তালিকা
   GET    /admin/advertisements/create    - ফর্ম
   POST   /admin/advertisements           - সংরক্ষণ
   GET    /admin/advertisements/{id}      - বিস্তারিত
   GET    /admin/advertisements/{id}/edit - সম্পাদনা ফর্ম
   PUT    /admin/advertisements/{id}      - আপডেট
   DELETE /admin/advertisements/{id}      - মুছুন
   POST   /admin/advertisements/{id}/toggle-status - স্ট্যাটাস চেঞ্জ
   GET    /admin/advertisements/export/csv - মপ্রার্ট

✅ routes/api.php (Public API)
   GET    /api/ads/placement/{placement}           - Placement অনুযায়ী
   GET    /api/ads/random/{placement}              - র‍্যান্ডম
   POST   /api/ads/{id}/click                      - ক্লিক রেকর্ড
   POST   /api/ads/{id}/impression                 - ইম্প্রেশন রেকর্ড
   GET    /api/ads/{id}/statistics                 - পরিসংখ্যান
   GET    /api/ads/trending                        - জনপ্রিয় বিজ্ঞাপন
```

---

## 🚀 ব্যবহার গাইড

### Admin Panel এ (বিজ্ঞাপন তৈরি/সম্পাদনা)

#### Step 1: যান Advertisements সেকশনে
1. Admin Dashboard খুলুন
2. সাইডবার থেকে **Advertisements** ক্লিক করুন

#### Step 2: নতুন বিজ্ঞাপন তৈরি করুন
1. **Create New Ad** বাটন ক্লিক করুন
2. নিম্নলিখিত ভরুন:

```
📌 Basic Information:
   - Name: "Coca Cola Summer Offer"
   - Placement: "Homepage Banner" 
   - Type: "Banner"
   - Device Target: "All Devices"

📌 Image & Link:
   - Image URL: https://yoursite.com/ads/coca-cola.jpg
   - Ad URL: https://cocacola.com.bd/offers

📌 UTM Parameters (গুরুত্বপূর্ণ):
   - Source: facebook
   - Medium: cpc
   - Campaign: summer_sale_2026
   - Term: soft_drinks
   - Content: homepage_banner

📌 Schedule:
   - Start Date: [আজকের তারিখ]
   - End Date: [শেষের তারিখ]
```

**ফলাফল**: সম্পূর্ণ URL হবে:
```
https://cocacola.com.bd/offers?utm_source=facebook&utm_medium=cpc&utm_campaign=summer_sale_2026&utm_term=soft_drinks&utm_content=homepage_banner
```

#### Step 3: বিজ্ঞাপন পরিচালনা করুন
- **View**: বিস্তারিত দেখতে এনক্লিক করুন
- **Edit**: সম্পাদনা করতে ক্লিক করুন
- **Delete**: মুছে ফেলতে ক্লিক করুন
- প্রতিটি বিজ্ঞাপনে রিয়েল-টাইম পরিসংখ্যান দেখুন

---

### Frontend এ (বিজ্ঞাপন প্রদর্শন)

#### Option 1: Blade Component ব্যবহার করুন (সবচেয়ে সহজ)

**Homepage এ Banner যোগ করুন:**
```blade
<x-advertisement placement="homepage_banner" device="desktop" limit="1" />
```

**Sidebar এ Ads যোগ করুন:**
```blade
<div class="sidebar">
    <x-advertisement placement="sidebar" device="desktop" limit="3" />
</div>
```

**নিউজ আর্টিকেলে Ads যোগ করুন:**
```blade
<article>
    <h1>{{ $news->title }}</h1>
    
    <!-- কন্টেন্টের পরে বিজ্ঞাপন -->
    <x-advertisement placement="within_news" device="desktop" limit="1" />
    
    <p>{{ $news->content }}</p>
</article>
```

**Category পেজে Ads যোগ করুন:**
```blade
@section('content')
    <x-advertisement placement="category_page" device="desktop" limit="2" />
    
    <!-- Category content -->
@endsection
```

#### Option 2: API ব্যবহার করুন (JavaScript এ)

**Placement অনুযায়ী Ads পান:**
```javascript
fetch('/api/ads/placement/homepage_banner?device=desktop&limit=3')
    .then(res => res.json())
    .then(data => {
        data.ads.forEach(ad => {
            // প্রতিটি বিজ্ঞাপন প্রদর্শন করুন
            console.log(ad.name, ad.ad_url);
        });
    });
```

**ক্লিক ট্র্যাকিং:**
```javascript
function clickAd(adId) {
    fetch(`/api/ads/${adId}/click`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ placement: 'homepage_banner' })
    });
}
```

---

## 📊 Database Schema

### Advertisements টেবিল কলামসমূহ

```sql
CREATE TABLE advertisements (
    id                          INTEGER PRIMARY KEY,
    name                        VARCHAR,
    slug                        VARCHAR UNIQUE,
    placement                   VARCHAR,  -- within_news, homepage_banner, etc.
    type                        VARCHAR,  -- banner, sidebar, inline, featured, etc.
    image_url                   VARCHAR,  -- বিজ্ঞাপন ছবির URL
    ad_url                      VARCHAR,  -- গন্তব্য URL
    alt_text                    VARCHAR,  -- অ্যাক্সেসিবিলিটি
    
    -- UTM Parameters
    utm_source                  VARCHAR,  -- facebook, google, etc.
    utm_medium                  VARCHAR,  -- cpc, banner, email
    utm_campaign                VARCHAR,  -- campaign name
    utm_term                    VARCHAR,  -- keywords
    utm_content                 VARCHAR,  -- ad id
    
    -- Performance
    views                       INTEGER,  -- মোট দর্শন সংখ্যা
    clicks                      INTEGER,  -- মোট ক্লিক সংখ্যা
    
    -- Targeting
    target_categories           JSON,     -- target category ids
    target_tags                 JSON,     -- target tag ids
    display_order               INTEGER,  -- প্রদর্শন সংখ্যা
    show_on_mobile              BOOLEAN,  -- মোবাইলে দেখান?
    show_on_desktop             BOOLEAN,  -- ডেস্কটপে দেখান?
    
    -- Limits
    daily_impression_limit      INTEGER,  -- দৈনিক দর্শন সীমা
    max_clicks_per_day          INTEGER,  -- দৈনিক ক্লিক সীমা
    
    -- Pricing
    cpc_amount                  DECIMAL,  -- cost per click
    cpm_amount                  DECIMAL,  -- cost per 1000 impressions
    total_spent                 DECIMAL,  -- মোট খরচ
    
    -- Advertiser Info
    advertiser_name             VARCHAR,
    advertiser_email            VARCHAR,
    advertiser_phone            VARCHAR,
    
    -- Other
    device_target               VARCHAR,  -- all, mobile, desktop
    start_date                  DATETIME,
    end_date                    DATETIME,
    is_active                   BOOLEAN,
    notes                        TEXT,
    code                        TEXT,     -- কাস্টম কোড
    created_by                  INTEGER,  -- user id
    created_at                  DATETIME,
    updated_at                  DATETIME
);
```

---

## 🔧 API Endpoints

### Get Ads by Placement
```
GET /api/ads/placement/{placement}?device=desktop&limit=3

Response:
{
    "success": true,
    "placement": "homepage_banner",
    "count": 3,
    "ads": [
        {
            "id": 1,
            "name": "Coca Cola Banner",
            "image_url": "...",
            "ad_url": "...",
            "alt_text": "..."
        }
    ]
}
```

### Record Click
```
POST /api/ads/{id}/click
Body: { "placement": "homepage_banner" }

Response:
{
    "success": true,
    "message": "Click recorded",
    "ad_id": 1
}
```

### Record Impression
```
POST /api/ads/{id}/impression

Response:
{
    "success": true,
    "views": 150
}
```

### Get Statistics
```
GET /api/ads/{id}/statistics

Response:
{
    "success": true,
    "statistics": {
        "id": 1,
        "name": "Coca Cola Banner",
        "views": 150,
        "clicks": 5,
        "ctr": 3.33
    }
}
```

---

## 💡 UTM Parameter Examples

### Facebook Ad Campaign
```
utm_source=facebook
utm_medium=cpc
utm_campaign=summer_sale_2026
utm_term=beverages
utm_content=homepage_banner

Final URL:
https://cocacola.com.bd/offers?utm_source=facebook&utm_medium=cpc&utm_campaign=summer_sale_2026&utm_term=beverages&utm_content=homepage_banner
```

### Google Ads Campaign
```
utm_source=google
utm_medium=cpc
utm_campaign=winter_sale_2026
utm_term=soft_drinks
utm_content=header_banner
```

### Email Newsletter
```
utm_source=newsletter
utm_medium=email
utm_campaign=january_offers
utm_term=subscribers
utm_content=email_banner
```

---

## 📈 Google Analytics Integration

Google Analytics-এ আপনার ক্যাম্পেইনগুলি দেখুন:

1. **Google Analytics খুলুন**
2. **Acquisition → Campaigns → All Campaigns**
3. আপনার UTM campaign গুলি দেখুন

প্রতিটি ক্যাম্পেইনের জন্য দেখুন:
- ট্রাফিক উৎস
- ব্যবহারকারী সংখ্যা
- রূপান্তর হার
- প্রতিটি ক্যাম্পেইনের ROI

---

## ✅ Checklist - বাস্তবায়ন সম্পূর্ণ

### Database
- [x] Advertisement model enhanced
- [x] Database migrations applied
- [x] All columns added successfully

### Admin Panel
- [x] AdController created (CRUD operations)
- [x] Index view (list all ads)
- [x] Create view (with UTM builder)
- [x] Edit view (with statistics)
- [x] Show view (detailed view)
- [x] Menu item added to sidebar

### Frontend Components
- [x] Blade Component created
- [x] Multiple placement support
- [x] Responsive design
- [x] Click tracking integration

### API
- [x] Public API routes created
- [x] Click recording endpoint
- [x] Impression recording endpoint
- [x] Statistics endpoint
- [x] Get ads by placement

### Services
- [x] AdService created
- [x] Performance tracking methods
- [x] Cost calculation
- [x] Daily limit checking

### Documentation
- [x] Complete usage guide in Bengali
- [x] UTM parameter guide
- [x] Frontend integration examples
- [x] API endpoint documentation

---

## 🎓 টিউটোরিয়াল ডকুমেন্টেশন

সম্পূর্ণ বাংলা গাইড দেখুন: `ADVERTISEMENTS_COMPLETE_GUIDE.md`

---

## 🔐 নিরাপত্তা বৈশিষ্ট্য

- ✅ CSRF Protection সব ফর্মে
- ✅ User authentication required
- ✅ Activity logging সব পরিবর্তনের জন্য
- ✅ Input validation সব ইনপুটে
- ✅ SQL injection protection (prepared statements)

---

## 🚦 পরবর্তী পদক্ষেপ

1. **Dashboard এ যান**: `/admin` 
2. **Advertisements মেনু খুলুন**
3. **Create New Ad বাটন ক্লিক করুন**
4. **প্রথম বিজ্ঞাপন তৈরি করুন**
5. **Frontend-এ যোগ করুন**: `<x-advertisement />`
6. **Google Analytics-এ ট্র্যাক করুন**

---

## 📞 সহায়তা

কোনো সমস্যা বা প্রশ্ন থাকলে, দেখুন:
- `ADVERTISEMENTS_COMPLETE_GUIDE.md` - সম্পূর্ণ গাইড
- Admin Panel - সমস্ত বিজ্ঞাপন পরিচালনা করুন
- Screenshots - প্রতিটি ফর্মের উদাহরণ

---

**সিস্টেম সম্পূর্ণ এবং ব্যবহারের জন্য প্রস্তুত!** ✨

**প্রকাশনা তারিখ**: ১৯ ফেব্রুয়ারি ২০২৬
**সংস্করণ**: 1.0
