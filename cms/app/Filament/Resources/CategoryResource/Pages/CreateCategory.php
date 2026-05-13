<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\Pages\Concerns\CreatesLocaleTranslation;
use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    use CreatesLocaleTranslation;

    protected static string $resource = CategoryResource::class;
}
