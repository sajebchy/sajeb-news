# Bootstrap 5 ইমপ্লিমেন্টেশন গাইড

## সামগ্রিক দৃষ্টিভঙ্গি

এই প্রজেক্টটি সম্পূর্ণভাবে **Bootstrap 5.3.3** দিয়ে আপডেট করা হয়েছে। Tailwind CSS পরিবর্তে এখন আমরা শক্তিশালী Bootstrap ফ্রেমওয়ার্ক ব্যবহার করছি।

## পরিবর্তনগুলি

### 1. **প্যাকেজ ডিপেন্ডেন্সি**
```json
// পুরাতন (Tailwind)
"@tailwindcss/forms": "^0.5.2",
"@tailwindcss/vite": "^4.0.0",
"postcss": "^8.4.31",
"tailwindcss": "^3.1.0",

// নতুন (Bootstrap)
"bootstrap": "^5.3.3",
"bootstrap-icons": "^1.11.3"
```

### 2. **CSS ফাইল স্ট্রাকচার**

#### `resources/css/app.css`
- Bootstrap SCSS কম্পোনেন্ট সব ইমপোর্ট করা হয়েছে
- Bootstrap Icons (bi class) ব্যবহার করা হয়েছে
- কাস্টম স্টাইলস যোগ করা হয়েছে

#### `resources/css/bootstrap-custom.css` (নতুন)
- Bootstrap কম্পোনেন্টগুলির কাস্টমাইজেশন
- রঙ স্কিম সেটআপ
- কাস্টম ইউটিলিটি ক্লাস

### 3. **লেআউট আপডেট**

#### `layouts/app.blade.php`
- Bootstrap CDN থেকে CSS এবং JS লোড করা হয়
- Tailwind কিউটিলিটি ক্লাস পরিবর্তে Bootstrap গ্রিড সিস্টেম

#### `layouts/guest.blade.php`
- Bootstrap কার্ড এবং গ্রিড ব্যবহার করে

#### `layouts/navigation.blade.php`
- Bootstrap Navbar কম্পোনেন্ট
- Dropdown মেনু
- মোবাইল রেসপন্সিভ টগলার

### 4. **আইকন পরিবর্তন**
```html
<!-- পুরাতন (Font Awesome) -->
<i class="fas fa-file-alt"></i>

<!-- নতুন (Bootstrap Icons) -->
<i class="bi bi-file-text"></i>
```

## Bootstrap ক্লাস রেফারেন্স

### গ্রিড সিস্টেম
```html
<div class="container-lg">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            <!-- Content -->
        </div>
    </div>
</div>
```

### বাটন
```html
<button class="btn btn-primary">Primary</button>
<button class="btn btn-secondary">Secondary</button>
<button class="btn btn-success">Success</button>
<button class="btn btn-danger">Danger</button>
<button class="btn btn-warning">Warning</button>
<button class="btn btn-info">Info</button>
```

### কার্ড
```html
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Card Title</h5>
    </div>
    <div class="card-body">
        <!-- Content -->
    </div>
    <div class="card-footer">
        <!-- Footer -->
    </div>
</div>
```

### অ্যালার্ট
```html
<div class="alert alert-success" role="alert">
    Success message
</div>
<div class="alert alert-danger" role="alert">
    Danger message
</div>
```

### ফর্ম
```html
<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" required>
</div>

<div class="mb-3">
    <label for="select" class="form-label">Select</label>
    <select class="form-select" id="select">
        <option>Option 1</option>
        <option>Option 2</option>
    </select>
</div>

<div class="form-check">
    <input class="form-check-input" type="checkbox" id="check">
    <label class="form-check-label" for="check">Check me out</label>
</div>
```

### টেবিল
```html
<table class="table table-hover">
    <thead class="table-light">
        <tr>
            <th>Header</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data</td>
        </tr>
    </tbody>
</table>
```

### ব্যাজ
```html
<span class="badge bg-primary">Primary</span>
<span class="badge bg-success">Success</span>
<span class="badge bg-danger">Danger</span>
```

## টাইপোগ্রাফি

```html
<h1>Heading 1</h1>
<h2>Heading 2</h2>
<h3>Heading 3</h3>
<h4>Heading 4</h4>
<h5>Heading 5</h5>
<h6>Heading 6</h6>

<p class="lead">Lead paragraph</p>
<p class="text-muted">Muted text</p>
<p class="text-danger">Danger text</p>
```

## স্পেসিং ইউটিলিটি

```html
<!-- মার্জিন -->
<div class="m-3">Margin 3</div>
<div class="mt-2">Margin Top</div>
<div class="mb-4">Margin Bottom</div>
<div class="mx-auto">Margin X Auto (Center)</div>

<!-- প্যাডিং -->
<div class="p-3">Padding 3</div>
<div class="px-4">Padding X</div>
<div class="py-2">Padding Y</div>
```

## ডিসপ্লে ইউটিলিটি

```html
<div class="d-flex justify-content-center">Flex Center</div>
<div class="d-none d-md-block">Hidden on mobile</div>
<div class="d-block d-lg-none">Show only on mobile</div>
<div class="text-center">Center text</div>
```

## কালার ক্লাস

```html
<p class="text-primary">Primary text</p>
<p class="text-success">Success text</p>
<p class="text-danger">Danger text</p>
<p class="text-warning">Warning text</p>
<p class="text-info">Info text</p>

<div class="bg-light">Light background</div>
<div class="bg-dark text-white">Dark background</div>
```

## Bootstrap Icons

Bootstrap Icons ব্যবহার করতে সাধারণ `bi` ক্লাস এবং আইকন নাম ব্যবহার করুন:

```html
<i class="bi bi-house"></i>          <!-- Home -->
<i class="bi bi-person"></i>         <!-- Person -->
<i class="bi bi-plus"></i>           <!-- Plus -->
<i class="bi bi-pencil"></i>         <!-- Edit -->
<i class="bi bi-trash"></i>          <!-- Delete -->
<i class="bi bi-eye"></i>            <!-- View -->
<i class="bi bi-envelope"></i>       <!-- Email -->
<i class="bi bi-gear"></i>           <!-- Settings -->
<i class="bi bi-list-check"></i>     <!-- List -->
<i class="bi bi-graph-up"></i>       <!-- Chart -->
<i class="bi bi-newspaper"></i>      <!-- News -->
<i class="bi bi-people"></i>         <!-- Users -->
<i class="bi bi-clock-history"></i>  <!-- History -->
```

## JavaScript ফিচার

Bootstrap ডেফল্টভাবে প্রধান JS কম্পোনেন্ট সাপোর্ট করে:

### Navbar Toggle
```html
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
        data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
</button>
```

### ড্রপডাউন
```html
<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" 
            data-bs-toggle="dropdown">
        Dropdown
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">Action</a></li>
    </ul>
</div>
```

### মডাল
```html
<button type="button" class="btn btn-primary" data-bs-toggle="modal" 
        data-bs-target="#exampleModal">
    Open Modal
</button>

<div class="modal fade" id="exampleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
            </div>
            <div class="modal-body">
                Modal body content
            </div>
        </div>
    </div>
</div>
```

## রেসপন্সিভ ব্রেকপয়েন্ট

```
xs: < 576px (default)
sm: >= 576px
md: >= 768px
lg: >= 992px
xl: >= 1200px
xxl: >= 1400px
```

## কাস্টম কনফিগারেশন

### রঙ অ্যাডজাস্টমেন্ট
`resources/css/bootstrap-custom.css` এ `:root` সেকশনে:

```css
:root {
    --bs-primary: #0d6efd;
    --bs-success: #198754;
    --bs-danger: #dc3545;
    /* etc */
}
```

## ডেভেলপমেন্ট

### প্যাকেজ ইনস্টল
```bash
npm install
```

### ডেভেলপমেন্ট সার্ভার
```bash
npm run dev
```

### বিল্ড প্রোডাকশন
```bash
npm run build
```

## Blade কম্পোনেন্ট আপডেট

Blade কম্পোনেন্টগুলি Bootstrap ক্লাস ব্যবহার করার জন্য আপডেট করা প্রয়োজন:

- `x-input-label` → Bootstrap `form-label` সাথে
- `x-text-input` → Bootstrap `form-control` সাথে
- `x-primary-button` → Bootstrap `btn btn-primary` সাথে
- `x-input-error` → Bootstrap `text-danger small` সাথে

## সাধারণ সমস্যা সমাধান

### Bootstrap JS কাজ করছে না
নিশ্চিত করুন যে `bootstrap.bundle.min.js` লোড হয়েছে এবং এটি DOM এর শেষে রয়েছে।

### স্টাইলিং ঠিক নয়
- CSS ক্যাশ ক্লিয়ার করুন (`npm run build`)
- ব্রাউজার ক্যাশ ক্লিয়ার করুন (Ctrl+Shift+Delete)

### গ্রিড সিস্টেম ভাঙছে
সঠিক কলাম ক্লাস ব্যবহার করুন: `col-12 col-md-6 col-lg-4`

## উপকারী রিসোর্স

- [Bootstrap 5 ডকুমেন্টেশন](https://getbootstrap.com/docs/5.3/)
- [Bootstrap Icons](https://icons.getbootstrap.com/)
- [Bootstrap Examples](https://getbootstrap.com/docs/5.3/examples/)

---

**আপডেট তারিখ:** February 10, 2026
**Bootstrap সংস্করণ:** 5.3.3
