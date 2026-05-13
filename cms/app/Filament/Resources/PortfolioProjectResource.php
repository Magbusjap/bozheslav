<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortfolioProjectResource\Pages;
use App\Models\PortfolioProject;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Traits\HasTrashAction;
use Filament\Tables;
use Filament\Tables\Table;

class PortfolioProjectResource extends Resource
{
    use HasTrashAction;

    protected static ?string $model = PortfolioProject::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Проекты';
    protected static ?string $modelLabel = 'Проект';
    protected static ?string $pluralModelLabel = 'Проекты';
    protected static ?string $navigationGroup = 'Портфолио';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Название')
                ->required()
                ->maxLength(255)
                ->live(debounce: 800)
                ->afterStateUpdated(function (?string $state, callable $set) {
                    if (!empty($state)) {
                        $set('slug', \Illuminate\Support\Str::slug(transliterate($state)));
                    }
                }),
            Forms\Components\TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true),
            Forms\Components\Select::make('portfolio_category_id')
                ->label('Категория')
                ->relationship('category', 'name')
                ->nullable(),
            Forms\Components\Select::make('status')
                ->label('Статус')
                ->options([
                    'published' => 'Опубликован',
                    'draft'     => 'Черновик',
                ])
                ->default('published')
                ->required(),
            Forms\Components\Textarea::make('description')
                ->label('Описание')
                ->columnSpanFull(),
            Forms\Components\TagsInput::make('stack_tags')
                ->label('Теги стека')
                ->placeholder('Laravel, PostgreSQL...')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('github_url')
                ->label('GitHub URL')
                ->url()
                ->nullable(),
            Forms\Components\Select::make('link_type')
                ->label('Тип ссылки')
                ->options([
                    'demo'     => 'Demo',
                    'page'     => 'Страница',
                    'external' => 'Внешняя ссылка',
                ])
                ->default('demo')
                ->required()
                ->live(),
            Forms\Components\TextInput::make('link_url')
                ->label('URL ссылки')
                ->nullable()
                ->helperText(fn ($get) => match($get('link_type')) {
                    'demo'     => 'Например: https://bozheslav.com/portfolio/double-lending-fit-studio/',
                    'page'     => 'Например: https://bozheslav.com/portfolio/pages/n8n',
                    'external' => 'Например: https://github.com/username/project',
                    default    => ''
                }),
            Forms\Components\TextInput::make('link_label')
                ->label('Текст кнопки')
                ->nullable()
                ->placeholder(fn ($get) => match($get('link_type')) {
                    'demo'     => 'Demo',
                    'page'     => 'Перейти',
                    'external' => 'Подробнее',
                    default    => 'Demo'
                }),
            CuratorPicker::make('cover_image')
                ->label('Обложка')
                ->buttonLabel('Выбрать обложку')
                ->nullable()
                ->maxItems(1),
            Forms\Components\TextInput::make('sort_order')
                ->label('Порядок сортировки')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Обложка')
                    ->getStateUsing(fn ($record) => $record->cover_url)
                    ->width(80)
                    ->height(60),
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->url(fn ($record) => Pages\EditPortfolioProject::getUrl(['record' => $record])),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория'),
                Tables\Columns\TextColumn::make('link_type')
                    ->label('Тип')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'demo'     => 'success',
                        'page'     => 'info',
                        'external' => 'warning',
                        default    => 'gray',
                    }),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'published' => 'Опубликован',
                        'draft'     => 'Черновик',
                    ]),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
            ])
            ->recordUrl(fn ($record) => Pages\EditPortfolioProject::getUrl(['record' => $record]))
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                self::getTrashAction('Проекты'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPortfolioProjects::route('/'),
            'create' => Pages\CreatePortfolioProject::route('/create'),
            'edit'   => Pages\EditPortfolioProject::route('/{record}/edit'),
        ];
    }
}