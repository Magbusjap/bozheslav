<?php

namespace App\Filament\Resources\EmailFolderResource\Pages;

use App\Filament\Resources\EmailFolderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailFolders extends ListRecords
{
    protected static string $resource = EmailFolderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
