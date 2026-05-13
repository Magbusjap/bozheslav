<?php

namespace App\Filament\Resources\PortfolioProjectResource\Pages;

use App\Filament\Resources\PortfolioProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Trash;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class EditPortfolioProject extends EditRecord
{
    protected static string $resource = PortfolioProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('trash')
                ->label('Удалить')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Переместить в корзину?')
                ->modalDescription(fn() => "«{$this->record->title}» будет перемещено в корзину на 60 дней.")
                ->modalSubmitActionLabel('Переместить в корзину')
                ->action(function () {
                    Trash::moveToTrash($this->record->withoutRelations(), 'Проекты', 'title');
                    $this->record->delete();
                    Notification::make()->title('Перемещено в корзину')->success()->send();
                    $this->redirect(PortfolioProjectResource::getUrl());
                }),
        ];
    }
}
