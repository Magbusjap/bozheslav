<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use App\Models\Page;
use App\Models\PortfolioProject;
use App\Models\PortfolioCategory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Постов опубликовано', Post::where('status', 'published')->count())
                ->description('Всего в блоге: ' . Post::count())
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Черновики', Post::where('status', 'draft')->count())
                ->description('Не опубликованные посты')
                ->descriptionIcon('heroicon-m-pencil')
                ->color('warning'),

            Stat::make('Проекты', PortfolioProject::count())
                ->description('Категорий: ' . PortfolioCategory::count())
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('primary'),

            Stat::make('Страницы', Page::count())
                ->description('Статических страниц')
                ->descriptionIcon('heroicon-m-document')
                ->color('info'),
        ];
    }
}