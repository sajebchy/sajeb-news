<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $seoSettings = SeoSetting::first() ?? SeoSetting::create([
            'site_title' => config('app.name', 'Sajeb NEWS'),
            'site_name' => config('app.name', 'Sajeb NEWS'),
            'site_description' => 'Bengali News Portal',
        ]);
        
        $schemaSettings = \App\Models\SchemaSetting::getInstance();

        return view('admin.settings.index', [
            'seoSettings' => $seoSettings,
            'settings' => $seoSettings->toArray(),
            'schemaSettings' => $schemaSettings,
        ]);
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_url' => 'nullable|url',
            'site_title' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
            'about_page_content' => 'nullable|string',
            'logo' => 'nullable|image|max:5120',
            'mobile_logo' => 'nullable|image|max:5120',
            'og_image' => 'nullable|image|max:5120',
            'favicon' => 'nullable|image|max:1024',
            'google_analytics_id' => 'nullable|string',
            'google_tag_manager_id' => 'nullable|string',
            'ga_tracking_id' => 'nullable|string',
            'gtm_tracking_id' => 'nullable|string',
            'facebook_pixel_id' => 'nullable|string',
            'enable_analytics' => 'nullable|boolean',
            'robots_txt' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            // Schema settings
            'enable_news_article_schema' => 'nullable|boolean',
            'enable_organization_schema' => 'nullable|boolean',
            'enable_website_schema' => 'nullable|boolean',
            'enable_breadcrumb_schema' => 'nullable|boolean',
            'enable_person_schema' => 'nullable|boolean',
            'enable_image_object_schema' => 'nullable|boolean',
            'enable_video_object_schema' => 'nullable|boolean',
            'enable_live_blog_schema' => 'nullable|boolean',
            'enable_faq_schema' => 'nullable|boolean',
            'enable_job_posting_schema' => 'nullable|boolean',
            'enable_event_schema' => 'nullable|boolean',
            'enable_claim_review_schema' => 'nullable|boolean',
            'organization_name' => 'nullable|string|max:255',
            'organization_description' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'contact_type' => 'nullable|string|max:100',
            // reCAPTCHA settings
            'recaptcha_site_key' => 'nullable|string|max:255',
            'recaptcha_secret_key' => 'nullable|string|max:255',
            'recaptcha_threshold' => 'nullable|numeric|min:0|max:1',
            'recaptcha_enabled' => 'nullable|boolean',
            // Push Notifications settings
            'vapid_public_key' => 'nullable|string',
            'vapid_private_key' => 'nullable|string',
            'push_notifications_enabled' => 'nullable|boolean',
        ]);

        // Handle PC logo upload with optimization
        if ($request->hasFile('logo')) {
            $optimizer = new ImageOptimizer();
            $validated['logo'] = $optimizer->optimize(
                $request->file('logo'),
                'logo',
                'settings/logos'
            );
        }

        // Handle mobile logo upload with optimization
        if ($request->hasFile('mobile_logo')) {
            $optimizer = new ImageOptimizer();
            $validated['mobile_logo'] = $optimizer->optimize(
                $request->file('mobile_logo'),
                'logo',
                'settings/logos'
            );
        }

        // Handle OG image upload with optimization
        if ($request->hasFile('og_image')) {
            $optimizer = new ImageOptimizer();
            $validated['og_image'] = $optimizer->optimize(
                $request->file('og_image'),
                'og_image',
                'settings'
            );
        }

        // Handle favicon upload with optimization
        if ($request->hasFile('favicon')) {
            $optimizer = new ImageOptimizer();
            $validated['favicon'] = $optimizer->optimize(
                $request->file('favicon'),
                'favicon',
                'settings'
            );
        }

        // Separate schema settings from SEO settings
        $schemaSettingsData = [];
        $seoSettingsData = [];
        
        foreach ($validated as $key => $value) {
            if (strpos($key, 'enable_') === 0 || in_array($key, ['organization_name', 'organization_description', 'contact_email', 'contact_phone', 'contact_type'])) {
                $schemaSettingsData[$key] = $value;
            } else {
                // Include reCAPTCHA settings in SEO settings
                $seoSettingsData[$key] = $value;
            }
        }

        // Update SEO Settings
        $seoSettings = SeoSetting::first() ?? SeoSetting::create([
            'site_title' => config('app.name', 'Sajeb NEWS'),
            'site_name' => config('app.name', 'Sajeb NEWS'),
            'site_description' => 'Bengali News Portal',
        ]);
        
        $seoSettings->update($seoSettingsData);
        
        // Update Schema Settings
        $schemaSettings = \App\Models\SchemaSetting::getInstance();
        $schemaSettings->update($schemaSettingsData);

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Settings updated successfully!');
    }
}
