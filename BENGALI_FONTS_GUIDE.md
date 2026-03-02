# বাংলা ফন্ট সেটআপ গাইড 🇧🇩

এই গাইডটি আপনার Sajeb NEWS ওয়েবসাইটে বাংলা ফন্ট ব্যবহার করার পদ্ধতি ব্যাখ্যা করে।

---

## ইনস্টল করা ফন্ট

আমরা Google Fonts থেকে দুটি সেরা Bengali ফন্ট যোগ করেছি:

### 1. **Noto Serif Bengali** 
- **ব্যবহার**: শিরোনাম এবং বডি টেক্সট
- **বৈশিষ্ট্য**: Traditional, Elegant, Readable
- **Weight**: 400, 500, 600, 700
- **ব্যবহারের ক্ষেত্র**: 
  - নিউজ শিরোনাম
  - আর্টিকেল কন্টেন্ট
  - মূল লেখার জন্য আদর্শ

### 2. **Noto Sans Bengali**
- **ব্যবহার**: মেনু, বাটন, ছোট টেক্সট
- **বৈশিষ্ট্য**: Clean, Modern, Professional
- **Weight**: 400, 500, 600, 700
- **ব্যবহারের ক্ষেত্র**:
  - নেভিগেশন মেনু
  - বাটন এবং লেবেল
  - মেটা তথ্য

---

## ফন্ট ব্যবহারের নিয়ম

### অটোমেটিক (Recommended)

ফন্ট গুলি সব টেক্সটে অটোমেটিক প্রয়োগ করা হয়েছে:

```html
<!-- সব জায়গায় কাজ করে -->
<h1>আপনার শিরোনাম</h1>
<p>আপনার প্যারাগ্রাফ</p>
<button>বাটন</button>
```

### ম্যানুয়াল ক্লাস ব্যবহার

নির্দিষ্ট ফন্ট নির্বাচন করতে এই ক্লাস গুলি ব্যবহার করুন:

```html
<!-- Serif (শিরোনাম জন্য) -->
<h1 class="news-title">বাংলা নিউজ শিরোনাম</h1>

<!-- Content (কন্টেন্ট জন্য) -->
<div class="news-content">
    <p>বাংলা আর্টিকেল কন্টেন্ট এখানে লিখুন।</p>
</div>

<!-- Navigation (মেনু জন্য) -->
<nav class="navbar">
    <a href="#" class="menu">হোম</a>
</nav>
```

### Available CSS Classes

```css
/* Headings - শিরোনাম */
.news-title          /* নিউজ শিরোনাম */
.article-title       /* আর্টিকেল শিরোনাম */
.post-title          /* পোস্ট শিরোনাম */

/* Content - কন্টেন্ট */
.news-content        /* নিউজ কন্টেন্ট */
.article-content     /* আর্টিকেল কন্টেন্ট */
.post-content        /* পোস্ট কন্টেন্ট */

/* Navigation - নেভিগেশন */
.menu                /* মেনু আইটেম */
.navbar              /* নেভিগেশন বার */

/* Comments - মন্তব্য */
.comment             /* কমেন্ট টেক্সট */
.reply               /* রিপ্লাই টেক্সট */
```

---

## CSS Font Stacks

আপনার নিজস্ব CSS এ এই ফন্ট স্ট্যাক ব্যবহার করুন:

```css
/* শুধু Bengali Serif */
font-family: 'Noto Serif Bengali', serif;

/* শুধু Bengali Sans */
font-family: 'Noto Sans Bengali', sans-serif;

/* Mixed (Bengali + English) */
font-family: 'Noto Serif Bengali', 'Figtree', serif;
font-family: 'Noto Sans Bengali', 'Figtree', sans-serif;

/* CSS Variables (প্রি-ডিফাইনড) */
font-family: var(--bengali-serif);    /* Serif */
font-family: var(--bengali-sans);     /* Sans */
```

---

## Blade Template এ বাংলা ফন্ট

### উদাহরণ ১: নিউজ পোস্ট

```blade
<article class="news-item">
    <h1 class="news-title">{{ $news->title }}</h1>
    
    <div class="meta">
        <span class="author">লেখক: {{ $news->author }}</span>
        <span class="date">{{ $news->published_at->format('d M Y') }}</span>
    </div>
    
    <div class="news-content">
        {!! $news->content !!}
    </div>
</article>
```

### উদাহরণ ২: কমেন্ট সেকশন

```blade
<div class="comments-section">
    <h3 class="section-title">মন্তব্য</h3>
    
    @foreach($comments as $comment)
        <div class="comment">
            <strong>{{ $comment->author }}</strong>
            <p class="comment-text">{{ $comment->text }}</p>
        </div>
    @endforeach
</div>
```

### উদাহরণ ৩: ক্যাটাগরি লিস্ট

```blade
<nav class="category-menu">
    @foreach($categories as $category)
        <a href="{{ route('category', $category->slug) }}" class="menu-item">
            {{ $category->name }}
        </a>
    @endforeach
</nav>
```

---

## Font Loading Performance

### ফন্ট Preload করা হয়েছে

আমরা নিম্নলিখিত অপটিমাইজেশন করেছি:

1. **DNS Prefetch** - দ্রুত সংযোগ
2. **Preconnect** - গুগল ফন্ট সার্ভারের সাথে প্রি-কানেক্ট
3. **Preload** - গুরুত্বপূর্ণ ফন্ট প্রি-লোড

```html
<!-- ফর্মেট এ আছে resources/views/public/layout.blade.php -->
<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;500;600;700&..." rel="stylesheet">
```

### ক্যশিং টিপস

- ব্রাউজার ক্যাশ করে Google Fonts এক বছরের জন্য
- পেজ লোডিং স্পিড খুব দ্রুত থাকে
- Mobile এ অপ্টিমাইজড

---

## বিভিন্ন Font Weight ব্যবহার

### Font Weights উপলব্ধ

- **400** - Regular (সাধারণ টেক্সট)
- **500** - Medium (কিছুটা গাঢ়)
- **600** - Semibold (শিরোনাম)
- **700** - Bold (গুরুত্বপূর্ণ পয়েন্ট)

### CSS এ ব্যবহার

```css
/* Regular Text */
p {
    font-family: 'Noto Serif Bengali', serif;
    font-weight: 400;
}

/* Headings */
h1, h2, h3 {
    font-family: 'Noto Serif Bengali', serif;
    font-weight: 700;
}

/* Strong/Bold */
strong, .bold {
    font-family: 'Noto Serif Bengali', serif;
    font-weight: 600;
}
```

---

## সমস্যা সমাধান

### সমস্যা: বাংলা টেক্সট ঠিকমত দেখা যাচ্ছে না

**সমাধান 1**: HTML এ `lang` attribute যোগ করুন
```html
<html lang="bn">
</html>
```

**সমাধান 2**: CSS এ line-height বাড়ান
```css
p {
    font-family: 'Noto Serif Bengali', serif;
    line-height: 1.8; /* বাংলা জন্য ভালো */
}
```

### সমস্যা: ফন্ট লোড হচ্ছে না

1. Google Fonts সাইট খুলুন এবং চেক করুন যে ফন্ট এখনও উপলব্ধ
2. Browser console এ error চেক করুন
3. Cache clear করুন: `Ctrl+Shift+Delete`

### সমস্যা: মোবাইলে দেখা যাচ্ছে না সঠিক

1. Font weight 400-700 ব্যবহার করুন (এর বেশি নয়)
2. Line-height 1.6 থেকে 1.8 এর মধ্যে রাখুন

---

## কাস্টম Font Strategy

### আপনার নিজস্ব সিদ্ধান্ত

আপনি চাইলে অন্য Bengali font ব্যবহার করতে পারেন Google Fonts থেকে:

- **Mukta Vaani** - Modern Sans Serif
- **Hindava** - Traditional style
- **Baloo 2** - Friendly, rounded

এগুলি যোগ করতে:

```html
<!-- resources/views/layouts/guest.blade.php এ -->
<link href="https://fonts.googleapis.com/css2?family=Mukta+Vaani:wght@400;500;600&display=swap" rel="stylesheet" />
```

তারপর CSS এ:

```css
:root {
    --bengali-custom: 'Mukta Vaani', sans-serif;
}

.custom-text {
    font-family: var(--bengali-custom);
}
```

---

## Build এবং Deploy

### পরিবর্তন সংরক্ষণ

```bash
# Changes commit করুন
git add .
git commit -m "feat: Add Bengali fonts from Google Fonts"
git push origin main
```

### Build করুন

```bash
# CSS compile করুন
npm run build

# Development এ টেস্ট করুন
npm run dev
```

---

## পারফরম্যান্স Metrics

ফন্ট সেটআপ পরে আপনার সাইটের:

- ✅ Page Load: দ্রুত
- ✅ Accessibility: ভালো
- ✅ Readability (বাংলা): উন্নত
- ✅ SEO: উপকৃত

---

## সাপোর্ট এবং রিসোর্স

### অফিসিয়াল লিঙ্ক

- [Google Fonts Bengali](https://fonts.google.com/?subset=bengali)
- [Noto Serif Bengali](https://fonts.google.com/noto/specimen/Noto+Serif+Bengali)
- [Noto Sans Bengali](https://fonts.google.com/noto/specimen/Noto+Sans+Bengali)

### আরও সাহায্য

- CSS Font এ সমস্যা? `resources/css/app.css` চেক করুন
- Template এ সমস্যা? `resources/views/` এর layout files দেখুন

---

**Last Updated**: March 2, 2026  
**Created For**: Sajeb NEWS - Bangladesh News Portal  
**Language**: Bengali + Implementation

