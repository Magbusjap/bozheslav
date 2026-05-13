<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitStatsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        return [
            Stat::make('Посещений сегодня', Visit::whereDate('created_at', today())->count())
                ->description('За всё время: ' . Visit::count())
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary'),

            Stat::make('Уникальных IP сегодня', Visit::whereDate('created_at', today())->distinct('ip')->count('ip'))
                ->description('Всего уникальных: ' . Visit::distinct('ip')->count('ip'))
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            Stat::make('Стран', Visit::whereNotNull('country')->distinct('country')->count('country'))
                ->description('За всё время')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('info'),
        ];
    }
}