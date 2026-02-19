<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Advertisement extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'type',
        'ad_type',
        'ad_source',
        'ad_network',
        'network_config',
        'placement',
        'device_target',
        'image_url',
        'ad_url',
        'alt_text',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'start_date',
        'end_date',
        'is_active',
        'is_adsense_enabled',
        'adsense_code',
        'adsense_slot_id',
        'adsense_publisher_id',
        'views',
        'clicks',
        'target_categories',
        'target_tags',
        'display_order',
        'show_on_mobile',
        'show_on_desktop',
        'daily_impression_limit',
        'max_clicks_per_day',
        'disable_page_limit',
        'minimum_content_length',
        'cpc_amount',
        'cpm_amount',
        'total_spent',
        'advertiser_name',
        'advertiser_email',
        'advertiser_phone',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_adsense_enabled' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'target_categories' => 'json',
        'target_tags' => 'json',
        'network_config' => 'json',
        'show_on_mobile' => 'boolean',
        'show_on_desktop' => 'boolean',
        'cpc_amount' => 'decimal:2',
        'cpm_amount' => 'decimal:2',
        'total_spent' => 'decimal:2',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the full ad URL with UTM parameters
     */
    public function getFullUrlAttribute()
    {
        if (!$this->ad_url) {
            return null;
        }

        $url = $this->ad_url;
        $params = [];

        if ($this->utm_source) $params['utm_source'] = $this->utm_source;
        if ($this->utm_medium) $params['utm_medium'] = $this->utm_medium;
        if ($this->utm_campaign) $params['utm_campaign'] = $this->utm_campaign;
        if ($this->utm_term) $params['utm_term'] = $this->utm_term;
        if ($this->utm_content) $params['utm_content'] = $this->utm_content;

        if (!empty($params)) {
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url .= $separator . http_build_query($params);
        }

        return $url;
    }

    /**
     * Build complete ad URL with tracking
     */
    public function buildTrackedUrl($tracking = [])
    {
        $url = $this->ad_url;
        $params = [
            'utm_source' => $this->utm_source,
            'utm_medium' => $this->utm_medium,
            'utm_campaign' => $this->utm_campaign,
            'utm_term' => $this->utm_term,
            'utm_content' => $this->utm_content,
            'ad_id' => $this->id,
            'ad_slug' => $this->slug,
        ];

        // Merge with additional tracking parameters
        $params = array_merge($params, $tracking);
        $params = array_filter($params);

        if (!empty($params)) {
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url .= $separator . http_build_query($params);
        }

        return $url;
    }

    /**
     * Record a view
     */
    public function recordView()
    {
        $this->increment('views');
    }

    /**
     * Record a click
     */
    public function recordClick()
    {
        $this->increment('clicks');
    }

    /**
     * Scope to filter by placement
     */
    public function scopeByPlacement($query, $placement)
    {
        return $query->where('placement', $placement);
    }

    /**
     * Scope to filter active ads
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope to filter by device
     */
    public function scopeForDevice($query, $device = 'desktop')
    {
        if ($device === 'mobile') {
            return $query->where('show_on_mobile', true);
        } else {
            return $query->where('show_on_desktop', true);
        }
    }

    /**
     * Scope to filter ads for specific placement
     */
    public function scopeForPlacement($query, $placement)
    {
        return $query->byPlacement($placement)->active();
    }

    /**
     * Get CTR (Click Through Rate)
     */
    public function getCtrAttribute()
    {
        if ($this->views == 0) {
            return 0;
        }
        return round(($this->clicks / $this->views) * 100, 2);
    }

    /**
     * Get cost per click (when using CPC)
     */
    public function calculateCosts()
    {
        $costs = [];
        
        if ($this->cpc_amount) {
            $costs['cpc_total'] = $this->clicks * $this->cpc_amount;
        }

        if ($this->cpm_amount) {
            $costs['cpm_total'] = ($this->views / 1000) * $this->cpm_amount;
        }

        return $costs;
    }

    /**
     * Check if this is an AdSense advertisement
     */
    public function isAdSense(): bool
    {
        return $this->ad_type === 'adsense' && $this->is_adsense_enabled && !empty($this->adsense_code);
    }

    /**
     * Get AdSense code with safety checks
     */
    public function getAdSenseCode(): ?string
    {
        if (!$this->isAdSense()) {
            return null;
        }

        // Return the code as is (should be the script tag from AdSense)
        return $this->adsense_code;
    }

    /**
     * Validate AdSense code format
     */
    public function isValidAdSenseCode(): bool
    {
        if (!$this->adsense_code) {
            return false;
        }

        // Check if it contains AdSense script
        return strpos($this->adsense_code, 'adsbygoogle') !== false 
            && strpos($this->adsense_code, 'script') !== false;
    }

    /**
     * Check AdSense policy compliance
     */
    public function checkAdSenseCompliance(): array
    {
        $compliance = [
            'is_compliant' => true,
            'issues' => [],
            'warnings' => []
        ];

        // Check 1: Valid AdSense code
        if ($this->isAdSense() && !$this->isValidAdSenseCode()) {
            $compliance['is_compliant'] = false;
            $compliance['issues'][] = 'Invalid AdSense code format';
        }

        // Check 2: Minimum content length (AdSense requires content)
        if ($this->minimum_content_length > 0) {
            $compliance['warnings'][] = 'Ensure minimum ' . $this->minimum_content_length . ' words content';
        }

        // Check 3: Ad density (max ads per page)
        if ($this->disable_page_limit > 3) {
            $compliance['warnings'][] = 'AdSense allows max 3 ads per page';
        }

        return $compliance;
    }

    /**
     * Get AdSense policy guidelines
     */
    public static function getAdSensePolicies(): array
    {
        return [
            'max_ads_per_page' => 3,
            'min_content_length' => 300,
            'prohibited_content' => [
                'adult_content',
                'violence',
                'hate_speech',
                'copyright_infringement',
                'dangerous_products'
            ],
            'must_have' => [
                'privacy_policy',
                'clear_content',
                'original_content',
                'fast_loading_pages'
            ]
        ];
    }

    /**
     * Scope for AdSense ads
     */
    public function scopeAdSenseOnly($query)
    {
        return $query->where('ad_type', 'adsense')
            ->where('is_adsense_enabled', true)
            ->active();
    }

    /**
     * Get list of all supported ad networks
     */
    public static function getSupportedNetworks(): array
    {
        return [
            'adsense' => 'Google AdSense',
            'media_net' => 'Media.net',
            'ezoic' => 'Ezoic',
            'propeller_ads' => 'PropellerAds',
            'mediavine' => 'Mediavine',
            'raptive' => 'Raptive',
            'monumetric' => 'Monumetric',
            'adsterra' => 'Adsterra',
            'monetag' => 'Monetag',
            'infolinks' => 'Infolinks',
            'taboola_outbrain' => 'Taboola/Outbrain',
            'amazon_associates' => 'Amazon Associates',
        ];
    }

    /**
     * Get required fields for each ad network
     */
    public static function getNetworkFields(): array
    {
        return [
            'adsense' => [
                'code' => 'AdSense Code',
                'slot_id' => 'Ad Slot ID',
                'publisher_id' => 'Publisher ID (pub-XXXXXXXXXXXXXXXX)',
            ],
            'media_net' => [
                'code' => 'Media.net Script Code',
                'zip_id' => 'ZIP ID',
            ],
            'ezoic' => [
                'namespace' => 'Ezoic Namespace',
                'zone_id' => 'Zone ID',
            ],
            'propeller_ads' => [
                'zone_id' => 'PropellerAds Zone ID',
            ],
            'mediavine' => [
                'site_id' => 'Mediavine Site ID',
            ],
            'raptive' => [
                'site_id' => 'Raptive Site ID',
            ],
            'monumetric' => [
                'site_id' => 'Monumetric Site ID',
            ],
            'adsterra' => [
                'zone_id' => 'Adsterra Zone ID',
                'code' => 'Adsterra Script Code',
            ],
            'monetag' => [
                'zone_id' => 'Monetag Zone ID',
            ],
            'infolinks' => [
                'site_id' => 'Infolinks Site ID',
                'code' => 'Infolinks Script Code',
            ],
            'taboola_outbrain' => [
                'container_id' => 'Container ID',
                'placement_id' => 'Placement ID',
                'code' => 'Script Code',
            ],
            'amazon_associates' => [
                'store_id' => 'Amazon Store ID',
                'ad_unit_id' => 'Ad Unit ID',
            ],
        ];
    }

    /**
     * Check if ad is using a specific network
     */
    public function isNetwork(string $network): bool
    {
        return $this->ad_network === $network && $this->is_active;
    }

    /**
     * Get network configuration
     */
    public function getNetworkConfig(?string $key = null): mixed
    {
        $config = $this->network_config ?? [];
        return $key ? ($config[$key] ?? null) : $config;
    }

    /**
     * Set network configuration
     */
    public function setNetworkConfig(string $key, mixed $value): self
    {
        $config = $this->network_config ?? [];
        $config[$key] = $value;
        $this->network_config = $config;
        return $this;
    }

    /**
     * Validate network configuration
     */
    public function isNetworkConfigValid(): bool
    {
        if (!$this->ad_network || !$this->network_config) {
            return false;
        }

        $requiredFields = self::getNetworkFields()[$this->ad_network] ?? [];
        foreach (array_keys($requiredFields) as $field) {
            if (empty($this->network_config[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Scope to filter by ad network
     */
    public function scopeByNetwork($query, string $network)
    {
        return $query->where('ad_network', $network)->active();
    }

    /**
     * Get all active ads for a specific network
     */
    public static function getByNetwork(string $network): \Illuminate\Database\Eloquent\Collection
    {
        return self::byNetwork($network)->get();
    }

    /**
     * Get validation errors for network config
     */
    public function getNetworkConfigErrors(): array
    {
        $errors = [];
        if (!$this->ad_network) {
            $errors[] = 'Network is required';
            return $errors;
        }

        if (!$this->network_config) {
            $errors[] = 'Network configuration is missing';
            return $errors;
        }

        $requiredFields = self::getNetworkFields()[$this->ad_network] ?? [];
        foreach ($requiredFields as $field => $label) {
            if (empty($this->network_config[$field])) {
                $errors[] = "$label is required for {$this->ad_network}";
            }
        }

        return $errors;
    }
}
