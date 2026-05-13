<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortfolioCategoryResource\Pages;
use App\Filament\Resources\Concerns\HasTranslatableResource;
use App\Models\PortfolioCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Traits\HasTrashAction;
use Filament\Tables;
use Filament\Tables\Table;

class PortfolioCategoryResource extends Resource
{
    use HasTrashAction;
    use HasTranslatableResource;

    protected static ?string $model = PortfolioCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationLabel = 'Категории';
    protected static ?string $modelLabel = 'Категория';
    protected static ?string $pluralModelLabel = 'Категории';
    protected static ?string $navigationGroup = 'Портфолио';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            self::localeFormSelect(),
            Forms\Components\TextInput::make('name')
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
            Forms\Components\TextInput::make('sort_order')
                ->label('Порядок сортировки')
                ->numeric()
                ->default(0),

            Forms\Components\Select::make('status')
                ->label('Статус')
                ->options([
                    'published' => 'Опубликована',
                    'draft'     => 'Черновик',
                ])
                ->default('published')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->description(fn (PortfolioCategory $record): ?string => self::placeholderDescription($record))
                    ->extraAttributes(fn (PortfolioCategory $record): array => $record->isTranslationPlaceholder()
                        ? ['style' => 'cursor: default; text-decoration: none; opacity: .55;']
                        : [
                            'style' => 'cursor: pointer; text-decoration: none;',
                            'onmouseover' => 'this.style.textDecoration="underline"',
                            'onmouseout' => 'this.style.textDecoration="none"',
                        ]),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->extraAttributes(fn (PortfolioCategory $record): array => self::placeholderCellAttributes($record)),
                self::localeTableColumn(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable()
                    ->extraAttributes(fn (PortfolioCategory $record): array => self::placeholderCellAttributes($record)),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'published' => 'Опубликована',
                        'draft'     => 'Черновик',
                    ])
                    ->disabled(fn (PortfolioCategory $record): bool => $record->isTranslationPlaceholder()),
            ])
            ->recordClasses(fn (PortfolioCategory $record): ?string => $record->isTranslationPlaceholder()
                ? 'bg-warning-50 dark:bg-warning-950/20'
                : null)
            ->recordUrl(fn (PortfolioCategory $record): ?string => self::translatableRecordUrl($record))
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (PortfolioCategory $record): bool => ! $record->isTranslationPlaceholder()),
                self::getTrashAction('Категории', 'name')
                    ->visible(fn (PortfolioCategory $record): bool => ! $record->isTranslationPlaceholder()),
            ]);

    }

    public static function translationCloneFields(): array
    {
        return ['name', 'slug', 'sort_order', 'status'];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePortfolioCategories::route('/'),
            'create' => Pages\CreatePortfolioCategory::route('/create'),
            'edit' => Pages\EditPortfolioCategory::route('/{record}/edit'),
        ];
    }
}
