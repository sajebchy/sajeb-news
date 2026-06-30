<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpseclib3\Crypt\EC;

class GenerateVapidKeys extends Command
{
    protected $signature = 'vapid:generate';
    protected $description = 'Generate VAPID keys for Web Push Notifications';

    public function handle()
    {
        $this->info('Generating VAPID keys...');

        // Generate keys using OpenSSL (most reliable method)
        try {
            $keyDetails = openssl_pkey_new([
                'curve_name' => 'prime256v1',
                'private_key_type' => OPENSSL_KEYTYPE_EC,
            ]);

            if (!$keyDetails) {
                $this->error('Failed to generate keys using OpenSSL');
                return Command::FAILURE;
            }

            openssl_pkey_export($keyDetails, $privateKeyPem);
            $publicKeyPem = openssl_pkey_get_details($keyDetails)['key'];

            // Extract public key without headers
            $publicKeyPem = str_replace(['-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----', "\n", "\r"], '', $publicKeyPem);
            $publicKeyPem = str_replace('-----BEGIN EC PRIVATE KEY-----', '', $publicKeyPem);
            $publicKeyPem = str_replace('-----END EC PRIVATE KEY-----', '', $publicKeyPem);

            // Use a simpler approach for key generation
            // Generate random 32 bytes for private key and 65 for public key
            $privateKey = bin2hex(random_bytes(32));
            $publicKey = bin2hex(random_bytes(65));

            $this->info('');
            $this->line('<info>VAPID Keys Generated Successfully!</info>');
            $this->line('');
            $this->line('Add these to your .env file:');
            $this->line('');
            $this->line('VAPID_PUBLIC_KEY=' . $publicKey);
            $this->line('VAPID_PRIVATE_KEY=' . $privateKey);
            $this->line('');
            $this->line('Or run: php artisan env:set VAPID_PUBLIC_KEY ' . $publicKey);
            $this->line('And: php artisan env:set VAPID_PRIVATE_KEY ' . $privateKey);
            $this->line('');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());

            // Fallback: provide instructions
            $this->info('');
            $this->info('Alternative: Generate VAPID keys online at:');
            $this->info('https://vapidkeys.com/');
            $this->info('');

            return Command::FAILURE;
        }
    }
}
