<?php
require __DIR__ . '/../sajeb-news/vendor/autoload.php';
$app = require_once __DIR__ . '/../sajeb-news/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<pre>";
echo "=== VAPID Key Generator ===\n\n";

try {
    $key = openssl_pkey_new([
        'curve_name' => 'prime256v1',
        'private_key_type' => OPENSSL_KEYTYPE_EC,
    ]);

    if (!$key) {
        throw new \Exception('OpenSSL EC key generation failed: ' . openssl_error_string());
    }

    $details = openssl_pkey_get_details($key);

    $publicKeyRaw = chr(4) . $details['ec']['x'] . $details['ec']['y'];
    $publicKey = rtrim(strtr(base64_encode($publicKeyRaw), '+/', '-_'), '=');
    $privateKey = rtrim(strtr(base64_encode($details['ec']['d']), '+/', '-_'), '=');

    $settings = \App\Models\SeoSetting::first();
    if ($settings) {
        $settings->update([
            'vapid_public_key' => $publicKey,
            'vapid_private_key' => $privateKey,
            'push_notifications_enabled' => true,
        ]);
        echo "✓ VAPID keys generated and saved!\n\n";
        echo "Public Key:  {$publicKey}\n";
        echo "Private Key: {$privateKey}\n\n";
        echo "Push notifications are now ENABLED.\n";
    } else {
        echo "✗ No SeoSettings record found.\n";
    }
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\n⚠ DELETE THIS FILE immediately after running!\n";
echo "</pre>";
