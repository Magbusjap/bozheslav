<?php

namespace App\Filament\Pages;

use App\Models\Option;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
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
        $legacyResume = Option::get('resume_pdf');

        $this->form->fill([
            'resume_pdf_ru' => Option::get('resume_pdf_ru', $legacyResume),
            'resume_pdf_en' => Option::get('resume_pdf_en'),
            'resume_pdf_sr' => Option::get('resume_pdf_sr'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('PDF резюме по языкам')
                    ->description('Загрузите отдельный PDF для каждого языка. Кнопка «Скачать моё резюме» будет отдавать файл текущей языковой версии сайта.')
                    ->icon('heroicon-o-document-arrow-down')
                    ->schema([
                        Tabs::make('Языки резюме')
                            ->tabs([
                                Tab::make('RU')
                                    ->schema([
                                        $this->resumeUpload('resume_pdf_ru', 'Резюме на русском (.pdf)', '/ru/resume/download'),
                                    ]),
                                Tab::make('EN')
                                    ->schema([
                                        $this->resumeUpload('resume_pdf_en', 'Resume in English (.pdf)', '/en/resume/download'),
                                    ]),
                                Tab::make('SR')
                                    ->schema([
                                        $this->resumeUpload('resume_pdf_sr', 'CV na srpskom (.pdf)', '/sr/resume/download'),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ([
            'resume_pdf_ru' => 'Резюме RU (PDF)',
            'resume_pdf_en' => 'Резюме EN (PDF)',
            'resume_pdf_sr' => 'Резюме SR (PDF)',
        ] as $key => $label) {
            Option::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $data[$key] ?? null,
                    'label' => $label,
                    'group' => 'general',
                ]
            );
        }

        Option::updateOrCreate(
            ['key' => 'resume_pdf'],
            [
                'value' => $data['resume_pdf_ru'] ?? null,
                'label' => 'Резюме (PDF, legacy)',
                'group' => 'general',
            ]
        );

        Notification::make()
            ->title('Резюме сохранено')
            ->success()
            ->send();
    }

    private function resumeUpload(string $key, string $label, string $url): FileUpload
    {
        return FileUpload::make($key)
            ->label($label)
            ->disk('public')
            ->directory('resumes')
            ->acceptedFileTypes(['application/pdf'])
            ->maxSize(5120)
            ->downloadable()
            ->deletable(true)
            ->helperText('Ссылка: ' . $url . '. Максимум 5 МБ.')
            ->columnSpanFull();
    }
}
