<?php

namespace App\Filament\Resources;


use Awcodes\Curator\Components\Forms\CuratorPicker;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Infolists\Components\Actions\Action as InfolistAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Traits\HasTrashAction;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Support\Str;

class PostResource extends Resource
{
	use HasTrashAction;

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
        Forms\Components\Select::make('locale')
            ->label('Язык статьи')
            ->options(Post::LOCALES)
            ->default('ru')
            ->required(),
		Forms\Components\TextInput::make('title')
            ->label('Заголовок')
			->required()
			->maxLength(255)
			->live(debounce: 800)
			->afterStateUpdated(function (?string $state, callable $set) {
				if ($state === null || $state === '') {
					$set('slug', '');
					return;
				}
				$set('slug', \Illuminate\Support\Str::slug(transliterate($state)));
			}),
		Forms\Components\TextInput::make('slug')
            ->label('Slug')
			->required()
			->maxLength(255)
			->unique(ignoreRecord: true, modifyRuleUsing: fn (Unique $rule, callable $get) => $rule->where('locale', $get('locale'))),
		Forms\Components\Select::make('category_id')
		    ->label('Категория')
			->relationship('category', 'name')
			->required(),
                Forms\Components\Textarea::make('excerpt')
                    ->label('Краткое описание')
                    ->columnSpanFull(),
               Forms\Components\Builder::make('content')
		    ->blocks([
	        Forms\Components\Builder\Block::make('heading')
	            ->label('Заголовок')
	            ->schema([
	                Forms\Components\Select::make('level')
	                    ->options(['h2' => 'H2', 'h3' => 'H3'])
	                    ->default('h2')
	                    ->required(),
	                Forms\Components\TextInput::make('text')
	                    ->label('Текст заголовка')
	                    ->required(),
	            ]),
	        Forms\Components\Builder\Block::make('text')
	            ->label('Текст')
	            ->schema([
	                TiptapEditor::make('content')
	                    ->label('Содержимое')
	                    ->required(),
	            ]),
	        Forms\Components\Builder\Block::make('code')
	            ->label('Код')
	            ->schema([
	                Forms\Components\Select::make('language')
	                    ->options([
	                        'php' => 'PHP',
	                        'javascript' => 'JavaScript',
	                        'bash' => 'Bash',
	                        'html' => 'HTML',
	                        'css' => 'CSS',
	                        'sql' => 'SQL',
	                    ])
	                    ->default('php')
	                    ->required(),
	                Forms\Components\Textarea::make('code')
	                    ->label('Код')
	                    ->required()
	                    ->rows(10),
	            ]),
	        Forms\Components\Builder\Block::make('image')
	            ->label('Изображение')
	            ->schema([
	                CuratorPicker::make('url')
						->label('Изображение')
						->buttonLabel('Выбрать изображение')
						->required(),
	                Forms\Components\TextInput::make('caption')
	                    ->label('Подпись'),
					Forms\Components\Toggle::make('proportional')
						->label('Пропорционально')
						->default(true),
					Forms\Components\TextInput::make('width')
						->label('Ширина (px)')
						->numeric(),
					Forms\Components\TextInput::make('height')
						->label('Высота (px)')
						->numeric(),
	            ]),
			Forms\Components\Builder\Block::make('markdown')
				->label('Markdown')
				->schema([
					Forms\Components\Textarea::make('content')
						->label('MD-текст')
						->required()
						->rows(10),
				]),
	        Forms\Components\Builder\Block::make('quote')
	            ->label('Цитата')
	            ->schema([
	                Forms\Components\Textarea::make('text')
	                    ->label('Текст цитаты')
	                    ->required(),
	                Forms\Components\TextInput::make('author')
	                    ->label('Автор'),
	            ]),
			Forms\Components\Builder\Block::make('image_text')
				->label('Изображение + текст')
				->schema([
					CuratorPicker::make('url')
						->label('Изображение')
						->buttonLabel('Выбрать изображение')
						->required(),
					Forms\Components\Select::make('position')
						->label('Расположение изображения')
						->options([
							'left' => 'Слева',
							'right' => 'Справа',
						])
						->default('left')
						->required(),
					Forms\Components\TextInput::make('width')
						->label('Ширина изображения (px)')
						->numeric()
						->default(300),
					// Forms\Components\Textarea::make('text')
					// 	->label('Текст')
					// 	->required()
					// 	->rows(5),
					TiptapEditor::make('text')
						->label('Текст')
						->required(),
				]),
	    ])
	    ->columnSpanFull(),
                CuratorPicker::make('cover_image')
					->label('Обложка')
					->buttonLabel('Выбрать обложку')
					->nullable()
					->maxItems(1),
                Forms\Components\Select::make('status')
					->options([
						'draft' => 'Черновик',
						'published' => 'Опубликован',
					])
					->selectablePlaceholder(false)
					->default('draft')
					->required(),
				Forms\Components\Section::make('SEO')
					->collapsed()
					->schema([
				Forms\Components\TextInput::make('seo_title')
					->label('SEO Title')
					->maxLength(60)
					->live(debounce: 500)
					->helperText(fn ($state) => strlen($state ?? '') . ' / 60 символов'),
				Forms\Components\Textarea::make('seo_description')
					->label('SEO Description')
					->maxLength(160)
					->rows(3)
					->live(debounce: 500)
					->helperText(fn ($state) => strlen($state ?? '') . ' / 160 символов'),
				]),
		]);
    }

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				Tables\Columns\TextColumn::make('title')
                    ->label('Title')
					->searchable()
					->wrap()
					->description(fn (Post $record): ?string => $record->isTranslationPlaceholder()
						? 'Нет версии ' . $record->localeLabel($record->selectedLocale()) . '. Показана основа ' . $record->localeLabel() . '.'
						: null)
					->extraAttributes(fn (Post $record): array => $record->isTranslationPlaceholder()
						? ['style' => 'cursor: default; text-decoration: none; opacity: .55;']
						: [
							'style' => 'cursor: pointer; text-decoration: none;',
							'onmouseover' => 'this.style.textDecoration="underline"',
							'onmouseout' => 'this.style.textDecoration="none"',
						]),
				Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
					->searchable()
					->wrap()
					->extraAttributes(fn (Post $record): array => self::placeholderCellAttributes($record)),
                Tables\Columns\TextColumn::make('locale')
                    ->label('Язык')
                    ->badge()
                    ->formatStateUsing(fn (string $state, Post $record): string => $record->isTranslationPlaceholder()
						? $record->localeLabel($record->selectedLocale()) . ': клонировать'
						: $record->localeLabel($state))
                    ->color(fn (string $state, Post $record): string => self::localeColor(
						$record->isTranslationPlaceholder() ? $record->selectedLocale() : $state
					))
					->tooltip(fn (Post $record): ?string => $record->isTranslationPlaceholder()
						? 'Создать версию ' . $record->localeLabel($record->selectedLocale()) . ' на основе ' . $record->localeLabel()
						: null)
					->action(fn (Post $record) => $record->isTranslationPlaceholder()
						? redirect(self::getTranslationCreateUrl($record))
						: null),
				Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover image')
    				->getStateUsing(fn ($record) => $record->cover_url)
					->width(85)
   					->height(65)
					->extraAttributes(fn (Post $record): array => self::placeholderCellAttributes($record)),
				Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
					->options([
						'draft' => 'Черновик',
						'published' => 'Опубликован',
					])
					->selectablePlaceholder(false)
					->disabled(fn (Post $record): bool => $record->isTranslationPlaceholder())
					->extraAttributes(fn (Post $record): array => self::placeholderCellAttributes($record)),
				Tables\Columns\TextColumn::make('created_at')
					->dateTime()
					->sortable()
					->toggleable(isToggledHiddenByDefault: true),
				Tables\Columns\TextColumn::make('updated_at')
					->label('Обновлено')
					->dateTime()
					->sortable()
					->extraAttributes(fn (Post $record): array => self::placeholderCellAttributes($record)),
					// ->toggleable(isToggledHiddenByDefault: true),
			])
			->recordClasses(fn (Post $record): ?string => $record->isTranslationPlaceholder()
				? 'bg-warning-50 dark:bg-warning-950/20'
				: null)
			->recordUrl(
				fn (Post $record): ?string => $record->isTranslationPlaceholder()
					? null
					: Pages\EditPost::getUrl(['record' => $record])
			)
			->filters([
				//
			])
			->actions([
				Tables\Actions\EditAction::make()
					->visible(fn (Post $record): bool => ! $record->isTranslationPlaceholder()),
				self::getTrashAction('Posts')
					->visible(fn (Post $record): bool => ! $record->isTranslationPlaceholder()),
				Tables\Actions\Action::make('view')
					->label('Просмотр')
					->icon('heroicon-o-arrow-top-right-on-square')
					->url(fn ($record) => '/' . $record->locale . '/blog/' . $record->slug)
					->openUrlInNewTab()
					->color('gray')
					->visible(fn (Post $record): bool => ! $record->isTranslationPlaceholder()),
			])
			->bulkActions([
				Tables\Actions\BulkActionGroup::make([
					self::getTrashBulkAction('Posts'),
				]),
			])
			->checkIfRecordIsSelectableUsing(fn (Post $record): bool => ! $record->isTranslationPlaceholder());
	}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getTranslationCreateUrl(Post $source): string
    {
        $targetLocale = $source->selectedLocale();

        $existingTranslation = $source->translation_group_id
            ? Post::query()
                ->where('translation_group_id', $source->translation_group_id)
                ->where('locale', $targetLocale)
                ->first()
            : null;

        if ($existingTranslation) {
            return Pages\EditPost::getUrl(['record' => $existingTranslation]);
        }

        return Pages\CreatePost::getUrl([
            'clone_from' => $source->getKey(),
            'locale' => $targetLocale,
        ]);
    }

    private static function localeColor(string $locale): string
    {
        return match ($locale) {
            'ru' => 'danger',
            'en' => 'info',
            'sr' => 'success',
            default => 'gray',
        };
    }

    private static function placeholderCellAttributes(Post $record): array
    {
        return $record->isTranslationPlaceholder()
            ? ['style' => 'opacity: .55;']
            : [];
    }

    public static function uniqueSlugForLocale(string $slug, string $locale): string
    {
        $baseSlug = Str::slug($slug) ?: 'post';
        $uniqueSlug = $baseSlug;
        $suffix = 2;

        while (Post::query()->where('locale', $locale)->where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $uniqueSlug;
    }

	

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
