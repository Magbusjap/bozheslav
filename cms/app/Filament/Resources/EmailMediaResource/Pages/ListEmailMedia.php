<?php

namespace App\Filament\Resources\EmailMediaResource\Pages;

use App\Filament\Resources\EmailMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailMedia extends ListRecords
{
    protected static string $resource = EmailMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
