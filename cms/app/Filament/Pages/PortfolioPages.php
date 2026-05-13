<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use App\Models\PortfolioPage as PageModel;

class PortfolioPages extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Page-проекты';
    protected static ?string $navigationGroup = 'Портфолио';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.pages.portfolio-pages';

    public array $pages = [];

    public function mount(): void
    {
        $this->loadPages();
    }

    public function loadPages(): void
    {
        $this->pages = PageModel::orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    public function createPage(array $data): void
    {
        $page = PageModel::create([
            'title'  => $data['title'],
            'slug'   => \Illuminate\Support\Str::slug(transliterate($data['title'])),
            'status' => 'draft',
        ]);

        $this->redirect('/admin/site-pages/' . $page->id . '/edit');
    }

    public function deletePage(int $id): void
    {
        PageModel::find($id)?->delete();
        $this->loadPages();
        Notification::make()->title('Страница удалена')->success()->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Создать страницу')
                ->icon('heroicon-o-plus')
                ->form([
                    TextInput::make('title')
                        ->label('Название проекта')
                        ->required(),
                ])
                ->action(fn (array $data) => $this->createPage($data)),
        ];
    }
}