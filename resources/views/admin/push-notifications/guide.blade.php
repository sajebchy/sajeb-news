<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-bell"></i> পুশ নোটিফিকেশন গাইড
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h5 class="mb-3">স্বয়ংক্রিয় পুশ নোটিফিকেশন</h5>
                            <p class="text-muted">যখন আপনি একটি নিউজ প্রকাশ করেন, সিস্টেম স্বয়ংক্রিয়ভাবে সমস্ত সাবস্ক্রাইবারদের ব্রাউজারে পুশ নোটিফিকেশন পাঠায়।</p>
                            
                            <div class="alert alert-info mb-4">
                                <strong>✓ সুবিধা:</strong>
                                <ul class="ms-3 mb-0">
                                    <li>নতুন নিউজ প্রকাশিত হলে তাৎক্ষণিক নোটিফিকেশন</li>
                                    <li>সাবস্ক্রাইবাররা নোটিফিকেশন ক্লিক করে সরাসরি নিউজে যায়</li>
                                    <li>কোনো ম্যানুয়াল কাজের প্রয়োজন নেই</li>
                                </ul>
                            </div>

                            <h5 class="mb-3">ম্যানুয়াল পুশ পাঠানো</h5>
                            <p class="text-muted">যদি আপনি কোনো পুরনো নিউজের জন্য পুশ পাঠাতে চান, নিম্নোক্ত কমান্ড চালান:</p>
                            
                            <div class="bg-dark p-3 rounded mb-3 text-white font-monospace">
                                <code>php artisan notifications:send-push {news_id}</code>
                            </div>

                            <p><strong>উদাহরণ:</strong></p>
                            <div class="bg-dark p-3 rounded mb-3 text-white font-monospace">
                                <code>php artisan notifications:send-push 5</code>
                            </div>

                            <p class="text-muted small">এটি ID 5 নম্বর নিউজের জন্য সমস্ত সাবস্ক্রাইবারদের কাছে পুশ পাঠাবে।</p>

                            <hr class="my-4">

                            <h5 class="mb-3">কিভাবে কাজ করে</h5>
                            <ol>
                                <li><strong>সাবস্ক্রাইবার রেজিস্ট্রেশন:</strong> যখন পাঠক "সাবস্ক্রাইব করুন" বাটন ক্লিক করে, তাদের ব্রাউজার সার্ভারে রেজিস্ট্রার হয়</li>
                                <li><strong>নিউজ প্রকাশ:</strong> আপনি একটি নিউজ প্রকাশ করেন এবং "Publish" বাটন ক্লিক করেন</li>
                                <li><strong>স্বয়ংক্রিয় ট্রিগার:</strong> সিস্টেম স্বয়ংক্রিয়ভাবে সনাক্ত করে যে নিউজ প্রকাশিত হয়েছে</li>
                                <li><strong>সাবস্ক্রাইবার নোটিফিকেশন:</strong> সমস্ত সক্রিয় সাবস্ক্রাইবারদের কাছে পুশ নোটিফিকেশন পাঠানো হয়</li>
                                <li><strong>ক্লিক এবং পড়ুন:</strong> পাঠক নোটিফিকেশন ক্লিক করে নিউজ পড়েন</li>
                            </ol>

                            <hr class="my-4">

                            <h5 class="mb-3">সাবস্ক্রাইবার পরিসংখ্যান</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h3 class="text-primary">{{ \App\Models\PushSubscription::count() }}</h3>
                                            <p class="text-muted mb-0">মোট সাবস্ক্রাইবার</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h3 class="text-success">{{ \App\Models\PushSubscription::where('is_active', true)->count() }}</h3>
                                            <p class="text-muted mb-0">সক্রিয় সাবস্ক্রাইবার</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body">
                                            <h3 class="text-warning">{{ \App\Models\PushSubscription::where('is_active', false)->count() }}</h3>
                                            <p class="text-muted mb-0">নিষ্ক্রিয় সাবস্ক্রাইবার</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-lightbulb"></i> টিপস</h6>
                                </div>
                                <div class="card-body small">
                                    <ul class="ps-3 mb-0">
                                        <li>নতুন নিউজ সবসময় স্বয়ংক্রিয়ভাবে পুশ পায়</li>
                                        <li>শুধুমাত্র "প্রকাশিত" স্ট্যাটাসের নিউজের জন্য পুশ পাঠায়</li>
                                        <li>সাবস্ক্রাইবাররা যেকোনো সময় unsubscribe করতে পারে</li>
                                        <li>Feedify integration সক্ষম করলে নোটিফিকেশন আরও একটি চ্যানেলে পাঠায়</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card bg-info bg-opacity-10 mb-3">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-check-circle"></i> প্রস্তুতি</h6>
                                </div>
                                <div class="card-body small">
                                    @php
                                        $settings = \App\Models\SeoSetting::first();
                                        $vapidConfigured = $settings && $settings->vapid_public_key && $settings->vapid_private_key;
                                    @endphp

                                    @if($vapidConfigured)
                                        <div class="alert alert-success mb-0">
                                            <i class="fas fa-check-circle"></i> VAPID Keys সকল সংরক্ষিত
                                        </div>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-triangle"></i> 
                                            <a href="{{ route('admin.settings.index') }}" class="text-warning fw-bold">
                                                VAPID Keys কনফিগার করুন
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-link"></i> দরকারী লিঙ্ক</h6>
                                </div>
                                <div class="card-body small">
                                    <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-outline-primary w-100 mb-2">
                                        <i class="fas fa-cog"></i> সেটিংস
                                    </a>
                                    <a href="https://web.dev/push-notifications/" target="_blank" class="btn btn-sm btn-outline-secondary w-100">
                                        <i class="fas fa-book"></i> ডক্স
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
