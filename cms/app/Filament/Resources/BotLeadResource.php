<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BotLeadResource\Pages;
use App\Models\BotLead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BotLeadResource extends Resource
{
    protected static ?string $model = BotLead::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Компании';
    protected static ?string $navigationGroup = 'Боты и AI';
    protected static ?int $navigationSort = 5;
    protected static ?string $modelLabel = 'Компания';
    protected static ?string $pluralModelLabel = 'Компании';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('company_name')->label('Название')->required(),
            Forms\Components\TextInput::make('contact_email')->label('Email')->email(),
            Forms\Components\TextInput::make('website')->label('Сайт'),
            Forms\Components\Select::make('source')
                ->label('Источник')
                ->options([
                    'hh' => 'HH.ru',
                    '2gis' => '2GIS',
                    'avito' => 'Авито',
                    'manual' => 'Вручную',
                ]),
            Forms\Components\TextInput::make('niche')->label('Ниша'),
            Forms\Components\Toggle::make('has_website')->label('Есть сайт'),
            Forms\Components\Select::make('site_quality')
                ->label('Качество сайта')
                ->options([
                    'none' => 'Нет сайта',
                    'bad' => 'Плохой',
                    'ok' => 'Нормальный',
                ]),
            Forms\Components\Select::make('status')
                ->label('Статус')
                ->options([
                    'new' => '🆕 Новый',
                    'sent' => '📨 Письмо отправлено',
                    'replied' => '✅ Ответил',
                    'rejected' => '❌ Отказ',
                ]),
            Forms\Components\Textarea::make('notes')->label('Заметки')->rows(3),
            Forms\Components\Textarea::make('ai_analysis')->label('Анализ AI')->rows(4)->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Компания')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('niche')
                    ->label('Ниша')
                    ->default('—'),
                Tables\Columns\TextColumn::make('source')
                    ->label('Источник')
                    ->badge(),
                Tables\Columns\IconColumn::make('has_website')
                    ->label('Сайт')
                    ->boolean(),
                Tables\Columns\TextColumn::make('site_quality')
                    ->label('Качество')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'ok' => 'success',
                        'bad' => 'danger',
                        'none' => 'warning',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'new' => 'info',
                        'sent' => 'warning',
                        'replied' => 'success',
                        'rejected' => 'danger',
                        default => 'gray'
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'new' => '🆕 Новый',
                        'sent' => '📨 Отправлено',
                        'replied' => '✅ Ответил',
                        'rejected' => '❌ Отказ',
                        default => $state
                    }),
                Tables\Columns\TextColumn::make('contact_email')
                    ->label('Email')
                    ->default('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Добавлено')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'sent' => 'Отправлено',
                        'replied' => 'Ответил',
                        'rejected' => 'Отказ',
                    ]),
                Tables\Filters\SelectFilter::make('source')
                    ->label('Источник')
                    ->options([
                        'hh' => 'HH.ru',
                        '2gis' => '2GIS',
                        'avito' => 'Авито',
                        'manual' => 'Вручную',
                    ]),
                Tables\Filters\TernaryFilter::make('has_website')->label('Есть сайт'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('open_website')
                    ->label('Сайт')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn(BotLead $record) => $record->website)
                    ->openUrlInNewTab()
                    ->visible(fn(BotLead $record) => !empty($record->website)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBotLeads::route('/'),
            'create' => Pages\CreateBotLead::route('/create'),
            'edit' => Pages\EditBotLead::route('/{record}/edit'),
        ];
    }
}