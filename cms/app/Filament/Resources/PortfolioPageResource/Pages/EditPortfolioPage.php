<?php

namespace App\Filament\Resources\PortfolioPageResource\Pages;

use App\Filament\Resources\PortfolioPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Trash;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class EditPortfolioPage extends EditRecord
{
    protected static string $resource = PortfolioPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view')
                ->label('Просмотр')
                ->url(fn () => '/portfolio/pages/' . $this->record->slug)
                ->openUrlInNewTab()
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->visible(fn () => $this->record->status === 'published'),
            Actions\Action::make('preview')
                ->label('Просмотр черновика')
                ->url(fn () => '/portfolio/pages/' . $this->record->slug)
                ->openUrlInNewTab()
                ->icon('heroicon-o-eye')
                ->color('warning')
                ->visible(fn () => $this->record->status === 'draft'),
            Action::make('trash')
                ->label('Удалить')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Переместить в корзину?')
                ->modalDescription(fn() => "«{$this->record->title}» будет перемещено в корзину на 60 дней.")
                ->modalSubmitActionLabel('Переместить в корзину')
                ->action(function () {
                    Trash::moveToTrash($this->record->withoutRelations(), 'Page-проекты', 'title');
                    $this->record->delete();
                    Notification::make()->title('Перемещено в корзину')->success()->send();
                    $this->redirect(PortfolioPageResource::getUrl());
                }),
        ];
    }
}
