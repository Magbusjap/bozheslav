<?php

namespace App\Filament\Resources\EmailFolderResource\Pages;

use App\Filament\Resources\EmailFolderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmailFolder extends EditRecord
{
    protected static string $resource = EmailFolderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
