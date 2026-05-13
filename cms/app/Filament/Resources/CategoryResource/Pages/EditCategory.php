<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Trash;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('trash')
                ->label('Удалить')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Переместить в корзину?')
                ->modalDescription(fn() => "«{$this->record->name}» будет перемещено в корзину. Вы сможете восстановить его в течение 60 дней.")
                ->modalSubmitActionLabel('Переместить в корзину')
                ->action(function () {
                    Trash::moveToTrash($this->record->withoutRelations(), 'Категории блога', 'name');
                    $this->record->delete();
                    Notification::make()->title('Перемещено в корзину')->success()->send();
                    $this->redirect(CategoryResource::getUrl());
                }),
        ];
    }
}
