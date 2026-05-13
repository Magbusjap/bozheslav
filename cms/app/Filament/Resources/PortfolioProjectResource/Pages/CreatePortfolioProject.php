<?php

namespace App\Filament\Resources\PortfolioProjectResource\Pages;

use App\Filament\Resources\Pages\Concerns\CreatesLocaleTranslation;
use App\Filament\Resources\PortfolioProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePortfolioProject extends CreateRecord
{
    use CreatesLocaleTranslation;

    protected static string $resource = PortfolioProjectResource::class;
}
