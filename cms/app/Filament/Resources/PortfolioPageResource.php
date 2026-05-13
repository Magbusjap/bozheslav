<?php

namespace App\Filament\Resources;

// MJML
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\TextInput;
// end MJML
use App\Filament\Resources\PortfolioPageResource\Pages;
use App\Filament\Resources\Concerns\HasTranslatableResource;
use App\Models\PortfolioPage;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Traits\HasTrashAction;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;

class PortfolioPageResource extends Resource
{
    use HasTrashAction;
    use HasTranslatableResource;

    protected static ?string $model = PortfolioPage::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Page-проекты';
    protected static ?string $modelLabel = 'Страница проекта';
    protected static ?string $pluralModelLabel = 'Страницы проектов';
    protected static ?string $navigationGroup = 'Портфолио';
    protected static ?int $navigationSort = 4;
    protected static ?string $slug = 'portfolio-pages';

    public static function form(Form $form): Form
    {
        return $form->schema([
            self::localeFormSelect(),
            Forms\Components\TextInput::make('title')
                ->label('Заголовок')
                ->required()
                ->maxLength(255)
                ->live(debounce: 800)
                ->afterStateUpdated(function (?string $state, callable $set) {
                    if (!empty($state)) {
                        $set('slug', \Illuminate\Support\Str::slug(transliterate($state)));
                    }
                }),
            Forms\Components\TextInput::make('slug')
                ->label('URL')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true, modifyRuleUsing: self::slugUniqueRule())
                ->prefix('/'),
            Forms\Components\Select::make('status')
                ->label('Статус')
                ->options([
                    'published' => 'Опубликована',
                    'draft'     => 'Черновик',
                ])
                ->default('draft')
                ->required(),
            Forms\Components\Textarea::make('excerpt')
                ->label('Описание')
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
                                ->label('Расположение')
                                ->options(['left' => 'Слева', 'right' => 'Справа'])
                                ->default('left')
                                ->required(),
                            Forms\Components\TextInput::make('width')
                                ->label('Ширина изображения (px)')
                                ->numeric()
                                ->default(300),
                            TiptapEditor::make('text')
                                ->label('Текст')
                                ->required(),
                        ]),
                    Forms\Components\Builder\Block::make('before_after')
                        ->label('Before | After')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->schema([
                            CuratorPicker::make('before_url')
                                ->label('Изображение ДО')
                                ->buttonLabel('Выбрать До')
                                ->required(),
                            CuratorPicker::make('after_url')
                                ->label('Изображение ПОСЛЕ')
                                ->buttonLabel('Выбрать После')
                                ->required(),
                            Forms\Components\TextInput::make('height')
                                ->label('Высота блока (px)')
                                ->numeric()
                                ->default(500)
                                ->suffix('px'),

                            Forms\Components\TextInput::make('width')
                                ->label('Ширина (px или %)')
                                ->default('100%')
                                ->helperText('Например: 800px или 70%'),
                        ]),
                    Forms\Components\Builder\Block::make('parser')
                        ->label('AJAX-парсер')
                        ->icon('heroicon-o-table-cells')
                        ->schema([
                            Forms\Components\TextInput::make('endpoint')
                                ->label('Endpoint (URL)')
                                ->placeholder('/api/vacancies')
                                ->required(),
                            Forms\Components\TextInput::make('param')
                                ->label('Параметр запроса')
                                ->placeholder('query')
                                ->default('query')
                                ->required(),
                            Forms\Components\TextInput::make('data_key')
                                ->label('Ключ массива в JSON-ответе')
                                ->placeholder('vacancies')
                                ->required(),
                            Forms\Components\TextInput::make('placeholder')
                                ->label('Placeholder поля поиска')
                                ->placeholder('Введите запрос...'),
                            Forms\Components\TextInput::make('search_label')
                                ->label('Текст кнопки')
                                ->default('Найти'),
                        ]),
                    Forms\Components\Builder\Block::make('mjml_workspace')
                        ->label('Email Конструктор (MJML)')
                        ->icon('heroicon-o-envelope')
                        ->schema([
                            Tabs::make('Email Editor')
                                ->tabs([
                                    // Вкладка с полями ввода
                                    Tabs\Tab::make('Рабочая область')
                                        ->icon('heroicon-m-pencil-square')
                                        ->schema([
                                            Forms\Components\TextInput::make('project_title')
                                                ->label('Название макета')
                                                ->placeholder('Например: Taskduck Promo'),
                                            
                                            CuratorPicker::make('images')
                                                ->label('Медиа-файлы проекта')
                                                ->multiple()
                                                ->buttonLabel('Добавить картинки'),

                                            Forms\Components\Textarea::make('html_content')
                                                ->label('HTML код письма')
                                                ->rows(15)
                                                ->columnSpanFull()
                                                ->live(onBlur: true) // Превью обновится, когда ты переключишь вкладку
                                                ->helperText('Вставь сюда скомпилированный HTML из MJML'),
                                        ]),

                                    // Вкладка с живым просмотром
                                    Tabs\Tab::make('Предпросмотр')
                                        ->icon('heroicon-m-eye')
                                        ->schema([
                                            ViewField::make('html_content') // Используем то же имя поля, чтобы подхватить данные
                                                ->view('filament.forms.components.email-preview-iframe')
                                        ]),
                                ])
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpanFull(),
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
                    ->label('Заголовок')
                    ->searchable()
                    ->wrap()
                    ->description(fn (PortfolioPage $record): ?string => self::placeholderDescription($record))
                    ->url(fn (PortfolioPage $record): ?string => $record->isTranslationPlaceholder()
                        ? null
                        : Pages\EditPortfolioPage::getUrl(['record' => $record])),
                Tables\Columns\TextColumn::make('slug')
                    ->label('URL')
                    ->searchable()
                    ->wrap()
                    ->extraAttributes(fn (PortfolioPage $record): array => self::placeholderCellAttributes($record)),
                self::localeTableColumn(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'published' => 'Опубликована',
                        'draft'     => 'Черновик',
                    ])
                    ->disabled(fn (PortfolioPage $record): bool => $record->isTranslationPlaceholder()),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime()
                    ->sortable()
                    ->extraAttributes(fn (PortfolioPage $record): array => self::placeholderCellAttributes($record)),
            ])
            ->recordClasses(fn (PortfolioPage $record): ?string => $record->isTranslationPlaceholder()
                ? 'bg-warning-50 dark:bg-warning-950/20'
                : null)
            ->recordUrl(
                fn (PortfolioPage $record): ?string => self::translatableRecordUrl($record)
            )
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (PortfolioPage $record): bool => ! $record->isTranslationPlaceholder()),
                self::getTrashAction('Page-проекты')
                    ->visible(fn (PortfolioPage $record): bool => ! $record->isTranslationPlaceholder()),
                Tables\Actions\Action::make('view')
                    ->label('Просмотр')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (PortfolioPage $record) => '/' . $record->locale . '/portfolio/pages/' . $record->slug)
                    ->openUrlInNewTab()
                    ->color('gray')
                    ->visible(fn (PortfolioPage $record): bool => ! $record->isTranslationPlaceholder())
            ]);
    }

    public static function translationCloneFields(): array
    {
        return [
            'title',
            'slug',
            'status',
            'excerpt',
            'content',
            'seo_title',
            'seo_description',
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPortfolioPages::route('/'),
            'create' => Pages\CreatePortfolioPage::route('/create'),
            'edit'   => Pages\EditPortfolioPage::route('/{record}/edit'),
        ];
    }
}
