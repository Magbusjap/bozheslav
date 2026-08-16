<?php

namespace App\Filament\Pages;

use App\Services\Plane\PlaneDashboardService;
use Filament\Pages\Page;

class PlaneOverview extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Plane';
    protected static ?string $navigationGroup = 'Система';
    protected static ?int $navigationSort = 4;
    protected static ?string $title = 'Plane';
    protected static ?string $slug = 'plane';
    protected static string $view = 'filament.pages.plane-overview';

    public array $plane = [];

    public function mount(PlaneDashboardService $dashboard): void
    {
        $this->plane = $dashboard->getDashboardData();
    }
}
