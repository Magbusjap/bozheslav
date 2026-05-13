<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Models\Option;

class BotSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Настройки промпта';
    protected static ?string $navigationGroup = 'Боты и AI';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.bot-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'bot_role'        => Option::where('key', 'bot_role')->value('value') ?? '',
            'bot_system'      => Option::where('key', 'bot_system')->value('value') ?? '',
            'bot_rules'       => Option::where('key', 'bot_rules')->value('value') ?? '',
            'bot_keywords'    => Option::where('key', 'bot_keywords')->value('value') ?? '',
            'bot_about'       => Option::where('key', 'bot_about')->value('value') ?? '',
            'leads_role'     => Option::where('key', 'leads_role')->value('value') ?? '',
            'leads_system'   => Option::where('key', 'leads_system')->value('value') ?? '',
            'leads_rules'    => Option::where('key', 'leads_rules')->value('value') ?? '',
            'leads_template' => Option::where('key', 'leads_template')->value('value') ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Роль и промпт')->schema([
                Forms\Components\Textarea::make('bot_role')
                    ->label('Роль')
                    ->rows(3)
                    ->placeholder('Ты помощник фрилансера...'),
                Forms\Components\Textarea::make('bot_system')
                    ->label('Системный промпт')
                    ->rows(6)
                    ->placeholder('Основные инструкции для AI...'),
                Forms\Components\Textarea::make('bot_rules')
                    ->label('Правила')
                    ->rows(5)
                    ->placeholder('Пропускай если: серая тема, нанято < 40%...'),
            ]),
            Forms\Components\Section::make('О фрилансере')->schema([
                Forms\Components\Textarea::make('bot_about')
                    ->label('Навыки, опыт, портфолио')
                    ->rows(5)
                    ->placeholder('Laravel, PHP, HTML/CSS BEM, Python Flask...'),
            ]),
            Forms\Components\Section::make('Ключевые слова')->schema([
                Forms\Components\Textarea::make('bot_keywords')
                    ->label('Ключевые слова (через запятую)')
                    ->rows(3)
                    ->placeholder('верстка, вёрстка, laravel, php, html...'),
            ]),
            Forms\Components\Section::make('Поиск компаний')->schema([
                Forms\Components\Textarea::make('leads_role')
                    ->label('Роль AI для компаний')
                    ->rows(3)
                    ->placeholder('Ты помогаешь фрилансеру находить потенциальных заказчиков...'),
                Forms\Components\Textarea::make('leads_system')
                    ->label('Системный промпт для компаний')
                    ->rows(5)
                    ->placeholder('Анализируй компанию и определяй подходит ли она...'),
                Forms\Components\Textarea::make('leads_rules')
                    ->label('Правила отбора компаний')
                    ->rows(4)
                    ->placeholder('Подходит если: есть сайт, малый/средний бизнес...'),
                Forms\Components\Textarea::make('leads_template')
                    ->label('Шаблон письма')
                    ->rows(6)
                    ->placeholder('Здравствуйте! Меня зовут Михаил...'),
            ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Option::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'label' => $key, 'group' => 'bot']
            );
        }

        Notification::make()
            ->title('Настройки сохранены')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Сохранить')
                ->action('save')
                ->icon('heroicon-o-check'),
        ];
    }
}