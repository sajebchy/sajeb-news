<?php

namespace App\Services;

use App\Models\VisitorAnalytic;
use Illuminate\Http\Request;

class VisitorTrackingService
{
    /**
     * Track a visitor's activity on an article
     */
    public function trackVisit(Request $request, $newsId, $data)
    {
        try {
            // Get IP address (handle proxy/cloudflare)
            $ip = $this->getClientIp($request);

            // Get location info (country/city) from IP if available
            $location = $this->getLocationFromIp($ip);

            // Parse user agent
            $userAgent = $request->userAgent() ?? '';
            $device = $this->getDeviceType($userAgent);
            $browser = $this->getBrowser($userAgent);
            $os = $this->getOperatingSystem($userAgent);

            // Create visitor analytics record
            $visitor = VisitorAnalytic::create([
                'news_id' => $newsId,
                'visitor_ip' => $ip,
                'visitor_country' => $location['country'] ?? null,
                'visitor_city' => $location['city'] ?? null,
                'visitor_device' => $device,
                'referrer_source' => $this->getReferrerSource($request),
                'referrer_url' => $request->header('referer'),
                'time_spent_seconds' => $data['time_spent'] ?? 0,
                'scroll_percentage' => $data['scroll_percentage'] ?? 0,
                'completed_reading' => $data['completed_reading'] ?? false,
                'browser' => $browser,
                'os' => $os,
                'visit_date' => now(),
                'user_agent' => $userAgent,
                'language' => $this->getLanguage($request->header('accept-language')),
                'screen_resolution' => $data['screen_resolution'] ?? null,
                'exit_time' => null,
                'exit_page' => null,
                'next_news_id' => null,
            ]);

            return $visitor;
        } catch (\Exception $e) {
            \Log::error('Visitor tracking error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get client IP address
     */
    private function getClientIp(Request $request)
    {
        if (!empty($request->server('HTTP_CF_CONNECTING_IP'))) {
            // Cloudflare
            return $request->server('HTTP_CF_CONNECTING_IP');
        } elseif (!empty($request->server('HTTP_X_FORWARDED_FOR'))) {
            // Proxy
            $ips = explode(',', $request->server('HTTP_X_FORWARDED_FOR'));
            return trim($ips[0]);
        } elseif (!empty($request->server('HTTP_X_FORWARDED'))) {
            return $request->server('HTTP_X_FORWARDED');
        } elseif (!empty($request->server('HTTP_FORWARDED_FOR'))) {
            return $request->server('HTTP_FORWARDED_FOR');
        } elseif (!empty($request->server('HTTP_FORWARDED'))) {
            return $request->server('HTTP_FORWARDED');
        } else {
            return $request->ip();
        }
    }

    /**
     * Get location from IP address (returns country and city)
     * Using ip-api.com free tier (45 per minute) or similar service
     */
    private function getLocationFromIp($ip)
    {
        try {
            // Skip tracking for localhost
            if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
                return ['country' => 'Local', 'city' => 'Localhost'];
            }

            // Use ip-api.com or similar service
            // For free tier, we use ip-api.com without API key (limited to 45 requests/minute)
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country,city");
            
            if ($response) {
                $data = json_decode($response, true);
                return [
                    'country' => $data['country'] ?? null,
                    'city' => $data['city'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('IP geolocation failed: ' . $e->getMessage());
        }

        return ['country' => null, 'city' => null];
    }

    /**
     * Get device type from user agent string
     */
    private function getDeviceType($userAgent)
    {
        $userAgent = strtolower($userAgent);

        if (preg_match('/mobile|android|iphone|ipod|windows phone/i', $userAgent)) {
            return 'Mobile';
        } elseif (preg_match('/tablet|ipad|android/i', $userAgent)) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

    /**
     * Get browser from user agent string
     */
    private function getBrowser($userAgent)
    {
        $browsers = [
            'OPR|Opera' => 'Opera',
            'Edge' => 'Edge',
            'MSIE|Trident' => 'Internet Explorer',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'Firefox' => 'Firefox',
        ];

        foreach ($browsers as $pattern => $name) {
            if (preg_match("/$pattern/i", $userAgent)) {
                return $name;
            }
        }

        return 'Unknown';
    }

    /**
     * Get operating system from user agent string
     */
    private function getOperatingSystem($userAgent)
    {
        $systems = [
            'Windows NT 10.0' => 'Windows 10',
            'Windows NT 6.3' => 'Windows 8.1',
            'Windows NT 6.2' => 'Windows 8',
            'Windows' => 'Windows',
            'iPhone' => 'iOS',
            'iPad' => 'iOS',
            'Mac' => 'macOS',
            'Android' => 'Android',
            'Linux' => 'Linux',
        ];

        foreach ($systems as $pattern => $name) {
            if (preg_match("/$pattern/i", $userAgent)) {
                return $name;
            }
        }

        return 'Unknown';
    }

    /**
     * Get language from accept-language header
     */
    private function getLanguage($acceptLanguage)
    {
        if (!$acceptLanguage) {
            return null;
        }

        // Get primary language code (e.g., 'en' from 'en-US,en;q=0.9')
        $parts = explode(',', $acceptLanguage);
        $primary = trim($parts[0]);
        $lang = explode(';', $primary)[0];
        
        return $lang;
    }

    /**
     * Get referrer source from referrer URL
     */
    private function getReferrerSource($request)
    {
        $referrer = $request->header('referer');

        if (!$referrer) {
            return 'direct';
        }

        $host = parse_url($referrer, PHP_URL_HOST);

        if (!$host) {
            return 'direct';
        }

        // Map common sources
        $sources = [
            'google' => ['google.com', 'google.co'],
            'facebook' => ['facebook.com', 'fb.com', 'm.facebook.com'],
            'twitter' => ['twitter.com', 'x.com', 't.co'],
            'linkedin' => ['linkedin.com'],
            'whatsapp' => ['whatsapp.com', 'wa.me'],
            'bing' => ['bing.com'],
            'chatgpt' => ['chatgpt.com', 'openai.com'],
        ];

        foreach ($sources as $source => $domains) {
            foreach ($domains as $domain) {
                if (stripos($host, $domain) !== false) {
                    return $source;
                }
            }
        }

        return 'other';
    }
}
