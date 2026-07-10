<?php
$basePath = __DIR__ . '/sajeb-news';
if (!file_exists($basePath . '/vendor/autoload.php')) {
    $basePath = __DIR__ . '/../sajeb-news';
}
require $basePath . '/vendor/autoload.php';
$app = require_once $basePath . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "<pre>";
echo "=== Push Notifications Migration ===\n\n";

// 1. Create push_subscriptions table
if (!Schema::hasTable('push_subscriptions')) {
    Schema::create('push_subscriptions', function (Blueprint $table) {
        $table->id();
        $table->text('endpoint');
        $table->text('public_key');
        $table->text('auth_token');
        $table->string('user_ip')->nullable();
        $table->string('user_agent')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
        $table->index('is_active');
        $table->index('created_at');
    });
    echo "✓ push_subscriptions table created\n";
} else {
    echo "- push_subscriptions table already exists\n";
}

// 2. Create push_notifications table
if (!Schema::hasTable('push_notifications')) {
    Schema::create('push_notifications', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('body')->nullable();
        $table->string('image')->nullable();
        $table->string('icon')->nullable();
        $table->string('action_url')->nullable();
        $table->string('target_audience')->default('all');
        $table->json('segments')->nullable();
        $table->timestamp('scheduled_at')->nullable();
        $table->boolean('is_sent')->default(false);
        $table->unsignedInteger('sent_count')->default(0);
        $table->unsignedInteger('click_count')->default(0);
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
    echo "✓ push_notifications table created\n";
} else {
    echo "- push_notifications table already exists\n";
}

// 3. Add VAPID columns to seo_settings
if (Schema::hasTable('seo_settings')) {
    if (!Schema::hasColumn('seo_settings', 'vapid_public_key')) {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->text('vapid_public_key')->nullable();
        });
        echo "✓ vapid_public_key column added\n";
    } else {
        echo "- vapid_public_key column already exists\n";
    }

    if (!Schema::hasColumn('seo_settings', 'vapid_private_key')) {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->text('vapid_private_key')->nullable();
        });
        echo "✓ vapid_private_key column added\n";
    } else {
        echo "- vapid_private_key column already exists\n";
    }

    if (!Schema::hasColumn('seo_settings', 'push_notifications_enabled')) {
        Schema::table('seo_settings', function (Blueprint $table) {
            $table->boolean('push_notifications_enabled')->default(false);
        });
        echo "✓ push_notifications_enabled column added\n";
    } else {
        echo "- push_notifications_enabled column already exists\n";
    }
} else {
    echo "✗ seo_settings table not found!\n";
}

echo "\n=== Migration Complete ===\n";
echo "\n⚠ DELETE THIS FILE after running!\n";
echo "</pre>";
