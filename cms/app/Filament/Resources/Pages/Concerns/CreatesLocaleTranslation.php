<?php

namespace App\Filament\Resources\Pages\Concerns;

use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;

trait CreatesLocaleTranslation
{
    #[Locked]
    public ?int $cloneFromId = null;

    #[Locked]
    public ?string $cloneTargetLocale = null;

    public function mount(): void
    {
        parent::mount();

        $this->cloneFromId = request()->integer('clone_from') ?: null;
        $this->cloneTargetLocale = request()->string('locale')->toString() ?: null;

        if (! $this->cloneFromId || ! array_key_exists($this->cloneTargetLocale, Post::LOCALES)) {
            return;
        }

        $model = static::getResource()::getModel();
        $source = $model::query()->find($this->cloneFromId);

        if (! $source) {
            return;
        }

        $existingTranslation = $source->translation_group_id
            ? $model::query()
                ->where('translation_group_id', $source->translation_group_id)
                ->where('locale', $this->cloneTargetLocale)
                ->first()
            : null;

        if ($existingTranslation) {
            $this->redirect(static::getResource()::getUrl('edit', ['record' => $existingTranslation]));

            return;
        }

        $this->form->fill(static::getResource()::translationCloneData($source, $this->cloneTargetLocale));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! $this->cloneFromId || ! array_key_exists($this->cloneTargetLocale, Post::LOCALES)) {
            return $data;
        }

        $model = static::getResource()::getModel();
        $source = $model::query()->find($this->cloneFromId);

        if (! $source) {
            return $data;
        }

        if (! $source->translation_group_id) {
            $source->translation_group_id = (string) Str::uuid();
            $source->saveQuietly();
        }

        $data['locale'] = $this->cloneTargetLocale;
        $data['translation_group_id'] = $source->translation_group_id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $resource = static::getResource();

        if (method_exists($resource, 'createMissingTranslations')) {
            $resource::createMissingTranslations($this->record);
        }
    }
}
