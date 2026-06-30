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
}
