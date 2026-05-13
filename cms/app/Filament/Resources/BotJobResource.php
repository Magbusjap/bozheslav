<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BotJobResource\Pages;
use App\Models\BotJob;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BotJobResource extends Resource
{
    protected static ?string $model = BotJob::class;
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'Найденные задания';
    protected static ?string $navigationGroup = 'Боты и AI';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Задание';
    protected static ?string $pluralModelLabel = 'Задания';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('Название')->disabled(),
            Forms\Components\TextInput::make('source')->label('Площадка')->disabled(),
            Forms\Components\TextInput::make('category')->label('Категория'),
            Forms\Components\TextInput::make('budget')->label('Бюджет')->disabled(),
            Forms\Components\Select::make('verdict')
                ->label('Вердикт')
                ->options([
                    'take' => '✅ Брать',
                    'skip' => '❌ Пропустить',
                    'unclear' => '❓ Неясно',
                ]),
            Forms\Components\Toggle::make('human_override')->label('Переопределено человеком')->disabled(),
            Forms\Components\Toggle::make('is_taken')->label('Взят'),
            Forms\Components\Toggle::make('is_paid')->label('Оплачен'),
            Forms\Components\TextInput::make('price_paid')->label('Сумма оплаты')->numeric(),
            Forms\Components\Textarea::make('ai_analysis')->label('Анализ AI')->disabled()->rows(6),
            Forms\Components\Textarea::make('description')->label('Описание')->disabled()->rows(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('source')
                    ->label('Площадка')
                    ->badge(),
                    Tables\Columns\TextColumn::make('category')
                    ->label('Категория')
                    ->searchable()
                    ->default('—'),
                Tables\Columns\TextColumn::make('budget')
                    ->label('Бюджет'),
                Tables\Columns\TextColumn::make('verdict')
                    ->label('Вердикт')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'take' => 'success',
                        'skip' => 'danger',
                        default => 'warning'
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'take' => '✅ Брать',
                        'skip' => '❌ Пропустить',
                        default => '❓ Неясно'
                    }),
                Tables\Columns\IconColumn::make('human_override')
                    ->label('Переопределено')
                    ->boolean()
                    ->trueColor('warning')
                    ->trueIcon('heroicon-o-exclamation-triangle'),
                Tables\Columns\IconColumn::make('is_taken')
                    ->label('Взят')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Оплачен')
                    ->boolean(),
                Tables\Columns\TextColumn::make('price_paid')
                    ->label('Сумма')
                    ->money('RUB'),
                Tables\Columns\TextColumn::make('found_at')
                    ->label('Найдено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('found_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('verdict')
                    ->label('Вердикт')
                    ->options([
                        'take' => '✅ Брать',
                        'skip' => '❌ Пропустить',
                        'unclear' => '❓ Неясно',
                    ]),
                Tables\Filters\SelectFilter::make('source')
                    ->label('Площадка')
                    ->options([
                        'kwork' => 'Kwork',
                        'fl' => 'FL.ru',
                        'freelance' => 'Freelance.ru',
                    ]),
                Tables\Filters\TernaryFilter::make('is_taken')->label('Взят'),
                Tables\Filters\TernaryFilter::make('is_paid')->label('Оплачен'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('open')
                    ->label('Открыть')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn(BotJob $record) => $record->link)
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBotJobs::route('/'),
            'edit' => Pages\EditBotJob::route('/{record}/edit'),
        ];
    }
}