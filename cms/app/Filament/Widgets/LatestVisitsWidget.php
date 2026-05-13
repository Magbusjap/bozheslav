<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestVisitsWidget extends BaseWidget
{
    protected static ?string $heading = 'Последние посещения';
    protected static ?int $sort = 6;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Visit::latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Время')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('Страница')
                    ->limit(40),
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP')
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('country_name')
                    ->label('Страна')
                    ->default('—'),
                Tables\Columns\TextColumn::make('city')
                    ->label('Город')
                    ->default('—'),
                Tables\Columns\BadgeColumn::make('device_type')
                    ->label('Устройство')
                    ->colors([
                        'primary' => 'desktop',
                        'warning' => 'mobile',
                        'success' => 'tablet',
                    ]),
                Tables\Columns\TextColumn::make('browser')
                    ->label('Браузер')
                    ->default('—'),
            ]);
    }
}