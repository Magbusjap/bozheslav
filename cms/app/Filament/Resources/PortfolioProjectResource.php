<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortfolioProjectResource\Pages;
use App\Filament\Resources\Concerns\HasTranslatableResource;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Support\LocaleTranslationStatus;
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
    use HasTranslatableResource;

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
            self::localeFormSelect(),
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
                ->unique(ignoreRecord: true, modifyRuleUsing: self::slugUniqueRule()),
            Forms\Components\Select::make('portfolio_category_id')
                ->label('Категория')
                ->options(fn (Forms\Get $get): array => PortfolioCategory::query()
                    ->where('locale', $get('locale') ?: 'ru')
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->all())
                ->searchable()
                ->preload()
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
                    ->description(fn (PortfolioProject $record): ?string => self::placeholderDescription($record))
                    ->url(fn (PortfolioProject $record): ?string => $record->isTranslationPlaceholder()
                        ? null
                        : Pages\EditPortfolioProject::getUrl(['record' => $record])),
                Tables\Columns\TextColumn::make('translations')
                    ->label('ru / eng / sr')
                    ->state(fn (PortfolioProject $record) => LocaleTranslationStatus::indicator(LocaleTranslationStatus::forModel($record)))
                    ->html()
                    ->extraAttributes(fn (PortfolioProject $record): array => self::placeholderCellAttributes($record)),
                self::localeTableColumn(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория')
                    ->extraAttributes(fn (PortfolioProject $record): array => self::placeholderCellAttributes($record)),
                Tables\Columns\TextColumn::make('link_type')
                    ->label('Тип')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'demo'     => 'success',
                        'page'     => 'info',
                        'external' => 'warning',
                        default    => 'gray',
                    })
                    ->extraAttributes(fn (PortfolioProject $record): array => self::placeholderCellAttributes($record)),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'published' => 'Опубликован',
                        'draft'     => 'Черновик',
                    ])
                    ->disabled(fn (PortfolioProject $record): bool => $record->isTranslationPlaceholder()),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable()
                    ->extraAttributes(fn (PortfolioProject $record): array => self::placeholderCellAttributes($record)),
            ])
            ->recordClasses(fn (PortfolioProject $record): ?string => $record->isTranslationPlaceholder()
                ? 'bg-warning-50 dark:bg-warning-950/20'
                : null)
            ->recordUrl(fn (PortfolioProject $record): ?string => self::translatableRecordUrl($record))
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (PortfolioProject $record): bool => ! $record->isTranslationPlaceholder()),
                self::getTrashAction('Проекты')
                    ->visible(fn (PortfolioProject $record): bool => ! $record->isTranslationPlaceholder()),
            ]);
    }

    public static function translationCloneFields(): array
    {
        return [
            'title',
            'slug',
            'portfolio_category_id',
            'status',
            'description',
            'stack_tags',
            'github_url',
            'link_type',
            'link_url',
            'link_label',
            'cover_image',
            'sort_order',
        ];
    }

    protected static function translatedRelationFields(): array
    {
        return [
            'portfolio_category_id' => PortfolioCategory::class,
        ];
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
