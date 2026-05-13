<?php

namespace App\Traits;

use App\Models\Trash;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;

trait HasTrashAction
{
    public static function getTrashAction(
        string $label,
        string $nameField = 'title'
    ): Action {
        return Action::make('delete')
            ->label('Удалить')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Переместить в корзину?')
            ->modalDescription(
                fn($record) => "«{$record->{$nameField}}» будет перемещено в корзину. "
                    . "Вы сможете восстановить его в течение 60 дней."
            )
            ->modalSubmitActionLabel('Переместить в корзину')
            ->modalIcon('heroicon-o-trash')
            ->action(function ($record) use ($label, $nameField) {
                Trash::moveToTrash($record->withoutRelations(), $label, $nameField);
                $record->delete();

                Notification::make()
                    ->title('Перемещено в корзину')
                    ->success()
                    ->send();
            });
    }

    public static function getTrashBulkAction(
        string $label,
        string $nameField = 'title'
    ): BulkAction {
        return BulkAction::make('delete')
            ->label('Удалить выбранные')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('Переместить выбранные в корзину?')
            ->modalDescription('Выбранные записи будут перемещены в корзину на 60 дней.')
            ->modalSubmitActionLabel('Переместить в корзину')
            ->action(function ($records) use ($label, $nameField) {
                $records->each(function ($record) use ($label, $nameField) {
                    Trash::moveToTrash($record->withoutRelations(), $label, $nameField);
                    $record->delete();
                });

                Notification::make()
                    ->title('Записи перемещены в корзину')
                    ->success()
                    ->send();
            })
            ->deselectRecordsAfterCompletion();
    }
}