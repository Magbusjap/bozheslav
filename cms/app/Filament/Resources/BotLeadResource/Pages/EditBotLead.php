<?php

namespace App\Filament\Resources\BotLeadResource\Pages;

use App\Filament\Resources\BotLeadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBotLead extends EditRecord
{
    protected static string $resource = BotLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
