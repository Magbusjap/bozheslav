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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;

class InfoResources extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'Ресурсы';
    protected static ?string $navigationGroup = 'Информация';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Ресурсы и документация';
    protected static string $view = 'filament.pages.info-resources';

    public ?array $data = [];

   public function mount(): void
    {
        $this->form->fill([
            'php_docs_url'       => Option::get('php_docs_url', 'https://www.php.net/manual/ru/'),
            'php_logo'           => Option::get('php_logo'),
            'laravel_docs_url'   => Option::get('laravel_docs_url', 'https://laravel.su/docs'),
            'laravel_github_url' => Option::get('laravel_github_url', 'https://github.com/laravel/laravel'),
            'laravel_logo'       => Option::get('laravel_logo'),
            'filament_docs_url'  => Option::get('filament_docs_url', 'https://filamentphp.com/docs'),
            'filament_github_url'=> Option::get('filament_github_url', 'https://github.com/filamentphp/filament'),
            'filament_logo'      => Option::get('filament_logo'),
            'nginx_logo'         => Option::get('nginx_logo'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('PHP')
                    ->icon('heroicon-o-code-bracket')
                    ->columns(2)
                    ->schema([
                        TextInput::make('php_docs_url')
                            ->label('Документация')
                            ->url()
                            ->default('https://www.php.net/manual/ru/')
                            ->columnSpan(2),
                        FileUpload::make('php_logo')
                            ->label('Логотип')
                            ->disk('public')
                            ->directory('stack-logos')
                            ->image()
                            ->maxSize(512),
                    ]),

                Section::make('Laravel')
                    ->icon('heroicon-o-fire')
                    ->columns(2)
                    ->schema([
                        TextInput::make('laravel_docs_url')
                            ->label('Документация (RU)')
                            ->url()
                            ->default('https://laravel.su/docs'),
                        TextInput::make('laravel_github_url')
                            ->label('GitHub')
                            ->url()
                            ->default('https://github.com/laravel/laravel'),
                        FileUpload::make('laravel_logo')
                            ->label('Логотип')
                            ->disk('public')
                            ->directory('stack-logos')
                            ->image()
                            ->maxSize(512)
                            ->columnSpan(2),
                    ]),

                Section::make('Filament')
                    ->icon('heroicon-o-squares-2x2')
                    ->columns(2)
                    ->schema([
                        TextInput::make('filament_docs_url')
                            ->label('Документация')
                            ->url()
                            ->default('https://filamentphp.com/docs'),
                        TextInput::make('filament_github_url')
                            ->label('GitHub')
                            ->url()
                            ->default('https://github.com/filamentphp/filament'),
                        FileUpload::make('filament_logo')
                            ->label('Логотип')
                            ->disk('public')
                            ->directory('stack-logos')
                            ->image()
                            ->maxSize(512)
                            ->columnSpan(2),
                    ]),

                Section::make('nginx')
                    ->icon('heroicon-o-server')
                    ->schema([
                        FileUpload::make('nginx_logo')
                            ->label('Логотип')
                            ->disk('public')
                            ->directory('stack-logos')
                            ->image()
                            ->maxSize(512),
                    ]),
                
                Section::make('База данных')
                    ->icon('heroicon-o-circle-stack')
                    ->schema([
                        Placeholder::make('adminer_link')
                            ->label('Adminer — веб-интерфейс PostgreSQL')
                            ->content(new \Illuminate\Support\HtmlString(
                                '<a href="https://bozheslav.com/adminer.php" target="_blank"
                                    class="text-primary-500 hover:underline font-medium">
                                    Открыть Adminer →
                                </a>'
                            )),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $labels = [
            'php_docs_url'        => 'PHP Документация',
            'php_logo'            => 'PHP Логотип',
            'laravel_docs_url'    => 'Laravel Документация',
            'laravel_github_url'  => 'Laravel GitHub',
            'laravel_logo'        => 'Laravel Логотип',
            'filament_docs_url'   => 'Filament Документация',
            'filament_github_url' => 'Filament GitHub',
            'filament_logo'       => 'Filament Логотип',
            'nginx_logo'          => 'nginx Логотип',
        ];

        foreach ($data as $key => $value) {
            Option::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'label' => $labels[$key] ?? $key, 'group' => 'info']
            );
        }

        Notification::make()
            ->title('Ссылки сохранены')
            ->success()
            ->send();
    }
}