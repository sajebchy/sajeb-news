<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Apply the mail (SMTP) configuration that admins set in the database,
        // so real emails (password reset, contact form) are sent without editing
        // server files. Wrapped in try/catch so it stays safe during migrations
        // when the seo_settings table may not exist yet.
        try {
            $this->configureMailFromSettings(\App\Models\SeoSetting::first());
        } catch (\Throwable $e) {
            // Table not ready (e.g. fresh install / migration) — skip silently.
        }

        // Share settings with all views
        view()->composer('*', function ($view) {
            $settings = \App\Models\SeoSetting::first() ?? \App\Models\SeoSetting::create([
                'site_title' => config('app.name', 'Sajeb NEWS'),
                'site_name' => config('app.name', 'Sajeb NEWS'),
                'site_description' => 'Bengali News Portal',
            ]);

            $view->with('settings', $settings);
        });
    }

    /**
     * Override the mail config at runtime from the admin-managed settings.
     * Only takes effect once a host + username have been entered, otherwise
     * the .env defaults remain in force.
     */
    private function configureMailFromSettings(?\App\Models\SeoSetting $settings): void
    {
        if (!$settings || empty($settings->mail_host) || empty($settings->mail_username)) {
            return;
        }

        $encryption = $settings->mail_encryption ?: null;

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $settings->mail_host,
            'mail.mailers.smtp.port' => (int) ($settings->mail_port ?: 587),
            'mail.mailers.smtp.username' => $settings->mail_username,
            'mail.mailers.smtp.password' => $settings->mail_password,
            // Port 465 needs implicit TLS ("smtps"); 587 uses STARTTLS (null scheme).
            'mail.mailers.smtp.scheme' => $encryption === 'ssl' ? 'smtps' : null,
        ]);

        if (!empty($settings->mail_from_address)) {
            config([
                'mail.from.address' => $settings->mail_from_address,
                'mail.from.name' => $settings->mail_from_name ?: ($settings->site_name ?: config('app.name')),
            ]);
        }
    }
}
