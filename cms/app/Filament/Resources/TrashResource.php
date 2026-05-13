<?php

namespace App\Filament\Resources;

use App\Models\Trash;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class TrashResource extends Resource
{
    protected static ?string $model = Trash::class;
    protected static ?string $navigationIcon = 'heroicon-o-trash';
    protected static ?string $navigationLabel = 'Корзина';
    protected static ?string $navigationGroup = 'Система';
    protected static ?string $modelLabel = 'Запись';
    protected static ?string $pluralModelLabel = 'Корзина';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model_label')
                    ->label('Раздел')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('model_name')
                    ->label('Название')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('deletedBy.name')
                    ->label('Удалил')
                    ->default('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Удалено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Удалится через')
                    ->formatStateUsing(fn($state) => $state
                        ? 'через ' . max(0, now()->diffInDays($state, false)) . ' дн.'
                        : '—')
                    ->color(fn($record) => $record->daysLeft() < 7 ? 'danger' : 'warning'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('model_label')
                    ->label('Раздел')
                    ->options(fn() => Trash::query()
                        ->distinct()
                        ->pluck('model_label', 'model_label')
                        ->toArray()),
            ])
            ->actions([
                Tables\Actions\Action::make('restore')
                    ->label('Восстановить')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Восстановить запись?')
                    ->modalDescription('Запись будет восстановлена в исходный раздел.')
                    ->action(function (Trash $record) {
                        if ($record->restore()) {
                            Notification::make()
                                ->title('Запись восстановлена')
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Ошибка восстановления')
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('force_delete')
                    ->label('Принудительно')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Удалить навсегда?')
                    ->modalDescription('Запись будет удалена безвозвратно. Восстановление невозможно.')
                    ->action(function (Trash $record) {
                        $record->delete();
                        Notification::make()
                            ->title('Запись удалена навсегда')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('restore_selected')
                    ->label('Восстановить выбранные')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn($records) => $records->each->restore()),

                Tables\Actions\BulkAction::make('force_delete_selected')
                    ->label('Принудительно удалить')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn($records) => $records->each->delete()),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Корзина пуста')
            ->emptyStateDescription('Удалённые записи будут храниться здесь 60 дней.');
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\TrashResource\Pages\ListTrashes::route('/'),
        ];
    }
}