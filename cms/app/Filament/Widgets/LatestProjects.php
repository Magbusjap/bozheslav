<?php

namespace App\Filament\Widgets;

use App\Models\PortfolioProject;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestProjects extends BaseWidget
{
    protected static ?string $heading = 'Проекты портфолио';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(PortfolioProject::latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Название')
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ]);
    }
}