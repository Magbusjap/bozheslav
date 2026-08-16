<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MindMapResource\Pages;
use App\Models\MindMap;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MindMapResource extends Resource
{
    protected static ?string $model = MindMap::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationLabel = 'Mind Maps';

    protected static ?string $navigationGroup = 'Система';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Название карты')
                    ->required()
                    ->maxLength(255)
                    ->live(debounce: 800)
                    ->afterStateUpdated(function (?string $state, callable $set) {
                        if ($state === null || $state === '') {
                            $set('slug', '');
                            return;
                        }

                        $set('slug', Str::slug(transliterate($state)));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('excerpt')
                    ->label('Краткое описание')
                    ->rows(3),
                Forms\Components\Section::make('Редактор mind map')
                    ->schema([
                        Forms\Components\ViewField::make('mind_map_editor')
                            ->view('filament.forms.components.mind-map-editor')
                            ->dehydrated(false),
                        Forms\Components\Textarea::make('data')
                            ->label('JSON карты')
                            ->rows(18)
                            ->required()
                            ->default(MindMap::defaultJson())
                            ->rule('json')
                            ->formatStateUsing(function ($state, ?MindMap $record) {
                                if (is_string($state) && trim($state) !== '') {
                                    return $state;
                                }

                                return $record?->data ?: MindMap::defaultJson($record?->title ?: 'New Mind Map');
                            })
                            ->extraAttributes([
                                'data-mind-map-json-wrapper' => 'true',
                            ])
                            ->extraInputAttributes([
                                'data-mind-map-json' => 'true',
                                'class' => 'font-mono text-xs',
                            ])
                            ->helperText('JSON синхронизируется с визуальным редактором и хранится в базе.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListMindMaps::route('/'),
            'create' => Pages\CreateMindMap::route('/create'),
            'edit' => Pages\EditMindMap::route('/{record}/edit'),
        ];
    }
}
