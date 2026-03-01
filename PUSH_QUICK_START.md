# দ্রুত শুরু করুন - পুশ নোটিফিকেশন সেটআপ (৫ মিনিট)

## ধাপ ১: VAPID কী জেনারেট করুন

```bash
cd /Volumes/SSD-Golden Niche BD/sajeb-news
php artisan vapid:generate
```

আউটপুট দেখুন এবং `VAPID_PUBLIC_KEY` এবং `VAPID_PRIVATE_KEY` নোট করুন।

## ধাপ ২: .env ফাইল আপডেট করুন

`.env` ফাইল খুলুন এবং নিম্নলিখিত যোগ করুন:

```env
VAPID_PUBLIC_KEY=your_generated_public_key
VAPID_PRIVATE_KEY=your_generated_private_key
```

## ধাপ ৩: ডাটাবেস মাইগ্রেশন চালান (যদি প্রয়োজন হয়)

```bash
php artisan migrate
```

এটি `push_subscriptions` টেবিল তৈরি করবে।

## ধাপ ৪: সাবস্ক্রিপশন টেস্ট করুন

ব্রাউজারে খুলুন:
```
http://localhost:8000/test-push
```

অথবা আপনার ডোমেইন দিয়ে:
```
https://yourdomain.com/test-push
```

## ধাপ ৫: সাবস্ক্রাইব করুন

1. পেজ খোলার পরে "সাবস্ক্রাইব করুন" বাঁটনে ক্লিক করুন
2. ব্রাউজার আপনাকে নোটিফিকেশন অনুমতি দিতে বলবে - "অনুমতি দিন" এ ক্লিক করুন
3. সফল হলে স্ট্যাটাস "সক্রিয় সাবস্ক্রিপশন আছে" দেখাবে

## ধাপ ৬: নতুন নিউজ পাবলিশ করে টেস্ট করুন

1. Admin প্যানেলে লগইন করুন
2. নতুন নিউজ তৈরি করুন এবং "প্রকাশিত" করুন
3. আপনার ব্রাউজারে পুশ নোটিফিকেশন দেখবেন

## ট্রাবলশুটিং

### যদি সাবস্ক্রাইব বাঁটন কাজ না করে:

1. **ব্রাউজার কনসোল খুলুন** (F12 → Console)
2. **ত্রুটি মেসেজ খুঁজুন** - প্রায়ই VAPID কী সম্পর্কিত হবে
3. **VAPID কী চেক করুন** - meta tag এ সঠিক কিনা দেখুন:
   ```javascript
   document.head.querySelector('meta[name="vapid-public-key"]')?.content
   ```

### যদি সার্ভার এরর পান:

```bash
# Log দেখুন
tail -f storage/logs/laravel.log

# Queue চালান (নিউজ প্রকাশিত হলে নোটিফিকেশন পাঠায়)
php artisan queue:listen
```

## গুরুত্বপূর্ণ ফাইল

| ফাইল | উদ্দেশ্য |
|------|---------|
| `/public/js/push-notification-manager.js` | Push subscription ম্যানেজমেন্ট |
| `/public/js/push-notification-client.js` | UI এবং ইউজার ইন্টার‍্যাকশন |
| `/public/service-worker.js` | Service Worker - নোটিফিকেশন রিসিভ করা |
| `app/Jobs/SendNewsNotification.php` | নিউজ প্রকাশিত হলে নোটিফিকেশন পাঠায়  |
| `app/Http/Controllers/PushNotificationController.php` | API এন্ডপয়েন্ট |

## পরবর্তী পদক্ষেপ

✓ **সেটআপ সম্পূর্ণ হয়েছে!**

এখন:
- যেকোনো নিউজ পেজে সাবস্ক্রাইব বাঁটন দেখবেন
- নতুন নিউজ প্রকাশিত হলে সকল সাবস্ক্রাইবারদের কাছে পুশ যাবে
- নোটিফিকেশন ক্লিক করলে সেই নিউজ পেজে যাবে

## যোগাযোগ/সাহায্য

বিস্তারিত গাইডের জন্য দেখুন: `PUSH_NOTIFICATIONS_SETUP.md`

---
**সর্বশেষ আপডেট:** February 26, 2026
