<?php

namespace App\Filament\Pages;

use App\Models\Option;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ResumeSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';
    protected static ?string $navigationLabel = 'Резюме';
    protected static ?string $navigationGroup = 'Профиль';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'Резюме';
    protected static string $view = 'filament.pages.resume-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'resume_pdf' => Option::get('resume_pdf'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('PDF резюме')
                    ->description('Загрузите PDF-файл. Кнопка «Скачать моё резюме» на сайте будет отдавать этот файл.')
                    ->icon('heroicon-o-document-arrow-down')
                    ->schema([
                        FileUpload::make('resume_pdf')
                            ->label('Файл резюме (.pdf)')
                            ->disk('public')
                            ->directory('resumes')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120)
                            ->downloadable()
                            ->deletable(true)
                            ->helperText('Максимум 5 МБ.')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Option::updateOrCreate(
            ['key' => 'resume_pdf'],
            [
                'value' => $data['resume_pdf'] ?? null,
                'label' => 'Резюме (PDF)',
                'group' => 'general',
            ]
        );

        Notification::make()
            ->title('Резюме сохранено')
            ->success()
            ->send();
    }
}
