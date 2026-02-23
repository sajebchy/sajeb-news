<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Advertisement;

// Find all ads with SVG placeholders and clear them
$ads = Advertisement::where('image_url', 'like', 'data:image/svg%')->get();
echo "Found " . $ads->count() . " ads with SVG placeholders\n";
foreach ($ads as $ad) {
    echo "Clearing SVG for: {$ad->name}\n";
    $ad->update(['image_url' => null]);
}

// Show all ads
echo "\n=== All Advertisements ===\n";
$allAds = Advertisement::select('id', 'name', 'placement', 'is_active', 'image_url')->get();
foreach ($allAds as $ad) {
    $status = $ad->is_active ? '✓ Active' : '✗ Inactive';
    $image = $ad->image_url ? '📷 ' . substr($ad->image_url, 0, 40) . '...' : '❌ No Image';
    echo "{$ad->id} | {$ad->name} | {$status} | {$image}\n";
}
echo "\nDatabase cleaned!\n";
