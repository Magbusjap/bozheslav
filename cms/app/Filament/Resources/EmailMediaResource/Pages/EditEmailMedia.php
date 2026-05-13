<?php

namespace App\Filament\Resources\EmailMediaResource\Pages;

use App\Filament\Resources\EmailMediaResource;
use Filament\Resources\Pages\EditRecord;

class EditEmailMedia extends EditRecord
{
    protected static string $resource = EmailMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}