# 🔍 JSON-LD Schema Implementation for Sajeb News

## সম্পূর্ণ কাঠামো

আমরা ১২টি ভিন্ন JSON-LD Schema type implement করেছি যা SEO এবং search engine optimization এর জন্য প্রয়োজনীয়।

---

## ✅ বাস্তবায়িত Schema প্রকার

### 1️⃣ **NewsArticle Schema** (সর্বোচ্চ অগ্রাধিকার)
- **ব্যবহার**: প্রতিটি নিউজ পোস্টে
- **কাজ**: Google News, Top Stories তে দৃশ্যমান করা
- **Properties**:
  - `headline` - নিউজ শিরোনাম
  - `image` - ফিচার্ড ইমেজ (1200x630px)
  - `datePublished` - প্রকাশনার তারিখ
  - `dateModified` - আপডেট তারিখ
  - `author` - লেখক তথ্য
  - `publisher` - প্রকাশনা সংস্থা
  - `description` - নিউজ বর্ণনা
  - `articleBody` - সম্পূর্ণ কন্টেন্ট
  - `keywords` - ট্যাগ ভিত্তিক কীওয়ার্ড

**লোকেশন**: `/news/{slug}` পেজে

```json
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "headline": "নিউজ শিরোনাম",
  "image": "https://example.com/image.jpg",
  "datePublished": "2026-02-14T10:00:00Z",
  "author": { "@type": "Person", "name": "লেখকের নাম" },
  "publisher": { "@type": "Organization", "name": "সাজেব নিউজ" }
}
```

---

### 2️⃣ **Organization Schema**
- **ব্যবহার**: সব পেজে (global)
- **কাজ**: প্রকাশনা সংস্থার তথ্য দেখানো
- **Properties**:
  - `name` - সংস্থার নাম
  - `logo` - লোগো URL
  - `url` - ওয়েবসাইট URL
  - `sameAs` - সোশ্যাল মিডিয়া লিঙ্ক
  - `contactPoint` - যোগাযোগের তথ্য

**নিয়ন্ত্রণ**: Settings → JSON-LD Schema → "Enable Organization Schema"

---

### 3️⃣ **WebSite Schema**
- **ব্যবহার**: সব পেজে (global)
- **কাজ**: Search box integration (Sitelinks Search Box)
- **Properties**:
  - `name` - ওয়েবসাইট নাম
  - `url` - ওয়েবসাইট URL
  - `potentialAction` - সার্চ অ্যাকশন

**লাভ**: Google search result এ সার্চ বক্স দেখায়

---

### 4️⃣ **BreadcrumbList Schema**
- **ব্যবহার**: সব পেজে (homepage, category, news)
- **কাজ**: Google search result এ breadcrumb নেভিগেশন দেখা
- **Structure**:
  ```
  Home > Category > Article
  ```

**উদাহরণ** News পেজে:
```
হোম > বিনোদন > "নিউজ শিরোনাম"
```

---

### 5️⃣ **Person Schema** (লেখক)
- **ব্যবহার**: নিউজ পেজে যখন লেখক আছে
- **কাজ**: লেখক তথ্য এবং credibility বৃদ্ধি
- **Properties**:
  - `name` - লেখকের নাম
  - `url` - লেখকের প্রোফাইল পেজ
  - `image` - লেখকের ছবি (avatar)
  - `jobTitle` - পদবি (Admin, Editor, Author etc.)
  - `bio` - সংক্ষিপ্ত পরিচয়

**নিয়ন্ত্রণ**: Settings → JSON-LD Schema → "Enable Person Schema"

---

### 6️⃣ **ImageObject Schema**
- **ব্যবহার**: নিউজ ইমেজের জন্য
- **কাজ**: Google Discover এ সঠিক ইমেজ দেখা
- **Properties**:
  - `url` - ইমেজ URL
  - `width` - প্রস্থ (১২০০px)
  - `height` - উচ্চতা (৬৩০px)

**নিয়ন্ত্রণ**: Settings → JSON-LD Schema → "Enable ImageObject Schema"

---

### 7️⃣ **VideoObject Schema**
- **ব্যবহার**: ভিডিও নিউজ থাকলে
- **কাজ**: Google Video section এ দেখা
- **Properties**:
  - `name` - ভিডিও নাম
  - `description` - বর্ণনা
  - `thumbnailUrl` - থাম্বনেইল
  - `uploadDate` - আপলোড তারিখ
  - `contentUrl` - ভিডিও URL

**নিয়ন্ত্রণ**: Settings → JSON-LD Schema → "Enable VideoObject Schema" (ডিফল্ট: অক্ষম)

---

### 8️⃣ **LiveBlogPosting Schema**
- **ব্যবহার**: লাইভ আপডেট থাকলে (ব্রেকিং নিউজ, ক্রিকেট, নির্বাচন)
- **কাজ**: লাইভ কভারেজ Google এ দেখায়
- **Properties**:
  - `headline` - শিরোনাম
  - `liveBlogUpdate[]` - আপডেট তালিকা
  - প্রতিটি update এ: `headline`, `datePublished`, `articleBody`

**নিয়ন্ত্রণ**: Settings → JSON-LD Schema → "Enable LiveBlogPosting Schema" (ডিফল্ট: অক্ষম)

---

### 9️⃣ **FAQPage Schema**
- **ব্যবহার**: FAQ বা বিশ্লেষণমূলক নিউজ
- **কাজ**: Google search result এ FAQ সেকশন দেখায়
- **Structure**:
  ```
  Q1: প্রশ্ন ১
  A1: উত্তর ১
  Q2: প্রশ্ন ২
  A2: উত্তর ২
  ```

**নিয়ন্ত্রণ**: Settings → JSON-LD Schema → "Enable FAQPage Schema" (ডিফল্ট: অক্ষম)

---

### 🔟 **JobPosting Schema**
- **ব্যবহার**: চাকরির বিজ্ঞাপন
- **কাজ**: Google Jobs এ দেখা
- **Properties**:
  - `title` - চাকরির শিরোনাম
  - `description` - বর্ণনা
  - `hiringOrganization` - নিয়োগকর্তা
  - `jobLocation` - স্থান
  - `baseSalary` - বেতন
  - `datePosted` - পোস্ট তারিখ
  - `validThrough` - ভ্যালিড পর্যন্ত

**নিয়ন্ত্রণ**: Settings → JSON-LD Schema → "Enable JobPosting Schema" (ডিফল্ট: অক্ষম)

---

### 1️⃣1️⃣ **Event Schema**
- **ব্যবহার**: ইভেন্ট কভারেজ
- **কাজ**: Google Events এ দেখা
- **Properties**:
  - `name` - ইভেন্টের নাম
  - `description` - বর্ণনা
  - `startDate` - শুরুর সময়
  - `endDate` - শেষের সময়
  - `organizer` - আয়োজক
  - `location` - স্থান

**নিয়ন্ত্রণ**: Settings → JSON-LD Schema → "Enable Event Schema" (ডিফল্ট: অক্ষম)

---

### 1️⃣2️⃣ **ClaimReview Schema**
- **ব্যবহার**: ফ্যাক্ট-চেকিং নিউজ
- **কাজ**: Google Fact Check Explorer এ দেখা
- **Properties**:
  - `claimReviewed` - দাবি
  - `reviewRating` - রেটিং (True/False)
  - `reviewDate` - পর্যালোচনা তারিখ
  - `author` - পর্যালোচক

**নিয়ন্ত্রণ**: Settings → JSON-LD Schema → "Enable ClaimReview Schema" (ডিফল্ট: অক্ষম)

---

## 🎛️ প্রশাসক নিয়ন্ত্রণ

সব Schema types admin panel থেকে নিয়ন্ত্রণ করা যায়:

### Settings Page → JSON-LD Schema ট্যাব

**Schema Enable/Disable**:
- ✅ NewsArticle Schema (enabled by default)
- ✅ Organization Schema (enabled by default)
- ✅ WebSite Schema (enabled by default)
- ✅ BreadcrumbList Schema (enabled by default)
- ✅ Person Schema (enabled by default)
- ✅ ImageObject Schema (enabled by default)
- ⚪ VideoObject Schema (disabled by default)
- ⚪ LiveBlogPosting Schema (disabled by default)
- ⚪ FAQPage Schema (disabled by default)
- ⚪ JobPosting Schema (disabled by default)
- ⚪ Event Schema (disabled by default)
- ⚪ ClaimReview Schema (disabled by default)

**Organization Information**:
- Organization Name (auto-use site name if empty)
- Contact Email
- Contact Phone
- Contact Type (e.g., Customer Service, News Inquiry)
- Organization Description

---

## 📝 কোড অবস্থান

### Service Class
- **File**: `/app/Services/SchemaGeneratorService.php`
- **Methods**:
  - `organizationSchema()` - Organization Schema generate
  - `websiteSchema()` - WebSite Schema generate
  - `newsArticleSchema($news)` - NewsArticle Schema generate
  - `breadcrumbSchema($breadcrumbs)` - Breadcrumb Schema generate
  - `personSchema($user)` - Person Schema generate
  - `imageObjectSchema()` - ImageObject Schema generate
  - `videoObjectSchema()` - VideoObject Schema generate
  - `liveBlogPostingSchema()` - LiveBlogPosting Schema generate
  - `faqPageSchema()` - FAQPage Schema generate
  - `jobPostingSchema()` - JobPosting Schema generate
  - `eventSchema()` - Event Schema generate
  - `claimReviewSchema()` - ClaimReview Schema generate

### Model
- **File**: `/app/Models/SchemaSetting.php`
- Database table: `schema_settings`
- Enable/disable settings সংরক্ষণ করে

### Database Migration
- **File**: `/database/migrations/2026_02_14_140000_create_schema_settings_table.php`

### Views
- **Layout**: `/resources/views/public/layout.blade.php` (Organization + WebSite schemas)
- **News Show**: `/resources/views/public/news/show.blade.php` (NewsArticle + Breadcrumb + Person schemas)
- **Homepage**: `/resources/views/public/index.blade.php` (Breadcrumb schema)
- **Category**: `/resources/views/public/category.blade.php` (Breadcrumb schema)
- **Admin Settings**: `/resources/views/admin/settings/index.blade.php` (Schema settings UI)

### Controller
- **File**: `/app/Http/Controllers/Admin/SettingController.php`
- `index()` - Load schema settings
- `update()` - Save schema settings

---

## 🧪 Testing

### Google Search Console Tools
1. **Rich Results Test**: https://search.google.com/test/rich-results
2. **Mobile-Friendly Test**: https://search.google.com/test/mobile-friendly
3. **Structured Data Testing Tool**: https://schema.org/

### Step-by-Step Test Process

#### 1. NewsArticle Schema Test
```
1. যেকোনো নিউজ পেজে যান: /news/{slug}
2. URL গুগল Rich Results Test এ paste করুন
3. Schema tab এ "NewsArticle" দেখা যাবে
4. সব properties সঠিক আছে কিনা দেখুন
```

#### 2. Organization Schema Test
```
1. Homepage এ যান: /
2. Page source (Ctrl+U) খুলুন
3. "Organization" খুঁজুন
4. name, logo, sameAs সঠিক আছে কিনা দেখুন
```

#### 3. BreadcrumbList Schema Test
```
1. Category page বা news page এ যান
2. Rich Results Test tool ব্যবহার করুন
3. "BreadcrumbList" দেখা যাবে
4. breadcrumb hierarchy সঠিক আছে কিনা দেখুন
```

---

## 🚀 SEO সুবিধা

### Search Visibility বৃদ্ধি
- ✅ Google News eligible
- ✅ Google Top Stories eligible
- ✅ Google Search result এ rich results
- ✅ Google Discover এ featured

### Snippets উন্নতি
- ✅ Rich snippets দেখায় (image, date, author)
- ✅ BreadcrumbList navigation দেখায়
- ✅ Author credibility increase করে

### CTR বৃদ্ধি
- ✅ Rich results আকর্ষণীয় দেখায়
- ✅ Extra information সরবরাহ করে
- ✅ User trust বৃদ্ধি করে

---

## 📊 Performance সুবিধা

### Database Impact: Minimal
- ✅ Schema generation runtime-based (cached)
- ✅ Database query minimal
- ✅ Performance overhead negligible

### Rendering Impact: None
- ✅ JSON-LD script tag (invisible to users)
- ✅ Page rendering speed অপরিবর্তিত
- ✅ Mobile performance unaffected

---

## 🔄 Future Enhancement

### Planned Features
1. Custom Schema per news category
2. Structured data validation dashboard
3. Schema analytics reporting
4. Multi-language schema support
5. Video news schema integration
6. Comment/Review schema support

---

## ⚠️ গুরুত্বপূর্ণ নোট

1. **বেশি important**: NewsArticle, Organization, WebSite, BreadcrumbList
2. **সাবধানে ব্যবহার করুন**: VideoObject, JobPosting (যখন সংশ্লিষ্ট কন্টেন্ট থাকে)
3. **ভবিষ্যতের জন্য**: FAQPage, ClaimReview, Event, LiveBlogPosting

---

## 📞 সাপোর্ট

Schema কোন সমস্যা হলে:
1. Google Search Console check করুন
2. Rich Results Test tool ব্যবহার করুন
3. Admin settings verify করুন
4. Schema enable আছে কিনা check করুন

---

**Created**: 14 Feb 2026
**Version**: 1.0
