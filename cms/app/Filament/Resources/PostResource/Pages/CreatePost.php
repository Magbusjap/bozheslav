<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\Pages\Concerns\CreatesLocaleTranslation;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    use CreatesLocaleTranslation;

    protected static string $resource = PostResource::class;
}
