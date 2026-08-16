<?php

namespace App\Filament\Pages;

use App\Services\Affine\AffineDashboardService;
use App\Services\Affine\AffinePasswordResetService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Throwable;

class AffineOverview extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'AFFiNE';
    protected static ?string $navigationGroup = 'Система';
    protected static ?int $navigationSort = 5;
    protected static ?string $title = 'AFFiNE';
    protected static ?string $slug = 'affine';
    protected static string $view = 'filament.pages.affine-overview';

    public array $affine = [];

    public function mount(AffineDashboardService $dashboard): void
    {
        $this->affine = $dashboard->getDashboardData();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendPasswordReset')
                ->label('Отправить сброс пароля')
                ->icon('heroicon-o-envelope')
                ->color('warning')
                ->form([
                    TextInput::make('email')
                        ->label('Email AFFiNE')
                        ->email()
                        ->required()
                        ->default((string) config('affine.recovery_email')),
                ])
                ->action(function (array $data): void {
                    try {
                        $resetService = app(AffinePasswordResetService::class);
                        $result = $resetService->sendResetLink((string) $data['email']);

                        Notification::make()
                            ->title('Письмо отправлено')
                            ->body('Ссылка для смены пароля AFFiNE отправлена на ' . $result['email'])
                            ->success()
                            ->send();
                    } catch (Throwable $e) {
                        Notification::make()
                            ->title('Не удалось отправить письмо')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
