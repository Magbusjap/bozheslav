<?php

namespace App\Filament\Resources\PortfolioCategoryResource\Pages;

use App\Filament\Resources\PortfolioCategoryResource;
use App\Models\Trash;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPortfolioCategory extends EditRecord
{
    protected static string $resource = PortfolioCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('trash')
                ->label('Удалить')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Переместить в корзину?')
                ->modalDescription(fn () => "«{$this->record->name}» будет перемещено в корзину на 60 дней.")
                ->modalSubmitActionLabel('Переместить в корзину')
                ->action(function (): void {
                    Trash::moveToTrash($this->record->withoutRelations(), 'Категории', 'name');
                    $this->record->delete();
                    Notification::make()->title('Перемещено в корзину')->success()->send();
                    $this->redirect(PortfolioCategoryResource::getUrl());
                }),
        ];
    }
}
