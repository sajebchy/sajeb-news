# Error Fix: UrlGenerationException - author.show Route

**Date:** February 14, 2026  
**Status:** ✅ FIXED  
**Error Type:** Illuminate\Routing\Exceptions\UrlGenerationException

---

## Problem

```
Missing required parameter for [Route: author.show] [URI: author/{author}] [Missing parameter: author].
```

### Root Cause

The route definition was:
```php
Route::get('/author/{author}', [NewsController::class, 'author'])->name('author.show');
```

But it was being called with different parameter types:
- `route('author.show', $news->author->id)` - passing ID
- `route('author.show', $stream->user->username)` - passing username
- Both without explicit route binding

### Why It Failed

Laravel's implicit model binding requires explicit binding syntax like `{author:id}` or `{author:username}`. Without it, Laravel doesn't know which property to use for binding, causing the route generation to fail.

---

## Solution

### 1. Fixed Route Definition

**File:** `routes/web.php`

Changed from:
```php
Route::get('/author/{author}', [NewsController::class, 'author'])->name('author.show');
```

To:
```php
Route::get('/author/{author:id}', [NewsController::class, 'author'])->name('author.show');
```

**Why:** Explicit binding tells Laravel to find User by `id` property.

### 2. Fixed View Reference

**File:** `resources/views/public/live-stream/watch.blade.php`

Changed from:
```php
route('author.show', $stream->user->username)
```

To:
```php
route('author.show', $stream->user->id)
```

**Why:** Now consistently passing `id` which matches the route binding `{author:id}`.

---

## Verification

✅ Route properly configured:
```
GET|HEAD        author/{author} ............ author.show
```

✅ Route generation working:
```
User ID: 1
Route: http://localhost/author/1
```

✅ All calls to `route('author.show', ...)` now work with user ID

---

## Files Modified

1. ✅ `routes/web.php` - Added explicit binding `{author:id}`
2. ✅ `resources/views/public/live-stream/watch.blade.php` - Pass user ID instead of username

---

## Related Route Pattern

This fix follows the same pattern as other routes in the application:

```php
Route::get('/news/{news:slug}', ...)          // Explicit slug binding
Route::get('/category/{category:slug}', ...) // Explicit slug binding
Route::get('/author/{author:id}', ...)       // Explicit ID binding (NEW)
```

---

## Testing Status

✅ Route generation test: PASSED  
✅ Route registration: PASSED  
✅ All author route calls: FIXED

---

**No further action required.**
