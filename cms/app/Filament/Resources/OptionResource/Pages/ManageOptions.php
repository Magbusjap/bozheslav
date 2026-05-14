<?php

namespace App\Filament\Resources\OptionResource\Pages;

use App\Filament\Resources\OptionResource;
use App\Models\Option;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageOptions extends ManageRecords
{
    protected static string $resource = OptionResource::class;

    public function mount(): void
    {
        $this->ensureLocalizedContactEmailOptions();

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    private function ensureLocalizedContactEmailOptions(): void
    {
        $currentEmail = Option::get('contact_email', 'i@mankudinov.ru');

        foreach ([
            'contact_email_ru' => ['Почта RU', $currentEmail],
            'contact_email_en' => ['Почта EN', 'magbusjap@gmail.com'],
            'contact_email_sr' => ['Почта SR', 'magbusjap@gmail.com'],
        ] as $key => [$label, $default]) {
            Option::firstOrCreate(
                ['key' => $key],
                [
                    'label' => $label,
                    'group' => 'contacts',
                    'value' => $default,
                ],
            );
        }
    }
}
