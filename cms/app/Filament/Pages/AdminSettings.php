<?php

namespace App\Filament\Pages;

use App\Models\Option;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Настройки админки';
    protected static ?string $navigationGroup = 'Настройки';
    protected static ?int $navigationSort = 3;
    protected static ?string $title = 'Настройки админки';
    protected static string $view = 'filament.pages.admin-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();

        $this->form->fill([
            'admin_logo'       => Option::get('admin_logo'),
            'admin_name'       => Option::get('admin_name', config('app.name')),
            'user_name'        => $user->name,
            'user_email'       => $user->email,
            'user_avatar'      => Option::get('user_avatar'),
            'current_password' => '',
            'new_password'     => '',
            'new_password_confirmation' => '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Логотип и название админки')
                    ->icon('heroicon-o-paint-brush')
                    ->description('Логотип отображается в левом верхнем углу панели управления.')
                    ->schema([
                        TextInput::make('admin_name')
                            ->label('Название админки')
                            ->helperText('Отображается рядом с логотипом или вместо него.'),
                        FileUpload::make('admin_logo')
                            ->label('Логотип (изображение)')
                            ->disk('public')
                            ->directory('admin')
                            ->image()
                            ->maxSize(512)
                            ->helperText('Рекомендуемый размер: 160×40px'),
                    ]),

                Section::make('Профиль пользователя')
                    ->icon('heroicon-o-user')
                    ->schema([
                        FileUpload::make('user_avatar')
                            ->label('Аватар')
                            ->disk('public')
                            ->directory('admin')
                            ->image()
                            ->avatar()
                            ->maxSize(1024),
                        TextInput::make('user_name')
                            ->label('Имя пользователя')
                            ->required(),
                        TextInput::make('user_email')
                            ->label('Email')
                            ->email()
                            ->required(),
                    ]),

                Section::make('Смена пароля')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Текущий пароль')
                            ->password()
                            ->revealable(),
                        TextInput::make('new_password')
                            ->label('Новый пароль')
                            ->password()
                            ->revealable()
                            ->minLength(8),
                        TextInput::make('new_password_confirmation')
                            ->label('Подтверждение нового пароля')
                            ->password()
                            ->revealable(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = User::find(Auth::id());

        // Сохраняем опции админки
        Option::updateOrCreate(['key' => 'admin_logo'],
            ['value' => $data['admin_logo'] ?? null, 'label' => 'Логотип админки', 'group' => 'admin']);
        Option::updateOrCreate(['key' => 'admin_name'],
            ['value' => $data['admin_name'] ?? null, 'label' => 'Название админки', 'group' => 'admin']);
        Option::updateOrCreate(['key' => 'user_avatar'],
            ['value' => $data['user_avatar'] ?? null, 'label' => 'Аватар пользователя', 'group' => 'admin']);

        // Обновляем имя и email
        $user->name  = $data['user_name'];
        $user->email = $data['user_email'];

        // Смена пароля
        if (!empty($data['new_password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                Notification::make()
                    ->title('Неверный текущий пароль')
                    ->danger()
                    ->send();
                return;
            }
            if ($data['new_password'] !== $data['new_password_confirmation']) {
                Notification::make()
                    ->title('Пароли не совпадают')
                    ->danger()
                    ->send();
                return;
            }
            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        Notification::make()
            ->title('Настройки сохранены')
            ->success()
            ->send();
    }
}