<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortfolioCategoryResource\Pages;
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
                ->unique(ignoreRecord: true),
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
                    ->extraAttributes(['style' => 'cursor: pointer; 
                     text-decoration: none;'])
                    ->extraAttributes(['onmouseover' => 'this.style.textDecoration="underline"',
                     'onmouseout' => 'this.style.textDecoration="none"']),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'published' => 'Опубликована',
                        'draft'     => 'Черновик',
                    ]),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                self::getTrashAction('Категории', 'name'),
            ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePortfolioCategories::route('/'),
        ];
    }
}