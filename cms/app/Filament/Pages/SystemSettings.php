<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;


class SystemSettings extends Page
{
    public string $logs = '';

    public function mount(): void
    {
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $lines = array_slice(file($logFile), -30);
            $this->logs = implode('', $lines);
        }
    }

    protected static ?string $navigationIcon = 'heroicon-o-server';
    protected static ?string $navigationLabel = 'Система';
    protected static ?string $navigationGroup = 'Настройки';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.system-settings';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Очистить кэш')
                ->icon('heroicon-o-trash')
                ->color('warning')
                ->action(function () {
                    Artisan::call('cache:clear');
                    Notification::make()
                        ->title('Кэш очищен')
                        ->success()
                        ->send();
                }),
            Action::make('clear_config')
                ->label('Очистить конфиг')
                ->icon('heroicon-o-cog')
                ->color('warning')
                ->action(function () {
                    Artisan::call('config:clear');
                    Notification::make()
                        ->title('Конфиг очищен')
                        ->success()
                        ->send();
                }),
            Action::make('clear_views')
                ->label('Очистить views')
                ->icon('heroicon-o-eye')
                ->color('warning')
                ->action(function () {
                    Artisan::call('view:clear');
                    Notification::make()
                        ->title('Views очищены')
                        ->success()
                        ->send();
                }),
            Action::make('clear_all')
                ->label('Очистить всё')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    Artisan::call('route:clear');
                    Artisan::call('view:clear');
                    Notification::make()
                        ->title('Всё очищено')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function getLogs(): string
    {
        $logFile = storage_path('logs/laravel.log');
        if (!file_exists($logFile)) {
            return 'Лог файл не найден.';
        }
        $lines = array_slice(file($logFile), -30);
        return implode('', $lines);
    }

}

