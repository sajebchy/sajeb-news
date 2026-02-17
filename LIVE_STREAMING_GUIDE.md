# Live Video Broadcasting System - Complete Documentation

**Status**: ✅ PRODUCTION READY  
**Date**: February 14, 2026  
**Feature**: Live Video Broadcasting with OBS Studio Integration

---

## Overview

সম্পূর্ণ লাইভ ভিডিও ব্রডকাস্টিং সিস্টেম যা আপনাকে OBS Studio বা যেকোনো RTMP-সামর্থী broadcaster সফটওয়্যার দিয়ে সরাসরি লাইভ স্ট্রীম করার সুযোগ দেয়।

---

## Features

### ✅ Admin Panel Features
- **Stream Management**: সম্পূর্ণ CRUD অপারেশন (Create, Read, Update, Delete)
- **Stream Keys**: স্বয়ংক্রিয় অনন্য স্ট্রীম কী জেনারেশন
- **OBS Configuration Guide**: ধাপে ধাপে OBS সেটআপ গাইড
- **Stream Control**: Start/Stop স্ট্রীম সরাসরি প্যানেল থেকে
- **Scheduling**: ভবিষ্যতের জন্য স্ট্রীম সময়নির্ধারণ করুন
- **Featured Streams**: বিশেষ স্ট্রীম হাইলাইট করুন
- **Analytics**: দর্শক সংখ্যা এবং পিক ভিউয়ার ট্র্যাকিং
- **Visibility Control**: জনসাধারণ/অসংক্ষিপ্ত/ব্যক্তিগত স্ট্রীম

### ✅ Public Streaming Features
- **Live Stream Page**: সমস্ত লাইভ স্ট্রীম দেখুন
- **Watch Page**: স্ট্রীম দেখার জন্য ডেডিকেটেড পেজ
- **Stream Stats**: দর্শক সংখ্যা এবং স্ট্যাটিস্টিক্স
- **Social Sharing**: ফেসবুক/টুইটার শেয়ারিং
- **Upcoming Streams**: আসন্ন স্ট্রীমের তালিকা
- **Featured Section**: বৈশিষ্ট্যযুক্ত স্ট্রীম প্রদর্শন

---

## Database Structure

### `live_streams` টেবিল

```sql
CREATE TABLE live_streams (
    id BIGINT PRIMARY KEY,
    user_id BIGINT (স্ট্রীম ক্রিয়েটর)
    title VARCHAR(255) (স্ট্রীমের নাম)
    description TEXT (বিস্তারিত বিবরণ)
    slug VARCHAR(255) UNIQUE (URL স্লাগ)
    status VARCHAR(255) DEFAULT 'draft' (draft, pending, live, ended, archived)
    thumbnail VARCHAR(255) (থাম্বনেইল ইমেজ)
    stream_key VARCHAR(255) UNIQUE (OBS-এ ব্যবহারের জন্য)
    stream_url VARCHAR(255) (RTMP সার্ভার URL)
    visibility ENUM('public', 'private', 'unlisted')
    viewer_count INT (বর্তমান দর্শক)
    peak_viewers INT (সর্বোচ্চ দর্শক)
    scheduled_at TIMESTAMP (নির্ধারিত শুরুর সময়)
    started_at TIMESTAMP (প্রকৃত শুরুর সময়)
    ended_at TIMESTAMP (শেষের সময়)
    duration_seconds INT (স্ট্রীমের সময়কাল)
    stream_tags JSON (ট্যাগ সমূহ)
    category VARCHAR(255) (বিভাগ)
    allow_comments BOOLEAN (মন্তব্য অনুমতি)
    allow_chat BOOLEAN (চ্যাট অনুমতি)
    recording_url VARCHAR(255) (রেকর্ডিং লিংক)
    is_featured BOOLEAN (বৈশিষ্ট্যযুক্ত?)
    view_count INT (মোট ভিউ)
    like_count INT (পছন্দের সংখ্যা)
    created_at TIMESTAMP
    updated_at TIMESTAMP
    deleted_at TIMESTAMP
)
```

---

## File Structure

### Models
- `app/Models/LiveStream.php` - লাইভ স্ট্রীম মডেল

### Controllers
- `app/Http/Controllers/Admin/LiveStreamController.php` - অ্যাডমিন প্যানেল নিয়ন্ত্রণ
- `app/Http/Controllers/LiveStreamViewController.php` - পাবলিক স্ট্রীম ভিউয়িং

### Views

**Admin Views:**
- `resources/views/admin/live-streams/index.blade.php` - স্ট্রীম তালিকা
- `resources/views/admin/live-streams/create.blade.php` - স্ট্রীম তৈরি ফর্ম
- `resources/views/admin/live-streams/show.blade.php` - স্ট্রীম বিবরণ
- `resources/views/admin/live-streams/obs-settings.blade.php` - OBS গাইড

**Public Views:**
- `resources/views/public/live-stream/index.blade.php` - সমস্ত স্ট্রীম
- `resources/views/public/live-stream/watch.blade.php` - স্ট্রীম দেখুন

### Configuration
- `config/broadcasting.php` - RTMP সার্ভার কনফিগারেশন

---

## Routes

### Admin Routes
```php
// লাইভ স্ট্রীম ম্যানেজমেন্ট
GET    /admin/live-streams                      (index)      - সমস্ত স্ট্রীম তালিকা
GET    /admin/live-streams/create               (create)     - স্ট্রীম তৈরি ফর্ম
POST   /admin/live-streams                      (store)      - স্ট্রীম সংরক্ষণ
GET    /admin/live-streams/{stream}             (show)       - স্ট্রীম বিবরণ
GET    /admin/live-streams/{stream}/edit        (edit)       - স্ট্রীম সম্পাদনা ফর্ম
PUT    /admin/live-streams/{stream}             (update)     - স্ট্রীম আপডেট
DELETE /admin/live-streams/{stream}             (destroy)    - স্ট্রীম মুছে ফেলুন
POST   /admin/live-streams/{stream}/start       (start)      - স্ট্রীম শুরু করুন
POST   /admin/live-streams/{stream}/stop        (stop)       - স্ট্রীম বন্ধ করুন
POST   /admin/live-streams/{stream}/regenerate-key (regenerate) - কী পুনরায় তৈরি
POST   /admin/live-streams/{stream}/toggle-featured (toggle)   - বৈশিষ্ট্য টগল
GET    /admin/live-streams/{stream}/obs-settings    (obs)      - OBS গাইড
```

### Public Routes
```php
GET /live                    - সমস্ত লাইভ স্ট্রীম দেখুন
GET /live/{stream:slug}      - স্ট্রীম দেখুন
GET /live/{stream}/chat      - চ্যাট মেসেজ API
```

---

## OBS Studio Configuration

### ধাপ 1: ডাউনলোড এবং ইনস্টল করুন
1. https://obsproject.com/ থেকে OBS Studio ডাউনলোড করুন
2. আপনার OS-এর জন্য ইনস্টল করুন

### ধাপ 2: স্ট্রীম সেটিংস কনফিগার করুন
1. OBS খুলুন → Settings → Stream
2. **Service**: Custom...
3. **Server**: স্ট্রীম বিবরণ থেকে RTMP URL
4. **Stream Key**: স্ট্রীম বিবরণ থেকে কী

### ধাপ 3: সেটিংস অপ্টিমাইজ করুন
- **Bitrate**: 2500-4000 kbps
- **Resolution**: 1920x1080 (1080p)
- **FPS**: 30 বা 60
- **Encoder**: H.264

### ধাপ 4: সিন এবং সোর্স যুক্ত করুন
- Window Capture (স্ক্রিন)
- Display Capture (মনিটর)
- Webcam (ক্যামেরা)
- Audio Sources (মাইক)

### ধাপ 5: স্ট্রীম শুরু করুন
1. OBS-এ "Start Streaming" ক্লিক করুন
2. অ্যাডমিন প্যানেলে স্ট্রীম স্ট্যাটাস "LIVE"-এ পরিবর্তন হবে
3. আপনার দর্শকদের স্ট্রীম লিংক শেয়ার করুন

---

## Admin Panel Usage

### স্ট্রীম তৈরি করুন
1. `/admin/live-streams/create` এ যান
2. শিরোনাম, বিবরণ এবং সেটিংস পূরণ করুন
3. একটি অনন্য **Stream Key** স্বয়ংক্রিয়ভাবে তৈরি হয়
4. "Create Stream" ক্লিক করুন

### স্ট্রীম শুরু করুন
1. স্ট্রীম বিবরণ পৃষ্ঠায় যান
2. "Start Stream" বোতাম ক্লিক করুন
3. Status `draft` থেকে `live`-এ পরিবর্তন হয়
4. OBS-এ "Start Streaming" ক্লিক করুন

### স্ট্রীম বন্ধ করুন
1. "Stop Stream" বোতাম ক্লিক করুন
2. Duration এবং viewer stats স্বয়ংক্রিয়ভাবে সংরক্ষিত হয়
3. স্ট্রীম archived এ চলে যায়

### OBS সেটিংস দেখুন
1. স্ট্রীম বিবরণে "OBS Configuration Guide" ক্লিক করুন
2. ধাপে ধাপে সেটআপ নির্দেশনা পান
3. RTMP URL এবং Stream Key সরাসরি কপি করুন

---

## Environment Variables

`.env` ফাইলে যোগ করুন:

```env
# RTMP সার্ভার কনফিগারেশন
RTMP_SERVER_URL=rtmp://localhost
RTMP_APP_NAME=live
RTMP_PORT=1935

# লাইভ স্ট্রীম সেটিংস
MAX_CONCURRENT_STREAMS=5
MAX_STREAM_DURATION=480
AUTO_RECORD_STREAM=true
ENABLE_LIVE_CHAT=true
ENABLE_STREAM_COMMENTS=true
```

---

## Methods & Functions

### LiveStream Model

```php
// চেক করুন স্ট্রীম লাইভ আছে কিনা
$stream->isLive()              // বুলিয়ান

// চেক করুন স্ট্রীম সময়নির্ধারিত আছে কিনা
$stream->isScheduled()         // বুলিয়ান

// চেক করুন স্ট্রীম শেষ হয়েছে কিনা
$stream->hasEnded()            // বুলিয়ান

// ফরম্যাটেড সময়কাল পান
$stream->getFormattedDuration() // "01:23:45"

// অনন্য স্ট্রীম কী তৈরি করুন
LiveStream::generateStreamKey() // "a1b2c3d4e5f6..."

// RTMP URL পান
$stream->getRtmpUrl()          // "rtmp://localhost/live"

// স্ট্রীম URL পান
$stream->getStreamUrl()        // "/live/slug-name"
```

### Controller Methods

```php
// সমস্ত স্ট্রীম তালিকা
index()

// স্ট্রীম তৈরি ফর্ম
create()

// স্ট্রীম সংরক্ষণ করুন
store(Request $request)

// স্ট্রীম বিবরণ দেখুন
show(LiveStream $stream)

// সম্পাদনা ফর্ম
edit(LiveStream $stream)

// স্ট্রীম আপডেট করুন
update(Request $request, LiveStream $stream)

// স্ট্রীম শুরু করুন
start(LiveStream $stream)

// স্ট্রীম বন্ধ করুন
stop(LiveStream $stream)

// কী পুনরায় তৈরি করুন
regenerateKey(LiveStream $stream)

// বৈশিষ্ট্য টগল করুন
toggleFeatured(LiveStream $stream)

// স্ট্রীম মুছে ফেলুন
destroy(LiveStream $stream)

// OBS সেটিংস গাইড
obsSettings(LiveStream $stream)
```

---

## Validation Rules

```php
'title' => 'required|string|max:255',
'description' => 'nullable|string|max:5000',
'category' => 'nullable|string|max:100',
'visibility' => 'required|in:public,private,unlisted',
'scheduled_at' => 'nullable|date|after:now',
'thumbnail' => 'nullable|image|max:5120',
'stream_tags' => 'nullable|string',
'allow_comments' => 'boolean',
'allow_chat' => 'boolean',
```

---

## Usage Examples

### স্ট্রীম তৈরি করুন
```php
$stream = LiveStream::create([
    'user_id' => auth()->id(),
    'title' => 'আমার প্রথম লাইভ স্ট্রীম',
    'description' => 'একটি দুর্দান্ত স্ট্রীম!',
    'status' => 'draft',
    'stream_key' => LiveStream::generateStreamKey(),
    'stream_url' => config('services.rtmp.server_url'),
    'visibility' => 'public',
]);
```

### স্ট্রীম শুরু করুন
```php
$stream->update([
    'status' => 'live',
    'started_at' => now(),
]);
```

### স্ট্রীম বন্ধ করুন
```php
$stream->update([
    'status' => 'ended',
    'ended_at' => now(),
    'duration_seconds' => $stream->started_at->diffInSeconds(now()),
]);
```

### সমস্ত লাইভ স্ট্রীম পান
```php
$liveStreams = LiveStream::where('status', 'live')
    ->where('visibility', 'public')
    ->latest('started_at')
    ->get();
```

---

## Features Coming Soon

- 🔄 Real-time viewer count updates
- 💬 Live chat integration
- 📹 Automatic recording and archive
- 📊 Detailed analytics dashboard
- 🔔 Subscriber notifications
- 💝 Super chat/donations
- 🎬 Video clips and highlights
- 📱 Mobile responsive streaming

---

## Security Notes

⚠️ **Stream Key Privacy**
- Stream key কখনো জনসাধারণের সাথে শেয়ার করবেন না
- যদি compromised হয়, অবিলম্বে regenerate করুন
- একটি এনভায়রনমেন্ট ভেরিয়েবলে RTMP সার্ভার সংরক্ষণ করুন

🔒 **Visibility Control**
- Private স্ট্রীম শুধুমাত্র মালিক এবং অ্যাডমিন দেখতে পারে
- Unlisted স্ট্রীম শুধুমাত্র লিংক জানা লোক দেখতে পারে
- Public স্ট্রীম সবার জন্য দৃশ্যমান

---

## Troubleshooting

### "Failed to connect to streaming server"
1. RTMP Server URL সঠিক কিনা চেক করুন
2. স্ট্রীম কী যাচাই করুন
3. ফায়ারওয়াল পোর্ট 1935 অনুমতি দিচ্ছে কিনা চেক করুন
4. RTMP সার্ভার চালু আছে কিনা চেক করুন

### "Stream key not working"
1. Stream key আপডেট করা হয়েছে কিনা চেক করুন
2. OBS পুনরায় চালু করুন
3. Stream key regenerate করুন এবং আবার চেষ্টা করুন

### "No audio in stream"
1. OBS-এ অডিও ডিভাইস নির্বাচিত আছে কিনা চেক করুন
2. মাইক permission দিয়েছেন কিনা চেক করুন
3. অডিও লেভেল চেক করুন (muted তো নয়?)

### "Stream lag/buffering"
1. ইন্টারনেট গতি 5 Mbps upload থাকা উচিত
2. Bitrate কমান (3000 kbps থেকে শুরু করুন)
3. Resolution কমান (720p এ চেষ্টা করুন)
4. Ethernet কানেকশন ব্যবহার করুন WiFi এর বদলে

---

## Configuration

### RTMP Server Setup (Optional)

আপনার নিজস্ব RTMP সার্ভার সেটআপ করতে:

```bash
# Windows
Download nginx-rtmp from https://github.com/illuspas/nginx-rtmp-win32

# Linux
sudo apt-get install nginx libnginx-mod-rtmp

# macOS
brew install nginx
```

`.env` এ আপডেট করুন:
```env
RTMP_SERVER_URL=rtmp://your-server-ip
RTMP_APP_NAME=live
```

---

## Testing

Admin প্যানেলে test করুন:

```bash
1. /admin/live-streams/create এ যান
2. একটি নতুন স্ট্রীম তৈরি করুন
3. "Start Stream" ক্লিক করুন
4. OBS-এ RTMP সেটিংস যোগ করুন
5. "Start Streaming" ক্লিক করুন
6. /live/stream-slug এ গিয়ে স্ট্রীম দেখুন
```

---

**Status**: ✅ PRODUCTION READY

সম্পূর্ণ লাইভ ব্রডকাস্টিং সিস্টেম প্রস্তুত এবং ব্যবহারের জন্য!
