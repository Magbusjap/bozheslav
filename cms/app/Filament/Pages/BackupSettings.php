<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BackupSettings extends Page implements HasForms
{
    use InteractsWithForms;

    private const CONFIG_PATH = 'app/backup-mail/settings.env';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?string $navigationLabel = 'Резервная копия';
    protected static ?string $navigationGroup = 'Мониторинг';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Резервная копия';
    protected static string $view = 'filament.pages.backup-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = $this->readSettings();

        $this->form->fill([
            'backup_email' => $settings['BACKUP_EMAIL'] ?? 'mgbusjap@gmail.com',
            'gmail_app_password' => '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Настройки отправки')
                    ->icon('heroicon-o-envelope')
                    ->description('Пароль приложения Gmail сохраняется скрыто и не выводится обратно в форму.')
                    ->schema([
                        TextInput::make('backup_email')
                            ->label('Почта для отправки и получения')
                            ->email()
                            ->required(),
                        TextInput::make('gmail_app_password')
                            ->label('Новый пароль приложения Gmail')
                            ->password()
                            ->helperText('Оставь поле пустым, если пароль менять не нужно.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $settings = $this->readSettings();

        $settings['BACKUP_EMAIL'] = $data['backup_email'];

        if (! empty($data['gmail_app_password'])) {
            $settings['GMAIL_APP_PASSWORD'] = $data['gmail_app_password'];
        }

        if (empty($settings['GMAIL_APP_PASSWORD'])) {
            Notification::make()
                ->title('Укажи пароль приложения Gmail')
                ->warning()
                ->send();
        }

        try {
            $this->writeSettings($settings);
        } catch (\Throwable $exception) {
            report($exception);

            Notification::make()
                ->title('Не удалось сохранить настройки резервной копии')
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return;
        }

        $this->form->fill([
            'backup_email' => $settings['BACKUP_EMAIL'],
            'gmail_app_password' => '',
        ]);

        Notification::make()
            ->title('Настройки резервной копии сохранены')
            ->success()
            ->send();
    }

    private function settingsPath(): string
    {
        return storage_path(self::CONFIG_PATH);
    }

    private function readSettings(): array
    {
        $path = $this->settingsPath();

        if (! File::exists($path)) {
            return [];
        }

        $settings = [];

        foreach (File::lines($path) as $line) {
            $line = trim($line);

            if ($line === '' || Str::startsWith($line, '#') || ! str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $settings[$key] = $value;
        }

        return $settings;
    }

    private function writeSettings(array $settings): void
    {
        $path = $this->settingsPath();
        File::ensureDirectoryExists(dirname($path), 0700);

        $content = collect([
            'BACKUP_EMAIL' => $settings['BACKUP_EMAIL'] ?? 'mgbusjap@gmail.com',
            'GMAIL_APP_PASSWORD' => $settings['GMAIL_APP_PASSWORD'] ?? '',
        ])->map(fn (string $value, string $key): string => $key . '=' . str_replace(["\r", "\n"], '', $value))
            ->implode(PHP_EOL) . PHP_EOL;

        File::put($path, $content);
        @chmod($path, 0600);
    }
}
