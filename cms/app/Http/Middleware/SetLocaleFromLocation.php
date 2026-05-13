<?php

namespace App\Http\Middleware;

use Closure;
use GeoIp2\Database\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromLocation
{
    private const SUPPORTED = ['ru', 'en', 'sr'];
    private const RUSSIAN_COUNTRIES = ['RU', 'KZ', 'BY'];
    private const SERBIAN_COUNTRIES = ['RS', 'BA', 'ME', 'XK'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->localeFromPath($request)
            ?? $this->localeFromQuery($request)
            ?? $this->localeFromSession()
            ?? $this->localeFromCountry($this->countryCode($request))
            ?? 'en';

        App::setLocale($locale);

        return $next($request);
    }

    private function localeFromPath(Request $request): ?string
    {
        $locale = $request->segment(1);

        if (! is_string($locale) || ! in_array($locale, self::SUPPORTED, true)) {
            return null;
        }

        if ($request->hasSession()) {
            $request->session()->put('locale', $locale);
        }

        return $locale;
    }

    private function localeFromQuery(Request $request): ?string
    {
        $locale = $request->query('lang');

        if (! is_string($locale) || ! in_array($locale, self::SUPPORTED, true)) {
            return null;
        }

        if ($request->hasSession()) {
            $request->session()->put('locale', $locale);
        }

        return $locale;
    }

    private function localeFromSession(): ?string
    {
        $locale = request()->hasSession() ? request()->session()->get('locale') : null;

        return in_array($locale, self::SUPPORTED, true) ? $locale : null;
    }

    private function localeFromCountry(?string $country): ?string
    {
        if (! $country) {
            return null;
        }

        if (in_array($country, self::RUSSIAN_COUNTRIES, true)) {
            return 'ru';
        }

        if (in_array($country, self::SERBIAN_COUNTRIES, true)) {
            return 'sr';
        }

        return 'en';
    }

    private function countryCode(Request $request): ?string
    {
        $headerCountry = strtoupper((string) $request->header('CF-IPCountry'));
        if (preg_match('/^[A-Z]{2}$/', $headerCountry)) {
            return $headerCountry;
        }

        $ip = $request->ip();
        if (! $ip || in_array($ip, ['127.0.0.1', '::1'], true)) {
            return null;
        }

        $dbPath = storage_path('geoip/GeoLite2-City.mmdb');
        if (! is_file($dbPath)) {
            return null;
        }

        try {
            $reader = new Reader($dbPath);
            $country = $reader->city($ip)->country->isoCode;
            $reader->close();

            return $country ?: null;
        } catch (\Throwable) {
            return null;
        }
    }
}
