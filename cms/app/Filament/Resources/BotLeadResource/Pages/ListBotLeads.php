<?php

namespace App\Filament\Resources\BotLeadResource\Pages;

use App\Filament\Resources\BotLeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBotLeads extends ListRecords
{
    protected static string $resource = BotLeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
