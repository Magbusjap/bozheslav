<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class PortfolioDemos extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Demo-проекты';
    protected static ?string $navigationGroup = 'Портфолио';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.portfolio-demos';

    public ?string $zipPath = null;
    public array $demos = [];

    public function mount(): void
    {
        $this->loadDemos();
    }

    public function loadDemos(): void
    {
        $path = public_path('portfolio');
        if (!is_dir($path)) {
            $this->demos = [];
            return;
        }
        $this->demos = array_filter(
            scandir($path),
            fn ($item) => $item !== '.' && $item !== '..' && is_dir($path . '/' . $item)
        );
        $this->demos = array_values($this->demos);
    }



    public function uploadZip(array $data): void
    {
        $file = $data['zip'];
        $tmpPath = storage_path('app/public/' . $file);

        if (!file_exists($tmpPath)) {
            Notification::make()->title('Файл не найден на сервере')->danger()->send();
            return;
        }

        $zip = new \ZipArchive();
        if ($zip->open($tmpPath) !== true) {
            Notification::make()->title('Не удалось открыть ZIP-архив')->danger()->send();
            return;
        }

        // original name
        $firstName = $zip->getNameIndex(0);
        $folderName = rtrim(explode('/', $firstName)[0], '/');

        // if not original name
        if (empty($folderName)) {
            $folderName = pathinfo($file, PATHINFO_FILENAME);
        }

        $extractPath = public_path('portfolio/' . $folderName);

        if (!is_dir($extractPath)) {
            mkdir($extractPath, 0755, true);
        }

        $zip->extractTo(public_path('portfolio/'));
        $zip->close();

        unlink($tmpPath);

        $this->loadDemos();

        Notification::make()
            ->title('Проект успешно распакован в: ' . $folderName)
            ->success()
            ->send();
    }

    public function deleteDemo(string $name): void
    {
        $path = public_path('portfolio/' . $name);
        if (is_dir($path)) {
            $this->deleteDirectory($path);
        }
        $this->loadDemos();
        Notification::make()->title('Проект удалён')->success()->send();
    }

    private function deleteDirectory(string $path): void
    {
        foreach (scandir($path) as $item) {
            if ($item === '.' || $item === '..') continue;
            $full = $path . '/' . $item;
            is_dir($full) ? $this->deleteDirectory($full) : unlink($full);
        }
        rmdir($path);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload')
                ->label('Загрузить ZIP')
                ->icon('heroicon-o-arrow-up-tray')
                ->modalSubmitActionLabel('Загрузить')
                ->form([
                    FileUpload::make('zip')
                        ->label('ZIP архив')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                        ->disk('public')
                        ->required(),
                ])
                ->action(fn (array $data) => $this->uploadZip($data)),
        ];
    }
}