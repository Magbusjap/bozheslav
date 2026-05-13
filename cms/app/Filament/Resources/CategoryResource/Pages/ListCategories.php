<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\Pages\Concerns\HasLocaleTabs;
use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    use HasLocaleTabs;

    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
