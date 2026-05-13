<?php

namespace App\Filament\Pages;

use App\Models\Option;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ProfileSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Ссылки профиля';
    protected static ?string $navigationGroup = 'Профиль';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.profile-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'github_url'   => Option::get('github_url'),
            'telegram_url' => Option::get('telegram_url'),
            'email'        => Option::get('email'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ссылки')
                    ->icon('heroicon-o-link')
                    ->schema([
                        TextInput::make('github_url')
                            ->label('GitHub')
                            ->url()
                            ->prefix('https://')
                            ->placeholder('github.com/username'),

                        TextInput::make('telegram_url')
                            ->label('Telegram')
                            ->url()
                            ->prefix('https://')
                            ->placeholder('t.me/username'),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->placeholder('you@example.com'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (['github_url', 'telegram_url', 'email'] as $key) {
            Option::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data[$key] ?? null,
                    'label' => $key,
                    'group' => 'profile',
                ]
            );
        }

        Notification::make()
            ->title('Ссылки сохранены')
            ->success()
            ->send();
    }
}