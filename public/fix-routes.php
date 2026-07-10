<?php
$basePath = __DIR__ . '/sajeb-news';
if (!file_exists($basePath . '/vendor/autoload.php')) {
    $basePath = __DIR__ . '/../sajeb-news';
}
if (!file_exists($basePath . '/vendor/autoload.php')) {
    $basePath = __DIR__ . '/..';
}

echo "<pre>";
echo "=== Route & Cache Diagnostic ===\n\n";

echo "Base path: " . realpath($basePath) . "\n";
echo "PHP version: " . phpversion() . "\n\n";

// 1. Clear all cache files
$cacheDir = $basePath . '/bootstrap/cache';
echo "--- Clearing Cache ---\n";
$cacheFiles = [
    'config.php',
    'routes-v7.php',
    'routes.php',
    'services.php',
    'packages.php',
    'events.php',
];
foreach ($cacheFiles as $file) {
    $path = $cacheDir . '/' . $file;
    if (file_exists($path)) {
        unlink($path);
        echo "✓ Deleted: bootstrap/cache/{$file}\n";
    } else {
        echo "- Not found: bootstrap/cache/{$file}\n";
    }
}

// Also clear compiled views
$viewsDir = $basePath . '/storage/framework/views';
if (is_dir($viewsDir)) {
    $count = 0;
    foreach (glob($viewsDir . '/*.php') as $viewFile) {
        unlink($viewFile);
        $count++;
    }
    echo "✓ Cleared {$count} compiled views\n";
}

echo "\n--- Checking Category 'national' ---\n";

try {
    require $basePath . '/vendor/autoload.php';
    $app = require_once $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $cat = \App\Models\Category::where('slug', 'national')->first();
    if ($cat) {
        echo "✓ Category found: ID={$cat->id}, Name={$cat->name}, Slug={$cat->slug}\n";
        echo "  Edit URL should be: /admin/categories/{$cat->slug}/edit\n";
    } else {
        echo "✗ Category with slug 'national' NOT FOUND!\n";
        echo "\nAll categories:\n";
        $cats = \Illuminate\Support\Facades\DB::table('categories')->get(['id', 'name', 'slug']);
        foreach ($cats as $c) {
            echo "  ID={$c->id} | {$c->name} | slug={$c->slug}\n";
        }
    }
} catch (\Throwable $e) {
    echo "✗ Laravel bootstrap error: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";

    // Fallback: direct PDO
    echo "\n--- Trying direct DB connection ---\n";
    try {
        $envFile = $basePath . '/.env';
        if (file_exists($envFile)) {
            $env = parse_ini_file($envFile);
            $host = $env['DB_HOST'] ?? '127.0.0.1';
            $db = $env['DB_DATABASE'] ?? '';
            $user = $env['DB_USERNAME'] ?? '';
            $pass = $env['DB_PASSWORD'] ?? '';

            $pdo = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $pass);
            $stmt = $pdo->query("SELECT id, name, slug FROM categories ORDER BY id");
            echo "Categories in database:\n";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "  ID={$row['id']} | {$row['name']} | slug={$row['slug']}\n";
            }
        } else {
            echo "✗ .env file not found at: {$envFile}\n";
        }
    } catch (\Exception $dbErr) {
        echo "✗ DB error: " . $dbErr->getMessage() . "\n";
    }
}

echo "\n=== Done ===\n";
echo "\n⚠ DELETE THIS FILE immediately after running!\n";
echo "</pre>";
