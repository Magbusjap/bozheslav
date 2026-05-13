<?php

namespace App\Filament\Resources\PortfolioPageResource\Pages;

use App\Filament\Resources\PortfolioPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPortfolioPages extends ListRecords
{
    protected static string $resource = PortfolioPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
