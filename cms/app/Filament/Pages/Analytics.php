<?php

namespace App\Filament\Pages;

use App\Models\Visit;
use Filament\Pages\Page;

class Analytics extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Аналитика';
    protected static ?string $navigationGroup = 'Аналитика';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Аналитика';
    protected static string $view = 'filament.pages.analytics';

    public string $period = '7d';

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    public function getViewData(): array
    {
        $since = match($this->period) {
            '1d'  => now()->subDay(),
            '7d'  => now()->subDays(7),
            '30d' => now()->subDays(30),
            'all' => now()->subYears(10),
            default => now()->subDays(7),
        };

        $visits = Visit::where('created_at', '>=', $since);

        // Топ страниц
        $topPages = (clone $visits)
            ->selectRaw('url, count(*) as count')
            ->groupBy('url')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Страны
        $countries = (clone $visits)
            ->whereNotNull('country_name')
            ->selectRaw('country_name, country, count(*) as count')
            ->groupBy('country_name', 'country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Города
        $cities = (clone $visits)
            ->whereNotNull('city')
            ->selectRaw('city, country, count(*) as count')
            ->groupBy('city', 'country')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Браузеры
        $browsers = (clone $visits)
            ->whereNotNull('browser')
            ->selectRaw('browser, count(*) as count')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->get();

        // Устройства
        $devices = (clone $visits)
            ->whereNotNull('device_type')
            ->selectRaw('device_type, count(*) as count')
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->get();

        // Посещения по дням
        $byDay = (clone $visits)
            ->selectRaw("DATE(created_at) as date, count(*) as count")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'period'      => $this->period,
            'total'       => (clone $visits)->count(),
            'unique'      => (clone $visits)->distinct('ip')->count('ip'),
            'topPages'    => $topPages,
            'countries'   => $countries,
            'cities'      => $cities,
            'browsers'    => $browsers,
            'devices'     => $devices,
            'byDay'       => $byDay,
        ];
    }
}