<?php

namespace App\Filament\Resources\BotJobResource\Pages;

use App\Filament\Resources\BotJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBotJobs extends ListRecords
{
    protected static string $resource = BotJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
