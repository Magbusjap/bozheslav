<?php

namespace App\Filament\Resources\TrashResource\Pages;

use App\Filament\Resources\TrashResource;
use Filament\Resources\Pages\ListRecords;

class ListTrashes extends ListRecords
{
    protected static string $resource = TrashResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}