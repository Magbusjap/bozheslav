<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\Pages\Concerns\HasLocaleTabs;
use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePages extends ManageRecords
{
    use HasLocaleTabs;

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
