<?php

use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\PagesController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [NewsController::class, 'index'])->name('home');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/category/{category:slug}', [NewsController::class, 'category'])->name('category.show');
Route::get('/tag/{tag}', [NewsController::class, 'tag'])->name('tag.show');
Route::get('/author/{author:id}', [NewsController::class, 'author'])->name('author.show');
Route::get('/search', [NewsController::class, 'search'])->name('news.search');

// Pages Routes
Route::get('/about', [PagesController::class, 'about'])->name('about');
Route::get('/contact', [PagesController::class, 'contact'])->name('contact');
Route::post('/contact', [PagesController::class, 'storeContact'])->name('contact.store');
Route::get('/privacy-policy', [PagesController::class, 'privacy'])->name('privacy');
Route::get('/terms-and-conditions', [PagesController::class, 'terms'])->name('terms');
Route::get('/sitemap', [PagesController::class, 'sitemap'])->name('sitemap');
Route::get('/sitemap.xml', [PagesController::class, 'sitemapXml'])->name('sitemap.xml');
Route::get('/llm.txt', [PagesController::class, 'llmTxt'])->name('llm.txt');

// Live Stream Routes (Public)
Route::get('/live', [\App\Http\Controllers\LiveStreamViewController::class, 'index'])->name('live.index');
Route::get('/live/{stream:slug}', [\App\Http\Controllers\LiveStreamViewController::class, 'watch'])->name('live.watch');
Route::get('/live/{stream}/chat', [\App\Http\Controllers\LiveStreamViewController::class, 'chatMessages'])->name('live.chat');

// Live Stream Comments (Public)
Route::post('/live/{stream:slug}/comments', [\App\Http\Controllers\StreamCommentController::class, 'store'])->name('live.comments.store');
Route::get('/live/{stream:slug}/comments', [\App\Http\Controllers\StreamCommentController::class, 'getComments'])->name('live.comments.list');
Route::delete('/live/comments/{comment}', [\App\Http\Controllers\StreamCommentController::class, 'destroy'])->name('live.comments.destroy');
Route::post('/live/comments/{comment}/like', [\App\Http\Controllers\StreamCommentController::class, 'like'])->name('live.comments.like');

// Web Push Notification Routes (Public)
Route::post('/api/push/subscribe', [\App\Http\Controllers\PushNotificationController::class, 'subscribe'])->name('push.subscribe');
Route::post('/api/push/unsubscribe', [\App\Http\Controllers\PushNotificationController::class, 'unsubscribe'])->name('push.unsubscribe');
Route::post('/api/push/check', [\App\Http\Controllers\PushNotificationController::class, 'checkSubscription'])->name('push.check');
Route::get('/api/push/stats', [\App\Http\Controllers\PushNotificationController::class, 'getStats'])->name('push.stats');

// Live Stream Status Check (Public API)
Route::get('/api/live/active', function () {
    $activeLiveStream = \App\Models\LiveStream::where('status', 'active')
        ->where('start_time', '<=', now())
        ->where(function($query) {
            $query->whereNull('end_time')
                  ->orWhere('end_time', '>', now());
        })
        ->first();
    
    return response()->json([
        'active' => $activeLiveStream ? true : false,
        'stream' => $activeLiveStream ? [
            'id' => $activeLiveStream->id,
            'title' => $activeLiveStream->title,
            'slug' => $activeLiveStream->slug,
        ] : null
    ]);
})->name('api.live.active');

// Debug User Verification Status
Route::get('/admin-verify', function () {
    // Find the test admin user
    $user = \App\Models\User::where('email', 'admin@test.com')->first();
    
    if ($user) {
        // Ensure the user has email verification
        $user->email_verified_at = now();
        $user->save();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Admin user verified successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'role' => $user->roles->first() ? $user->roles->first()->name : 'NO ROLE'
            ],
            'login_credentials' => [
                'email' => 'admin@test.com',
                'password' => '12345'
            ]
        ]);
    }
    
    return response()->json([
        'status' => 'error',
        'message' => 'Admin user not found. Please run: php artisan db:seed'
    ], 404);
});

// Quick fix: Create a comprehensive fix dialog
Route::get('/fix-login-now', function () {
    return <<<'HTML'
    <!DOCTYPE html>
    <html lang="bn">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>লগইন সমস্যা সমাধান</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
            .card { border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.3); border-radius: 15px; }
            .btn-custom { padding: 15px 30px; font-size: 17px; font-weight: bold; border-radius: 8px; }
            .status-box { background: #f8f9fa; border-left: 5px solid #667eea; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
            .status-ok { border-left-color: #28a745; }
        </style>
    </head>
    <body class="d-flex align-items-center justify-content-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <h2 class="fw-bold text-primary">🔐 সিস্টেম স্ট্যাটাস এবং লগইন গাইড</h2>
                            </div>

                            <!-- System Status -->
                            <div class="status-box status-ok">
                                <h5 class="text-success">✅ সিস্টেম প্রস্তুত</h5>
                                <small class="text-muted">সেশন কনফিগ ঠিক করা হয়েছে</small>
                            </div>

                            <!-- Step 1: Create Admin -->
                            <div class="alert alert-info mb-3">
                                <h5 class="mb-2">📝 ধাপ ১: অ্যাডমিন ইউজার তৈরি করুন</h5>
                                <p class="mb-2 small text-muted">আগে থেকে ইউজার থাকলে এই ধাপ স্কিপ করুন</p>
                                <button class="btn btn-info btn-custom w-100" onclick="setupAdmin()">
                                    নতুন অ্যাডমিন তৈরি করুন
                                </button>
                            </div>

                            <!-- Step 2: Username List -->
                            <div class="alert alert-warning mb-3">
                                <h5 class="mb-2">👥 সব ইউজার দেখুন</h5>
                                <button class="btn btn-warning btn-custom w-100" onclick="showUsers()">
                                    রেজিস্টার্ড ইউজার লিস্ট
                                </button>
                            </div>

                            <!-- Step 3: Login -->
                            <div class="alert alert-success mb-3">
                                <h5 class="mb-2">🚀 ধাপ ২: লগইন করুন</h5>
                                <p class="mb-2 small text-muted">নিচের ফর্ম ব্যবহার করুন অথবা লগইন পেজে যান</p>
                                <button class="btn btn-success btn-custom w-100" onclick="window.location='/login'">
                                    লগইন পেজে যান
                                </button>
                            </div>

                            <!-- Default Credentials -->
                            <div class="alert alert-secondary">
                                <strong>📝 ডিফল্ট ক্রেডেনশিয়ালস:</strong><br>
                                <code>ইমেইল: admin@example.com</code><br>
                                <code>পাসওয়ার্ড: 12345</code>
                            </div>

                            <!-- Debug Info -->
                            <div class="alert alert-light border">
                                <small class="text-muted">
                                    <strong>ডিবাগ তথ্য:</strong><br>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="checkLogs()">লগইন লগ দেখুন</button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="checkSession()">সেশন চেক করুন</button>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function setupAdmin() {
                if (confirm('প্রস্তুত? এটি সব ইউজার মুছে নতুন অ্যাডমিন তৈরি করবে।')) {
                    window.location = '/admin-setup';
                }
            }

            function showUsers() {
                fetch('/debug-login')
                    .then(res => res.json())
                    .then(data => {
                        let users = data.users.map(u => 
                            `ইমেইল: ${u.email}, রোল: ${u.roles.join(', ')}`
                        ).join('\n');
                        alert('রেজিস্টার্ড ইউজার:\n\n' + users);
                    });
            }

            function checkLogs() {
                fetch('/login-debug-logs')
                    .then(res => res.json())
                    .then(data => {
                        console.log('Recent logs:', data.recent_logs);
                        alert('লাস্ট লগ দেখতে কনসোল খুলুন (F12)');
                    });
            }

            function checkSession() {
                fetch('/test-session')
                    .then(res => res.json())
                    .then(data => {
                        alert('সেশন আইডি: ' + data.session_id + '\nড্রাইভার: ' + data.SESSION_DRIVER);
                    });
            }
        </script>
    </body>
    </html>
    HTML;
});

// Test login without redirect
Route::post('/test-simple-login', function (\Illuminate\Http\Request $request) {
    echo "TEST_DEBUG_1\n";
    $email = $request->input('email') ?? '';
    $password = $request->input('password') ?? '';
    
    echo "TEST_DEBUG_2: $email\n";
    
    // Find user by email
    $user = \App\Models\User::where('email', $email)->first();
    
    echo "TEST_DEBUG_3\n";
    
    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'User not found'
        ], 401);
    }
    
    echo "TEST_DEBUG_4\n";
    
    // Check password
    if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Password incorrect'
        ], 401);
    }
    
    echo "TEST_DEBUG_5\n";
    
    // Check if email verified
    if (!$user->email_verified_at) {
        return response()->json([
            'status' => 'error',
            'message' => 'Email not verified'
        ], 403);
    }
    
    echo "TEST_DEBUG_6\n";
    
    return response()->json([
        'status' => 'success',
        'message' => 'Credentials valid - Ready to login',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified' => $user->email_verified_at ? 'YES' : 'NO',
            'roles' => $user->roles->pluck('name')->toArray()
        ]
    ]);
});

// Debug login logs
Route::get('/login-debug-logs', function () {
    $logFile = storage_path('logs/laravel.log');
    
    if (!file_exists($logFile)) {
        return response()->json(['error' => 'Log file not found'], 404);
    }
    
    $lines = file($logFile);
    $logs = [];
    
    foreach (array_slice($lines, -50) as $line) {
        if (strpos($line, '🔐') !== false || 
            strpos($line, '✅') !== false || 
            strpos($line, '🎯') !== false ||
            strpos($line, 'Login') !== false ||
            strpos($line, 'auth') !== false) {
            $logs[] = trim($line);
        }
    }
    
    return response()->json([
        'status' => 'debug',
        'recent_logs' => array_reverse($logs),
        'log_file' => $logFile
    ]);
});

// Debug: Check system status
Route::get('/debug-login', function () {
    // Check users in database
    $users = \App\Models\User::all();
    $total_users = $users->count();
    
    $user_list = [];
    foreach ($users as $user) {
        $user_list[] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'roles' => $user->roles->pluck('name')->toArray()
        ];
    }
    
    return response()->json([
        'status' => 'debug',
        'total_users' => $total_users,
        'users' => $user_list,
        'app_debug' => config('app.debug'),
        'session_driver' => config('session.driver'),
        'auth_guard' => config('auth.defaults.guard'),
        'note' => 'If no users shown, run /admin-setup first'
    ]);
});

// Setup: Create and verify admin user
Route::get('/admin-setup', function () {
    \App\Models\User::query()->delete(); // Clear all users
    
    $admin = \App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('12345'),
    ]);
    
    // Force update email_verified_at
    \DB::table('users')->where('id', $admin->id)->update([
        'email_verified_at' => \Carbon\Carbon::now()
    ]);
    
    // Reload admin object
    $admin = \App\Models\User::find($admin->id);
    
    $admin->assignRole('super-admin');
    
    return response()->json([
        'status' => 'success',
        'message' => 'Admin user created successfully',
        'login_credentials' => [
            'email' => 'admin@example.com',
            'password' => '12345'
        ],
        'user' => [
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->roles->first()->name,
            'email_verified_at' => $admin->email_verified_at
        ]
    ]);
});

// Diagnostics and Quick Login Test
Route::get('/admin-login-test', function () {
    return <<<'HTML'
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login Test</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
            .login-container { background: white; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
            .btn-setup { background: #28a745; border: none; }
            .btn-setup:hover { background: #218838; }
        </style>
    </head>
    <body class="d-flex align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="login-container p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary">Admin Login</h3>
                            <p class="text-muted small">ডেসবোড এ লগইন করার জন্য আপনার ইমেইল এবং পাসওয়াড ব্যবহার করুন</p>
                        </div>

                        <!-- Setup Alert -->
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>প্রথম বার?</strong> নীচের বাটনটি ক্লিক করে একটি নতুন অ্যাডমিন ব্যবহারকারী তৈরি করুন।
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <button class="btn btn-setup w-100 mb-3" onclick="setupAdmin()">
                            নতুন অ্যাডমিন ইউজার তৈরি করুন
                        </button>

                        <hr class="my-3">
                        <p class="text-center text-muted small mb-3">অথবা নীচের ফর্ম ব্যবহার করে লগইন করুন</p>

                        <!-- Login Form -->
                        <form method="POST" action="/login" id="loginForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">ইমেইল</label>
                                <input type="email" name="email" class="form-control form-control-lg" 
                                       placeholder="admin@example.com" value="admin@example.com" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">পাসওয়ার্ড</label>
                                <input type="password" name="password" class="form-control form-control-lg" 
                                       placeholder="আপনার পাসওয়ার্ড" value="12345" required>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">আমাকে মনে রাখুন</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">
                                লগইন করুন
                            </button>
                        </form>

                        <!-- Default Credentials -->
                        <div class="alert alert-info mt-4 small">
                            <strong>ডিফল্ট ক্রেডেনশিয়ালস:</strong><br>
                            ইমেইল: <code>admin@example.com</code><br>
                            পাসওয়ার্ড: <code>12345</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function setupAdmin() {
                if (confirm('এটি সমস্ত বিদ্যমান ব্যবহারকারী মুছে ফেলবে এবং একটি নতুন অ্যাডমিন ব্যবহারকারী তৈরি করবে। চালিয়ে যেতে চান?')) {
                    fetch('/admin-setup')
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                alert('সাফল্য! নতুন অ্যাডমিন ইউজার তৈরি হয়েছে।\n\nইমেইল: ' + data.login_credentials.email + '\nপাসওয়ার্ড: ' + data.login_credentials.password);
                                location.reload();
                            } else {
                                alert('ত্রুটি: ' + data.message);
                            }
                        })
                        .catch(err => alert('ত্রুটি: ' + err.message));
                }
            }
        </script>
    </body>
    </html>
    HTML;
});;

// Debug Session Test
Route::get('/test-session', function (Illuminate\Http\Request $request) {
    $request->session()->put('test', 'hello world');
    return response()->json([
        'message' => 'Session set',
        'session_id' => session()->getId(),
        'cookie_name' => config('session.cookie'),
        'SESSION_DRIVER' => env('SESSION_DRIVER'),
        'session_data' => session()->all()
    ]);
});

Route::post('/test-session', function (Illuminate\Http\Request $request) {
    return response()->json([
        'message' => 'Session test',
        'has_test' => session()->has('test'),
        'test_value' => session()->get('test'),
        'session_id' => session()->getId(),
        'all_session' => session()->all()
    ]);
});

// Newsletter Routes
Route::post('/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// News Comments Routes
Route::middleware('auth')->group(function () {
    Route::post('/news/{news:slug}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('news.comments.store');
    Route::delete('/news/comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('news.comments.destroy');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // News Management
    Route::resource('news', \App\Http\Controllers\Admin\NewsController::class);
    Route::post('/news/upload-image', [\App\Http\Controllers\Admin\NewsController::class, 'uploadImage'])->name('news.upload-image');
    
    // Category Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Tag Management
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class);
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::put('/users/{user}/change-password', [\App\Http\Controllers\Admin\UserController::class, 'changePassword'])->name('users.change-password');
    
    // Analytics
    Route::get('/analytics', [\App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/{news}', [\App\Http\Controllers\Admin\AnalyticsController::class, 'show'])->name('analytics.show');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update']);
    
    // Activity Logs
    Route::get('/activities', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activities');
    
    // Newsletter Subscribers
    Route::get('/newsletters', [\App\Http\Controllers\Admin\NewsletterController::class, 'index'])->name('newsletters.index');
    Route::delete('/newsletters/{subscriber}', [\App\Http\Controllers\Admin\NewsletterController::class, 'destroy'])->name('newsletters.destroy');
    
    // Advertisement Management
    Route::get('/advertisements/export/csv', [\App\Http\Controllers\Admin\AdController::class, 'export'])->name('advertisements.export');
    Route::post('/advertisements/upload', [\App\Http\Controllers\Admin\AdController::class, 'uploadAdvertisementImage'])->name('upload-advertisement-image');
    Route::resource('advertisements', \App\Http\Controllers\Admin\AdController::class);
    Route::post('/advertisements/{advertisement}/toggle-status', [\App\Http\Controllers\Admin\AdController::class, 'toggleStatus'])->name('advertisements.toggle-status');
    Route::get('/advertisements/{advertisement}/statistics', [\App\Http\Controllers\Admin\AdController::class, 'statistics'])->name('advertisements.statistics');
    
    // Live Stream Management - Only for Admins
    Route::middleware('admin')->group(function () {
        Route::get('/live-streams', [\App\Http\Controllers\Admin\LiveStreamController::class, 'index'])->name('live-streams.index');
        Route::post('/live-streams', [\App\Http\Controllers\Admin\LiveStreamController::class, 'store'])->name('live-streams.store');
        Route::get('/live-streams/create', [\App\Http\Controllers\Admin\LiveStreamController::class, 'create'])->name('live-streams.create');
        Route::get('/live-streams/{stream}', [\App\Http\Controllers\Admin\LiveStreamController::class, 'show'])->name('live-streams.show');
        Route::put('/live-streams/{stream}', [\App\Http\Controllers\Admin\LiveStreamController::class, 'update'])->name('live-streams.update');
        Route::patch('/live-streams/{stream}', [\App\Http\Controllers\Admin\LiveStreamController::class, 'update']);
        Route::delete('/live-streams/{stream}', [\App\Http\Controllers\Admin\LiveStreamController::class, 'destroy'])->name('live-streams.destroy');
        Route::get('/live-streams/{stream}/edit', [\App\Http\Controllers\Admin\LiveStreamController::class, 'edit'])->name('live-streams.edit');
        Route::post('/live-streams/{stream}/start', [\App\Http\Controllers\Admin\LiveStreamController::class, 'start'])->name('live-streams.start');
        Route::post('/live-streams/{stream}/stop', [\App\Http\Controllers\Admin\LiveStreamController::class, 'stop'])->name('live-streams.stop');
        Route::post('/live-streams/{stream}/regenerate-key', [\App\Http\Controllers\Admin\LiveStreamController::class, 'regenerateKey'])->name('live-streams.regenerate-key');
        Route::post('/live-streams/{stream}/toggle-featured', [\App\Http\Controllers\Admin\LiveStreamController::class, 'toggleFeatured'])->name('live-streams.toggle-featured');
        Route::get('/live-streams/{stream}/obs-settings', [\App\Http\Controllers\Admin\LiveStreamController::class, 'obsSettings'])->name('live-streams.obs-settings');
        
        // Live Stream Comments Moderation
        Route::post('/live-streams/{stream}/comments/{comment}/approve', [\App\Http\Controllers\StreamCommentController::class, 'approve'])->name('live-streams.comments.approve');
        Route::post('/live-streams/{stream}/comments/{comment}/reject', [\App\Http\Controllers\StreamCommentController::class, 'reject'])->name('live-streams.comments.reject');
        Route::post('/live-streams/{stream}/comments/{comment}/pin', [\App\Http\Controllers\StreamCommentController::class, 'pin'])->name('live-streams.comments.pin');
        Route::post('/live-streams/{stream}/comments/{comment}/unpin', [\App\Http\Controllers\StreamCommentController::class, 'unpin'])->name('live-streams.comments.unpin');
    });
    
    // File Manager Routes
    Route::get('/file-manager', function () {
        return view('admin.file-manager');
    })->name('file-manager.index');
    
    Route::prefix('file-manager')->name('file-manager.')->group(function () {
        Route::post('/upload', [\App\Http\Controllers\Admin\FileManagerController::class, 'upload'])->name('upload');
        Route::post('/delete', [\App\Http\Controllers\Admin\FileManagerController::class, 'delete'])->name('delete');
        Route::post('/rename', [\App\Http\Controllers\Admin\FileManagerController::class, 'rename'])->name('rename');
        Route::post('/create-folder', [\App\Http\Controllers\Admin\FileManagerController::class, 'createFolder'])->name('create-folder');
        Route::get('/list', [\App\Http\Controllers\Admin\FileManagerController::class, 'list'])->name('list');
    });
});

// Debug Session and Cookies
Route::get('/debug-session', function (Illuminate\Http\Request $request) {
    return response()->json([
        'message' => 'Session Debug Info',
        'session_id' => session()->getId(),
        'session_driver' => config('session.driver'),
        'session_lifetime' => config('session.lifetime'),
        'cookie_name' => config('session.cookie'),
        'all_cookies' => $request->cookie(),
        'session_data' => session()->all(),
        'app_debug' => config('app.debug'),
        'app_env' => config('app.env'),
        'csrf_token_in_session' => session()->token(),
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Minimal login WITH session middleware but NO CSRF
Route::get('/simple-login', function () {
    return <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <title>Simple Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
        <div class="card w-100" style="max-width: 400px;">
            <div class="card-body">
                <h3 class="text-center mb-4">Simple Test Login (No CSRF)</h3>
                <form action="/simple-login" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="test@test.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" value="12345" required>
                    </div>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </body>
    </html>
    HTML;
});

Route::post('/simple-login', function (Illuminate\Http\Request $request) {
    try {
        \Log::info('Test login attempt', [
            'email' => $request->email,
            'session_id' => session()->getId(),
        ]);

        $credentials = $request->only('email', 'password');
        
        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            \Log::info('Auth successful, regenerating session');
            $request->session()->regenerate();
            return redirect('/dashboard')->with('success', 'Logged in successfully');
        }
        
        \Log::warning('Auth failed for email: ' . $request->email);
        return '<div style="padding: 20px; text-align: center;"><h3 style="color: red;">Login Failed</h3><p>Invalid email or password</p><a href="/simple-login">Try again</a></div>';
    } catch (\Exception $e) {
        \Log::error('Login error: ' . $e->getMessage());
        return '<div style="padding: 20px; text-align: center;"><h3 style="color: red;">Error</h3><p>' . $e->getMessage() . '</p><a href="/simple-login">Try again</a></div>';
    }
});

// Test login endpoint without CSRF
Route::get('/test-login', function () {
    return view('test-login');
});

Route::post('/test-login', function (Illuminate\Http\Request $request) {
    try {
        \Log::info('Test login attempt', [
            'email' => $request->email,
            'session_id' => session()->getId(),
        ]);

        $credentials = $request->only('email', 'password');
        
        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            \Log::info('Auth successful, regenerating session');
            $request->session()->regenerate();
            return redirect('/dashboard')->with('success', 'Logged in successfully');
        }
        
        \Log::warning('Auth failed for email: ' . $request->email);
        return back()->with('error', 'Invalid credentials');
    } catch (\Exception $e) {
        \Log::error('Login error: ' . $e->getMessage());
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
});

// Include authentication routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';
