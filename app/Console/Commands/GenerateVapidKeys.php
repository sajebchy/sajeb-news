<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateVapidKeys extends Command
{
    protected $signature = 'vapid:generate {--save : Save keys directly to SeoSettings}';
    protected $description = 'Generate VAPID keys for Web Push Notifications';

    public function handle()
    {
        $this->info('Generating VAPID keys...');

        try {
            $opensslConf = null;
            foreach ([
                getenv('OPENSSL_CONF'),
                PHP_BINDIR . '/extras/ssl/openssl.cnf',
                PHP_BINDIR . '/../extras/ssl/openssl.cnf',
                'C:/laragon/bin/php/php-8.3.30-Win32-vs16-x64/extras/ssl/openssl.cnf',
            ] as $path) {
                if ($path && file_exists($path)) {
                    $opensslConf = $path;
                    break;
                }
            }

            $config = [
                'curve_name' => 'prime256v1',
                'private_key_type' => OPENSSL_KEYTYPE_EC,
            ];

            if ($opensslConf) {
                $config['config'] = $opensslConf;
            }

            $key = openssl_pkey_new($config);

            if (!$key) {
                throw new \Exception('OpenSSL EC key generation failed: ' . openssl_error_string());
            }

            $details = openssl_pkey_get_details($key);

            $publicKeyRaw = chr(4) . $details['ec']['x'] . $details['ec']['y'];
            $publicKey = rtrim(strtr(base64_encode($publicKeyRaw), '+/', '-_'), '=');
            $privateKey = rtrim(strtr(base64_encode($details['ec']['d']), '+/', '-_'), '=');

            $this->newLine();
            $this->info('VAPID Keys Generated Successfully!');
            $this->newLine();
            $this->line('Public Key:  ' . $publicKey);
            $this->line('Private Key: ' . $privateKey);
            $this->newLine();

            if ($this->option('save')) {
                $settings = \App\Models\SeoSetting::first();
                if ($settings) {
                    $settings->update([
                        'vapid_public_key' => $publicKey,
                        'vapid_private_key' => $privateKey,
                        'push_notifications_enabled' => true,
                    ]);
                    $this->info('Keys saved to SeoSettings and push notifications enabled.');
                } else {
                    $this->warn('No SeoSettings record found.');
                }
            } else {
                $this->line('Run with --save to store keys automatically.');
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
