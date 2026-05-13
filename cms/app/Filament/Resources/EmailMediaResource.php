<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailMediaResource\Pages;
use App\Models\EmailMedia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class EmailMediaResource extends Resource
{
    protected static ?string $model = EmailMedia::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Рассылки';
    protected static ?string $navigationLabel = 'Email Медиа';
    protected static ?string $modelLabel = 'Медиафайл';
    protected static ?string $pluralModelLabel = 'Email Медиа';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Загрузка файлов')
                    ->schema([
                        Forms\Components\Select::make('folder')
                            ->label('Папка')
                            ->required()
                            ->options(fn () => \App\Models\EmailFolder::pluck('name', 'slug')->toArray())
                            ->searchable()
                            ->helperText('Выберите папку для файла'),

                        Forms\Components\FileUpload::make('path')
                            ->label('Изображения')
                            ->image()
                            ->required()
                            ->multiple()
                            ->disk('public')
                            ->directory(fn (Forms\Get $get) => 'email-media/' . ($get('folder') ?: 'default'))
                            ->storeFileNamesIn('filename')
                            ->maxSize(5120)
                            ->hiddenOn('edit'),
                        Forms\Components\TextInput::make('filename')
                            ->label('Название файла')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Превью')
                    ->disk('public')
                    ->square()
                    ->size(80),

                Tables\Columns\TextColumn::make('emailFolder.name')
                    ->label('Папка')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('filename')
                    ->label('Файл')
                    ->limit(20)
                    ->searchable(),

                Tables\Columns\TextColumn::make('size')
                    ->label('Размер')
                    ->formatStateUsing(fn ($state) => $state ? round($state / 1024, 1) . ' KB' : '—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Загружен')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('Ссылка')
                    ->getStateUsing(function (EmailMedia $record) {
                        $path = is_array($record->path) ? $record->path[0] : $record->path;
                        $path = ltrim(str_replace(['["', '"]'], '', $record->path), '/');
                        return asset('storage/' . $path);
                    })
                    ->copyable()
                    ->copyMessage('Ссылка скопирована!')
                    ->icon('heroicon-o-link')
                    ->limit(30),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('folder')
                    ->label('Папка')
                    ->options(fn () => EmailMedia::query()
                        ->distinct()
                        ->pluck('folder', 'folder')
                        ->toArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Изменить'),
                Tables\Actions\DeleteAction::make()
                    ->label('Удалить')
                    ->after(fn (EmailMedia $record) => Storage::disk('public')->delete($record->path)),
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
            'index' => Pages\ListEmailMedia::route('/'),
            'create' => Pages\CreateEmailMedia::route('/create'),
            'edit' => Pages\EditEmailMedia::route('/{record}/edit'),
        ];
    }
}