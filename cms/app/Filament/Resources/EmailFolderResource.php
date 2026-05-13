<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailFolderResource\Pages;
use App\Models\EmailFolder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmailFolderResource extends Resource
{
    protected static ?string $model = EmailFolder::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationGroup = 'Рассылки';
    protected static ?string $navigationLabel = 'Папки';
    protected static ?string $modelLabel = 'Папка';
    protected static ?string $pluralModelLabel = 'Папки';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Название папки')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Slug генерируется автоматически'),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->badge(),

                Tables\Columns\TextColumn::make('media_count')
                    ->label('Файлов')
                    ->counts('media')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Изменить'),
                Tables\Actions\DeleteAction::make()->label('Удалить'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailFolders::route('/'),
            'create' => Pages\CreateEmailFolder::route('/create'),
            'edit' => Pages\EditEmailFolder::route('/{record}/edit'),
        ];
    }
}