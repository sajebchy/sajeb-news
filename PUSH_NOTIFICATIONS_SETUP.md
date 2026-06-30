# পুশ নোটিফিকেশন সিস্টেম - সেটআপ এবং টেস্টিং গাইড

## সিস্টেম ওভারভিউ

এই গাইডটি আপনার পুশ নোটিফিকেশন সিস্টেম সম্পূর্ণভাবে কনফিগার এবং টেস্ট করার জন্য।

## প্রয়োজনীয় সেটআপ

### 1. VAPID কী জেনারেট করা

VAPID (Voluntary Application Server Identification) কী হল যা পুশ নোটিফিকেশন সাইন করার জন্য প্রয়োজন।

```bash
php artisan vapid:generate
```

এই কমান্ড দুটি কী তৈরি করবে:
- `VAPID_PUBLIC_KEY` - ব্রাউজারের সাবস্ক্রিপশনের জন্য
- `VAPID_PRIVATE_KEY` - পুশ নোটিফিকেশন স্বাক্ষর করার জন্য

### 2. .env ফাইলে কী যোগ করা

```env
VAPID_PUBLIC_KEY=your_public_key_here
VAPID_PRIVATE_KEY=your_private_key_here
```

### 3. অ্যাডমিন প্যানেলে সেটিংস আপডেট করা

`/admin/settings` এ যান এবং "পুশ নোটিফিকেশন" সেকশনে VAPID কী আপডেট করুন।

## সিস্টেম কম্পোনেন্টস

### ফ্রন্টএন্ড

#### 1. **push-notification-manager.js** (`/public/js/push-notification-manager.js`)
- ব্রাউজার নোটিফিকেশন API এর সাথে যোগাযোগ করে
- Service Worker রেজিস্টার করে
- সাবস্ক্রিপশন তথ্য সার্ভারে পাঠায়

**Key Methods:**
```javascript
// সাবস্ক্রাইব করা
await manager.subscribe()

// আনসাবস্ক্রাইব করা
await manager.unsubscribe()

// সাপোর্ট চেক করা
manager.isSupported()

// সক্রিয় সাবস্ক্রিপশন চেক করা
await manager.isEnabled()
```

#### 2. **push-notification-client.js** (`/public/js/push-notification-client.js`)
- UI ম্যানেজমেন্ট
- ব্টন স্টেট হ্যান্ডলিং
- ইউজার ফিডব্যাক (Toast নোটিফিকেশন)
- স্বয়ংক্রিয় সাবস্ক্রিপশন স্ট্যাটাস চেক

**Features:**
- স্বয়ংক্রিয় ইনিশিয়ালাইজেশন
- সাবস্ক্রিপশন স্ট্যাটাস চেক
- সুন্দর ইউআই ফিডব্যাক

#### 3. **Service Worker** (`/public/service-worker.js`)
- পুশ নোটিফিকেশন রিসিভ করা
- নোটিফিকেশন ডিসপ্লে করা
- নোটিফিকেশন ক্লিক হ্যান্ডল করা
- অফলাইন সাপোর্ট

### ব্যাকএন্ড

#### 1. **PushNotificationController** (`app/Http/Controllers/PushNotificationController.php`)
- `/api/push/subscribe` - সাবস্ক্রিপশন সংরক্ষণ করে
- `/api/push/unsubscribe` - সাবস্ক্রিপশন বাতিল করে
- `/api/push/check` - সাবস্ক্রিপশন স্ট্যাটাস চেক করে
- `/api/push/stats` - পরিসংখ্যান রিটার্ন করে

#### 2. **SendNewsNotification Job** (`app/Jobs/SendNewsNotification.php`)
- **VAPID JWT স্বাক্ষর করা** - সঠিক Web Push Protocol ব্যবহার করে
- সমস্ত সক্রিয় সাবস্ক্রাইবারদের কাছে নোটিফিকেশন পাঠায়
- নিষ্ক্রিয় সাবস্ক্রিপশন পরিচালনা করে (410/404 এরর)

**পুশ নোটিফিকেশন বিষয়বস্তু:**
```json
{
  "title": "নতুন নিউজ: নিউজ শিরোনাম",
  "body": "প্রথম ১০০ অক্ষর বিষয়বস্তু...",
  "icon": "featured_image_url",
  "badge": "badge_url",
  "tag": "news-{id}",
  "data": {
    "url": "news_page_url",
    "news_id": "id",
    "news_title": "শিরোনাম"
  }
}
```

#### 3. **NewsObserver** (`app/Observers/NewsObserver.php`)
- খবর প্রকাশিত হওয়ার সময় ট্রিগার হয়
- `SendNewsNotification` Job ডিসপ্যাচ করে

#### 4. **PushSubscription Model** (`app/Models/PushSubscription.php`)
সাবস্ক্রিপশন ডেটা সংরক্ষণ করে:
- `endpoint` - Push service endpoint
- `public_key` - Encryption public key
- `auth_token` - Authentication token
- `is_active` - সক্রিয় অবস্থা
- `user_ip` - Client IP address
- `user_agent` - Browser information

## টেস্টিং

### অপশন 1: টেস্ট পেজ ব্যবহার করা

ব্রাউজারে খুলুন: `http://your-domain/test-push`

এই পেজে:
- ✓ সিস্টেম সাপোর্ট চেক করুন
- ✓ VAPID কী যাচাই করুন
- ✓ সাবস্ক্রাইব/আনসাবস্ক্রাইব করুন
- ✓ সমস্ত লগ দেখুন

### অপশন 2: নিউজ পেজ থেকে সরাসরি সাবস্ক্রাইব করা

যেকোনো নিউজ পেজে "পুশ নোটিফিকেশন চালু করুন" বাঁটনে ক্লিক করুন।

### অপশন 3: ম্যানুয়াল টেস্টিং (Node.js স্ক্রিপ্ট)

```bash
npm install web-push
```

`test-push.js` তৈরি করুন:
```javascript
const webpush = require('web-push');

webpush.setVapidDetails(
  'mailto:admin@example.com',
  process.env.VAPID_PUBLIC_KEY,
  process.env.VAPID_PRIVATE_KEY
);

const subscription = {
  // ডাটাবেসে সংরক্ষিত সাবস্ক্রিপশন
};

const payload = {
  title: 'টেস্ট নোটিফিকেশন',
  body: 'এটি একটি টেস্ট পুশ নোটিফিকেশন',
  icon: '/images/logo.png',
};

webpush.sendNotification(subscription, JSON.stringify(payload))
  .then(response => console.log('✓ পাঠানো হয়েছে'))
  .catch(error => console.error('✗ ত্রুটি:', error));
```

## সমস্যা সমাধান

### সমস্যা: "সাবস্ক্রাইব হচ্ছে না"

**সমাধান:**
1. ব্রাউজার কনসোল চেক করুন (F12)
2. VAPID কী সঠিক কিনা যাচাই করুন
3. Service Worker রেজিস্ট্রেড কিনা চেক করুন: `navigator.serviceWorker.getRegistration()`
4. নোটিফিকেশন অনুমতি দেওয়া আছে কিনা চেক করুন

### সমস্যা: নতুন নিউজ প্রকাশিত হওয়ার সময় নোটিফিকেশন আসছে না

**সমাধান:**
1. Queue চেক করুন: `php artisan queue:listen`
2. SendNewsNotification Job এক্সিকিউট হচ্ছে কিনা চেক করুন
3. VAPID কী কনফিগারড কিনা যাচাই করুন
4. Push subscription এন্ডপয়েন্ট ভ্যালিড কিনা চেক করুন (410/404 এরর?)

### সমস্যা: ব্রাউজার সাপোর্ট নেই

**সমাধান:**
- আপডেটেড ব্রাউজার ব্যবহার করুন:
  - Chrome 50+
  - Firefox 48+
  - Edge 17+
  - Safari 16+
  - Opera 37+

## নিরাপত্তা বিষয়

1. **VAPID প্রাইভেট কী রক্ষা করুন**
   - কখনও ফ্রন্টএন্ডে পাঠাবেন না
   - .env ফাইলে নিরাপদে রাখুন
   - গিট রিপোজিটরিতে কমিট করবেন না

2. **CSRF সুরক্ষা**
   - সব পুশ API এন্ডপয়েন্টে CSRF টোকেন চেক করা হয়

3. **Rate Limiting**
   - মিডলওয়্যার যোগ করুন প্রয়োজন অনুযায়ী

## মনিটরিং

Laravel logs চেক করুন: `storage/logs/laravel.log`

গুরুত্বপূর্ণ লগ মেসেজ:
```
Push subscription created
Push notifications sent for news
Failed to send push to subscriber
Push subscription deactivated
```

## অপটিমাইজেশন

1. **বাল্ক নোটিফিকেশনের জন্য Queue ব্যবহার করুন**
   ```bash
   php artisan queue:work
   ```

2. **অসক্রিয় সাবস্ক্রিপশন পরিষ্কার করুন**
   ```bash
   php artisan tinker
   >> PushSubscription::where('is_active', false)->where('updated_at', '<', now()->subDays(30))->delete();
   ```

3. **পারফরম্যান্স মনিটর করুন**
   - ডাটাবেসের সাবস্ক্রিপশন সংখ্যা চেক করুন
   - পুশ ব্যর্থতার হার ট্র্যাক করুন

## উপযোগী কমান্ড

```bash
# VAPID কী জেনারেট করা
php artisan vapid:generate

# Queue শোনা
php artisan queue:listen

# Tinker এ সাবস্ক্রিপশন চেক করা
php artisan tinker
>> PushSubscription::count()

# লগ দেখা
tail -f storage/logs/laravel.log

# সিনক্রোনাসলি পুশ পাঠানো (ডেভেলপমেন্টের জন্য)
# app/News এ Observer অ্যাক্টিভেট করুন
```

## সংস্করণ তথ্য

- **Laravel:** ^12.0
- **PHP:** ^8.3
- **Web Push Protocol:** RFC 8188 (aes128gcm)
- **VAPID:** RFC 8292
- **Service Worker:** Level 1

## সাহায্য এবং সহায়তা

যদি সমস্যা পান:
1. লগ ফাইল চেক করুন
2. ব্রাউজার কনসোল চেক করুন
3. নেটওয়ার্ক ট্যাব দেখুন API কল সফল কিনা
4. Service Worker স্ট্যাটাস যাচাই করুন

---

**সিস্টেম আপডেট:** February 26, 2026
**স্টেটাস:** সম্পূর্ণ ফাংশনাল
