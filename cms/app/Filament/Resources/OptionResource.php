<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OptionResource\Pages;
use App\Models\Option;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OptionResource extends Resource
{
    protected static ?string $model = Option::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Настройки';
    protected static ?string $navigationLabel = 'SEO и Мета-данные';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralLabel = 'Настройки';
    protected static ?string $modelLabel = 'Настройка';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('group')
                ->label('Группа')
                ->options([
                    'social'   => 'Соцсети',
                    'hero'     => 'Главная страница',
                    'contacts' => 'Контакты',
                    'general'  => 'Общее',
                ])
                ->required(),
            Forms\Components\TextInput::make('label')
                ->label('Название')
                ->required(),
            Forms\Components\TextInput::make('key')
                ->label('Ключ(id')
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\Textarea::make('value')
                ->label('Ссылка')
                ->rows(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label('Группа')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('label')
                    ->label('Название')
                    ->searchable(),
                Tables\Columns\TextColumn::make('key')
                    ->label('Ключ(id)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Ссылка')
                    ->limit(50),
            ])
            ->defaultSort('group')
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label('Группа')
                    ->options([
                        'social'   => 'Соцсети',
                        'hero'     => 'Главная страница',
                        'contacts' => 'Контакты',
                        'general'  => 'Общее',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOptions::route('/'),
        ];
    }
}