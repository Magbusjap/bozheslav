<?php

namespace App\Filament\Resources\PortfolioProjectResource\Pages;

use App\Filament\Resources\Pages\Concerns\HasLocaleTabs;
use App\Filament\Resources\PortfolioProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPortfolioProjects extends ListRecords
{
    use HasLocaleTabs;

    protected static string $resource = PortfolioProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
