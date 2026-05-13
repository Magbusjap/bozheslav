<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use GeoIp2\Database\Reader;

class TrackVisit
{
    public function handle(Request $request, Closure $next)
    {
        // Skip admin panel, API, static files
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        try {
            $ip = $request->ip();
            $country = null;
            $countryName = null;
            $city = null;
            $lat = null;
            $lon = null;

            // GeoIP lookup
            $dbPath = storage_path('geoip/GeoLite2-City.mmdb');
            if (file_exists($dbPath) && $ip !== '127.0.0.1') {
                $reader = new Reader($dbPath);
                $record = $reader->city($ip);
                $country = $record->country->isoCode;
                $countryName = $record->country->name;
                $city = $record->city->name;
                $lat = $record->location->latitude;
                $lon = $record->location->longitude;
                $reader->close();
            }

            // Parse User-Agent
            $ua = $request->userAgent() ?? '';
            $deviceType = $this->detectDevice($ua);
            $browser = $this->detectBrowser($ua);
            $os = $this->detectOs($ua);

            Visit::create([
                'url'          => $request->path(),
                'ip'           => $ip,
                'country'      => $country,
                'country_name' => $countryName,
                'city'         => $city,
                'latitude'     => $lat,
                'longitude'    => $lon,
                'user_agent'   => substr($ua, 0, 255),
                'referer'      => $request->header('referer'),
                'device_type'  => $deviceType,
                'browser'      => $browser,
                'os'           => $os,
            ]);
        } catch (\Exception $e) {
            // not break the website because of an analytics error
        }

        return $next($request);
    }

    private function shouldSkip(Request $request): bool
    {
        $path = $request->path();

        // Skip Livewire AJAX requests
        if ($request->header('X-Livewire')) return true;
        if (str_starts_with($path, 'livewire')) return true;

        // Skip trusted IPs (configured in Monitoring section)
        $trustedIps = json_decode(\App\Models\Option::get('trusted_ips', '[]'), true) ?? [];
        if (in_array($request->ip(), $trustedIps)) return true;

        // Skip authenticated users via session
        if ($request->hasSession() && $request->session()->has('password_hash_web')) {
            return true;
        }

        // Skip admin panel, storage, static assets
        foreach (['magbusjap', 'storage', 'icons', 'css', 'js', 'images'] as $skip) {
            if (str_starts_with($path, $skip)) return true;
        }

        // Skip known bots and crawlers
        $ua = strtolower($request->userAgent() ?? '');
        foreach (['bot', 'crawler', 'spider', 'curl', 'wget', 'python'] as $bot) {
            if (str_contains($ua, $bot)) return true;
        }

        return false;
    }

    private function detectDevice(string $ua): string
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android')) return 'mobile';
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) return 'tablet';
        return 'desktop';
    }

    private function detectBrowser(string $ua): string
    {
        if (str_contains($ua, 'Edg/')) return 'Edge';
        if (str_contains($ua, 'YaBrowser')) return 'Яндекс';
        if (str_contains($ua, 'OPR') || str_contains($ua, 'Opera')) return 'Opera';
        if (str_contains($ua, 'Chrome')) return 'Chrome';
        if (str_contains($ua, 'Firefox')) return 'Firefox';
        if (str_contains($ua, 'Safari')) return 'Safari';
        return 'Other';
    }

    private function detectOs(string $ua): string
    {
        if (str_contains($ua, 'Windows')) return 'Windows';
        if (str_contains($ua, 'Mac OS')) return 'macOS';
        if (str_contains($ua, 'Android')) return 'Android';
        if (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) return 'iOS';
        if (str_contains($ua, 'Linux')) return 'Linux';
        return 'Other';
    }
}