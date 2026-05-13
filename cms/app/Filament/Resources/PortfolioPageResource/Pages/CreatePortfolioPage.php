<?php

namespace App\Filament\Resources\PortfolioPageResource\Pages;

use App\Filament\Resources\Pages\Concerns\CreatesLocaleTranslation;
use App\Filament\Resources\PortfolioPageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePortfolioPage extends CreateRecord
{
    use CreatesLocaleTranslation;

    protected static string $resource = PortfolioPageResource::class;
}
