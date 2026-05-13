<?php

namespace App\Filament\Resources\EmailMediaResource\Pages;

use App\Filament\Resources\EmailMediaResource;
use App\Models\EmailMedia;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailMedia extends CreateRecord
{
    protected static string $resource = EmailMediaResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $folder = $data['folder'];
        $paths = (array) $data['path'];
        $filenames = (array) ($data['filename'] ?? []);

        $first = null;
        foreach ($paths as $index => $path) {
            $fullPath = storage_path('app/public/' . $path);
            $size = file_exists($fullPath) ? filesize($fullPath) : null;
            $filename = $filenames[$index] ?? basename($path);

            $record = EmailMedia::create([
                'folder'   => $folder,
                'path'     => $path,
                'filename' => $filename,
                'size'     => $size,
            ]);

            if ($first === null) {
                $first = $record;
            }
        }

        return $first;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}