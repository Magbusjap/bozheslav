<?php

namespace App\Filament\Resources\MindMapResource\Pages;

use App\Filament\Resources\MindMapResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMindMaps extends ListRecords
{
    protected static string $resource = MindMapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
