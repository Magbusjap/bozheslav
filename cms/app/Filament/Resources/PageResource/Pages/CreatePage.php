<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\Pages\Concerns\CreatesLocaleTranslation;
use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    use CreatesLocaleTranslation;

    protected static string $resource = PageResource::class;
}
