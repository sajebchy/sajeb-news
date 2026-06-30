<?php
// Direct cleanup script - run this to fix SVG placeholders
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Advertisement;

// Method 1: Direct SQL update
$count = DB::table('advertisements')
    ->where('image_url', 'like', 'data:image/svg%')
    ->update(['image_url' => null]);

echo "✓ Cleaned up $count advertisements with SVG placeholders\n\n";

// Show remaining ads
$ads = Advertisement::select('id', 'name', 'placement', 'is_active', 'image_url')->limit(5)->get();
echo "Sample of ads in database:\n";
foreach ($ads as $ad) {
    $status = $ad->is_active ? 'Active' : 'Inactive';
    $image = $ad->image_url ? '✓ ' . substr($ad->image_url, 0, 50) : '✗ No image';
    echo "  ID {$ad->id}: {$ad->name} ($status) - {$image}\n";
}

echo "\n✓ Database cleaned successfully!\n";
?>
