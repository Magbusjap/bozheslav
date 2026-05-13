<?php

namespace App\Filament\Resources\PortfolioCategoryResource\Pages;

use App\Filament\Resources\Pages\Concerns\CreatesLocaleTranslation;
use App\Filament\Resources\PortfolioCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePortfolioCategory extends CreateRecord
{
    use CreatesLocaleTranslation;

    protected static string $resource = PortfolioCategoryResource::class;
}
