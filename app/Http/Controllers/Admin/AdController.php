<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdController extends Controller
{
    /**
     * Display a listing of advertisements.
     */
    public function index()
    {
        $ads = Advertisement::with('creator')
            ->latest()
            ->paginate(15);

        // Get stats
        $totalAds = Advertisement::count();
        $activeAds = Advertisement::active()->count();
        $totalImpressions = Advertisement::sum('views') ?? 0;
        $totalClicks = Advertisement::sum('clicks') ?? 0;

        return view('admin.advertisements.index', [
            'ads' => $ads,
            'totalAds' => $totalAds,
            'activeAds' => $activeAds,
            'totalImpressions' => $totalImpressions,
            'totalClicks' => $totalClicks,
        ]);
    }

    /**
     * Show the form for creating a new advertisement.
     */
    public function create()
    {
        $placements = [
            'header_top' => '1️⃣ Header Top Banner (728x90 / 970x90)',
            'navigation_sticky' => '2️⃣ Navigation Bar Sticky (728x90)',
            'homepage_top' => '3️⃣ Homepage Top (728x90 / Responsive)',
            'sidebar_medium_rectangle' => '4️⃣ Sidebar Medium Rectangle (300x250)',
            'sidebar_half_page' => '4️⃣ Sidebar Half Page (300x600)',
            'article_2nd_paragraph' => '5️⃣ Article 2nd Paragraph (300x250)',
            'article_middle' => '5️⃣ Article Middle (300x250)',
            'article_conclusion' => '5️⃣ Article Before Conclusion (300x250)',
            'below_article' => '6️⃣ Below Article Content (728x90 / 300x250)',
            'footer_banner' => '7️⃣ Footer Banner (728x90)',
            'between_news_listings' => '8️⃣ Between News Listings (300x250)',
            'sticky_sidebar_left' => '9️⃣ Sticky Sidebar Left (160x600 / 300x250)',
            'sticky_sidebar_right' => '9️⃣ Sticky Sidebar Right (160x600 / 300x250)',
            'popup_interstitial' => '🔟 Popup/Interstitial Ads',
        ];

        $types = [
            'banner' => 'Banner',
            'sidebar' => 'Sidebar',
            'inline' => 'Inline',
            'featured' => 'Featured',
            'header' => 'Header',
            'category_page' => 'Category Page',
            'search' => 'Search',
        ];

        $adSources = [
            'offline' => 'Offline Ad (Local Customer)',
            'online' => 'Online Ad (Ad Networks)',
        ];

        $adTypes = [
            'standard' => 'Standard Ad (Image/Text)',
            'image' => 'Image Ad',
            'video' => 'Video Ad',
        ];

        $adNetworks = Advertisement::getSupportedNetworks();
        $networkFields = Advertisement::getNetworkFields();

        $deviceTargets = [
            'all' => 'All Devices',
            'desktop' => 'Desktop Only',
            'mobile' => 'Mobile Only',
        ];

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.advertisements.create', [
            'placements' => $placements,
            'types' => $types,
            'adSources' => $adSources,
            'adTypes' => $adTypes,
            'adNetworks' => $adNetworks,
            'networkFields' => $networkFields,
            'deviceTargets' => $deviceTargets,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created advertisement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'placement' => 'required|in:header_top,navigation_sticky,homepage_top,sidebar_medium_rectangle,sidebar_half_page,article_2nd_paragraph,article_middle,article_conclusion,below_article,footer_banner,between_news_listings,sticky_sidebar_left,sticky_sidebar_right,popup_interstitial',
            'type' => 'required|in:banner,sidebar,inline,featured,header,responsive,sticky,popup',
            'ad_source' => 'required|in:offline,online',
            'ad_type' => 'nullable|in:standard,image,video',
            'image_file' => 'nullable|file|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'image_url' => 'nullable|string|max:500',
            'alt_text' => 'nullable|string|max:255',
            'ad_url' => 'required_if:ad_type,standard,image,video|url',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'device_target' => 'required|in:all,desktop,mobile',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'show_on_mobile' => 'boolean',
            'show_on_desktop' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'daily_impression_limit' => 'nullable|integer|min:1',
            'max_clicks_per_day' => 'nullable|integer|min:1',
            'cpc_amount' => 'nullable|numeric|min:0',
            'cpm_amount' => 'nullable|numeric|min:0',
            'advertiser_name' => 'nullable|string|max:255',
            'advertiser_email' => 'nullable|email',
            'advertiser_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'target_categories' => 'nullable|array',
            'target_tags' => 'nullable|array',
            'is_active' => 'boolean',
            'code' => 'nullable|string',
            'adsense_code' => 'required_if:ad_network,adsense|nullable|string',
            'adsense_slot_id' => 'required_if:ad_network,adsense|nullable|string|max:50',
            'adsense_publisher_id' => 'required_if:ad_network,adsense|nullable|string|regex:/^pub-[0-9]{16}$/',
            'is_adsense_enabled' => 'boolean',
            'ad_network' => 'required_if:ad_source,online|nullable|in:' . implode(',', array_keys(Advertisement::getSupportedNetworks())),
            'network_config' => 'nullable|array',
        ]);

        // Validate AdSense code format
        if ($request->input('ad_network') === 'adsense') {
            if (empty($validated['adsense_code']) || strpos($validated['adsense_code'], 'adsbygoogle') === false) {
                return back()
                    ->withInput()
                    ->with('error', 'Invalid AdSense code. Code must contain Google AdSense script.');
            }
        }

        // Handle ad network configuration
        if (!empty($request->input('ad_network'))) {
            $network = $request->input('ad_network');
            $networkConfig = [];
            
            // Collect network-specific fields from request
            $networkFields = Advertisement::getNetworkFields()[$network] ?? [];
            foreach (array_keys($networkFields) as $field) {
                $value = $request->input("network_{$field}");
                if (!empty($value)) {
                    $networkConfig[$field] = $value;
                }
            }
            
            $validated['network_config'] = $networkConfig;
        }

        // Handle device preferences
        if ($validated['device_target'] === 'mobile') {
            $validated['show_on_mobile'] = true;
            $validated['show_on_desktop'] = false;
        } elseif ($validated['device_target'] === 'desktop') {
            $validated['show_on_mobile'] = false;
            $validated['show_on_desktop'] = true;
        } else {
            $validated['show_on_mobile'] = true;
            $validated['show_on_desktop'] = true;
        }

        // Filter out SVG placeholders - only keep real image paths
        if (!empty($validated['image_url'])) {
            if (strpos($validated['image_url'], 'data:image/svg') === 0) {
                $validated['image_url'] = null;
            }
        }

        // Fallback: If image_url is empty but image_file was uploaded, process it
        if (empty($validated['image_url']) && $request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $uploadDir = public_path('uploads/advertisements');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = time() . '_' . Str::random(10) . '.' . $extension;
            $file->move($uploadDir, $fileName);
            $validated['image_url'] = 'uploads/advertisements/' . $fileName;
        }

        $validated['created_by'] = auth()->id();

        $ad = Advertisement::create($validated);

        // Log activity
        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($ad)
                    ->log('created');
            }
        } catch (\Exception $e) {
            // Activity logging not available
        }

        return redirect()
            ->route('admin.advertisements.show', $ad)
            ->with('success', 'Advertisement created successfully!');
    }

    /**
     * Display the specified advertisement.
     */
    public function show(Advertisement $advertisement)
    {
        return view('admin.advertisements.show', [
            'ad' => $advertisement,
        ]);
    }

    /**
     * Show the form for editing the specified advertisement.
     */
    public function edit(Advertisement $advertisement)
    {
        $placements = [
            'header_top' => '1️⃣ Header Top Banner (728x90 / 970x90)',
            'navigation_sticky' => '2️⃣ Navigation Bar Sticky (728x90)',
            'homepage_top' => '3️⃣ Homepage Top (728x90 / Responsive)',
            'sidebar_medium_rectangle' => '4️⃣ Sidebar Medium Rectangle (300x250)',
            'sidebar_half_page' => '4️⃣ Sidebar Half Page (300x600)',
            'article_2nd_paragraph' => '5️⃣ Article 2nd Paragraph (300x250)',
            'article_middle' => '5️⃣ Article Middle (300x250)',
            'article_conclusion' => '5️⃣ Article Before Conclusion (300x250)',
            'below_article' => '6️⃣ Below Article Content (728x90 / 300x250)',
            'footer_banner' => '7️⃣ Footer Banner (728x90)',
            'between_news_listings' => '8️⃣ Between News Listings (300x250)',
            'sticky_sidebar_left' => '9️⃣ Sticky Sidebar Left (160x600 / 300x250)',
            'sticky_sidebar_right' => '9️⃣ Sticky Sidebar Right (160x600 / 300x250)',
            'popup_interstitial' => '🔟 Popup/Interstitial Ads',
        ];

        $types = [
            'banner' => 'Banner',
            'sidebar' => 'Sidebar',
            'inline' => 'Inline',
            'featured' => 'Featured',
            'header' => 'Header',
            'responsive' => 'Responsive',
            'sticky' => 'Sticky',
            'popup' => 'Popup',
        ];

        $adSources = [
            'offline' => 'Offline Ad (Local Customer)',
            'online' => 'Online Ad (Ad Networks)',
        ];

        $adTypes = [
            'standard' => 'Standard Ad (Image/Text)',
            'image' => 'Image Ad',
            'video' => 'Video Ad',
        ];

        $deviceTargets = [
            'all' => 'All Devices',
            'desktop' => 'Desktop Only',
            'mobile' => 'Mobile Only',
        ];

        $adNetworks = Advertisement::getSupportedNetworks();
        $networkFields = Advertisement::getNetworkFields();

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.advertisements.edit', [
            'ad' => $advertisement,
            'placements' => $placements,
            'types' => $types,
            'adSources' => $adSources,
            'adTypes' => $adTypes,
            'adNetworks' => $adNetworks,
            'networkFields' => $networkFields,
            'deviceTargets' => $deviceTargets,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }

    /**
     * Update the specified advertisement in storage.
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'placement' => 'required|in:header_top,navigation_sticky,homepage_top,sidebar_medium_rectangle,sidebar_half_page,article_2nd_paragraph,article_middle,article_conclusion,below_article,footer_banner,between_news_listings,sticky_sidebar_left,sticky_sidebar_right,popup_interstitial',
            'type' => 'required|in:banner,sidebar,inline,featured,header,responsive,sticky,popup',
            'ad_source' => 'required|in:offline,online',
            'ad_type' => 'nullable|in:standard,image,video',
            'image_file' => 'nullable|file|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'image_url' => 'nullable|string|max:500',
            'alt_text' => 'nullable|string|max:255',
            'ad_url' => 'required_if:ad_type,standard,image,video|url',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'device_target' => 'required|in:all,desktop,mobile',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'show_on_mobile' => 'boolean',
            'show_on_desktop' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'daily_impression_limit' => 'nullable|integer|min:1',
            'max_clicks_per_day' => 'nullable|integer|min:1',
            'cpc_amount' => 'nullable|numeric|min:0',
            'cpm_amount' => 'nullable|numeric|min:0',
            'advertiser_name' => 'nullable|string|max:255',
            'advertiser_email' => 'nullable|email',
            'advertiser_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'target_categories' => 'nullable|array',
            'target_tags' => 'nullable|array',
            'is_active' => 'boolean',
            'code' => 'nullable|string',
            'adsense_code' => 'required_if:ad_type,adsense|nullable|string',
            'adsense_slot_id' => 'required_if:ad_type,adsense|nullable|string|max:50',
            'adsense_publisher_id' => 'required_if:ad_type,adsense|nullable|string|regex:/^pub-[0-9]{16}$/',
            'is_adsense_enabled' => 'boolean',
            'ad_network' => 'required_if:ad_source,online|nullable|in:' . implode(',', array_keys(Advertisement::getSupportedNetworks())),
            'network_config' => 'nullable|array',
        ]);

        // Validate AdSense code format
        if ($request->input('ad_network') === 'adsense') {
            if (empty($validated['adsense_code']) || strpos($validated['adsense_code'], 'adsbygoogle') === false) {
                return back()
                    ->withInput()
                    ->with('error', 'Invalid AdSense code. Code must contain Google AdSense script.');
            }
        }

        // Handle ad network configuration
        if (!empty($request->input('ad_network'))) {
            $network = $request->input('ad_network');
            $networkConfig = [];
            
            // Collect network-specific fields from request
            $networkFields = Advertisement::getNetworkFields()[$network] ?? [];
            foreach (array_keys($networkFields) as $field) {
                $value = $request->input("network_{$field}");
                if (!empty($value)) {
                    $networkConfig[$field] = $value;
                }
            }
            
            $validated['network_config'] = $networkConfig;
        }

        // Handle device preferences
        if ($validated['device_target'] === 'mobile') {
            $validated['show_on_mobile'] = true;
            $validated['show_on_desktop'] = false;
        } elseif ($validated['device_target'] === 'desktop') {
            $validated['show_on_mobile'] = false;
            $validated['show_on_desktop'] = true;
        } else {
            $validated['show_on_mobile'] = true;
            $validated['show_on_desktop'] = true;
        }

        // Filter out SVG placeholders - only keep real image paths
        if (!empty($validated['image_url'])) {
            if (strpos($validated['image_url'], 'data:image/svg') === 0) {
                unset($validated['image_url']);
            }
        } elseif (empty($validated['image_url'] ?? null)) {
            // Preserve existing image_url if no new image was uploaded
            unset($validated['image_url']);
        }

        // Fallback: If image_url was not set and image_file was uploaded, process it
        if (!isset($validated['image_url']) && $request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $uploadDir = public_path('uploads/advertisements');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = time() . '_' . Str::random(10) . '.' . $extension;
            $file->move($uploadDir, $fileName);
            $validated['image_url'] = 'uploads/advertisements/' . $fileName;
        }

        $advertisement->update($validated);

        // Log activity
        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($advertisement)
                    ->log('updated');
            }
        } catch (\Exception $e) {
            // Activity logging not available
        }

        return redirect()
            ->route('admin.advertisements.show', $advertisement)
            ->with('success', 'Advertisement updated successfully!');
    }

    /**
     * Remove the specified advertisement from storage.
     */
    public function destroy(Advertisement $advertisement)
    {
        // Log activity before deletion
        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($advertisement)
                    ->log('deleted');
            }
        } catch (\Exception $e) {
            // Activity logging not available
        }

        $advertisement->delete();

        return redirect()
            ->route('admin.advertisements.index')
            ->with('success', 'Advertisement deleted successfully!');
    }

    /**
     * Toggle advertisement status
     */
    public function toggleStatus(Advertisement $advertisement)
    {
        $advertisement->update([
            'is_active' => !$advertisement->is_active
        ]);

        $status = $advertisement->is_active ? 'activated' : 'deactivated';

        // Log activity
        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy(auth()->user())
                    ->performedOn($advertisement)
                    ->log($status);
            }
        } catch (\Exception $e) {
            // Activity logging not available
        }

        return back()->with('success', 'Advertisement ' . $status . ' successfully!');
    }

    /**
     * Get ad statistics
     */
    public function statistics(Advertisement $advertisement)
    {
        return view('admin.advertisements.statistics', [
            'ad' => $advertisement,
        ]);
    }

    /**
     * Export advertisements
     */
    public function export()
    {
        $ads = Advertisement::with('creator')->get();

        $data = $ads->map(function ($ad) {
            return [
                'ID' => $ad->id,
                'Name' => $ad->name,
                'Placement' => $ad->placement,
                'Type' => $ad->type,
                'Status' => $ad->is_active ? 'Active' : 'Inactive',
                'Start Date' => $ad->start_date ? $ad->start_date->format('Y-m-d') : 'N/A',
                'End Date' => $ad->end_date ? $ad->end_date->format('Y-m-d') : 'N/A',
                'Views' => $ad->views,
                'Clicks' => $ad->clicks,
                'CTR' => $ad->ctr . '%',
                'Created By' => $ad->creator->name ?? 'N/A',
            ];
        });

        $fileName = 'advertisements_' . date('Y-m-d_H-i-s') . '.csv';
        
        $fp = fopen('php://output', 'w');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Type: text/csv');

        // Write header
        if (!empty($data)) {
            fputcsv($fp, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($fp, $row);
            }
        }

        fclose($fp);
        exit;
    }

    /**
     * Upload advertisement image via AJAX
     */
    public function uploadAdvertisementImage(Request $request)
    {
        try {
            \Log::info('Upload request received', ['files' => $request->allFiles()]);
            
            // Validate with just the 'image' rule
            $validated = $request->validate([
                'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp,bmp|max:5120'
            ]);

            $file = $request->file('image');
            \Log::info('File validated', [
                'filename' => $file->getClientOriginalName(), 
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension()
            ]);
            
            // Create uploads directory if it doesn't exist
            $uploadDir = public_path('uploads/advertisements');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique filename - use original extension
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = time() . '_' . Str::random(10) . '.' . $extension;
            
            // Store the file
            $fullPath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;
            $file->move($uploadDir, $fileName);
            
            // Verify file was actually created
            if (!file_exists($fullPath)) {
                throw new \Exception('File was not saved to disk');
            }
            
            \Log::info('File moved to uploads', ['path' => $fullPath, 'size' => filesize($fullPath)]);

            // Return the relative path for storage (without leading /)
            $imagePath = 'uploads/advertisements/' . $fileName;

            return response()->json([
                'success' => true,
                'url' => $imagePath,
                'message' => 'Image uploaded successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $errorMsg = implode(', ', array_merge(...array_values($errors)));
            \Log::error('Validation error on upload', ['errors' => $errors]);
            return response()->json([
                'success' => false,
                'message' => 'File validation failed: ' . $errorMsg
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Upload exception', ['message' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ], 400);
        }
    }
}
