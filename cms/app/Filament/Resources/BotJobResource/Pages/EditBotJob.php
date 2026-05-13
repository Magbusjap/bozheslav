<?php

namespace App\Filament\Resources\BotJobResource\Pages;

use App\Filament\Resources\BotJobResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBotJob extends EditRecord
{
    protected static string $resource = BotJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
