<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

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

        $source = Post::query()->find($this->cloneFromId);

        if (! $source) {
            return;
        }

        $existingTranslation = $source->translation_group_id
            ? Post::query()
                ->where('translation_group_id', $source->translation_group_id)
                ->where('locale', $this->cloneTargetLocale)
                ->first()
            : null;

        if ($existingTranslation) {
            $this->redirect(PostResource::getUrl('edit', ['record' => $existingTranslation]));

            return;
        }

        $this->form->fill([
            'locale' => $this->cloneTargetLocale,
            'title' => $source->title,
            'slug' => PostResource::uniqueSlugForLocale($source->slug, $this->cloneTargetLocale),
            'category_id' => $source->category_id,
            'excerpt' => $source->excerpt,
            'content' => $source->content,
            'cover_image' => $source->cover_image,
            'status' => 'draft',
            'seo_title' => $source->seo_title,
            'seo_description' => $source->seo_description,
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! $this->cloneFromId || ! array_key_exists($this->cloneTargetLocale, Post::LOCALES)) {
            return $data;
        }

        $source = Post::query()->find($this->cloneFromId);

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
}
