<?php

namespace App\Filament\Resources;

// MJML
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\TextInput;
// end MJML
use Awcodes\Curator\Components\Forms\CuratorPicker;
use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use App\Traits\HasTrashAction;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;

class PageResource extends Resource
{
    use HasTrashAction;

    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationLabel = 'Страницы';
    protected static ?string $modelLabel = 'Страница';
    protected static ?string $pluralModelLabel = 'Страницы';
    protected static ?string $slug = 'site-pages';

    public static function form(Form $form): Form
    {
        return $form->schema([
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
                ->unique(ignoreRecord: true)
                ->prefix('/'),
            Forms\Components\Select::make('status')
                ->label('Статус')
                ->options([
                    'published' => 'Опубликована',
                    'draft'     => 'Черновик',
                ])
                ->default('published')
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
                    ->extraAttributes(['style' => 'cursor: pointer; text-decoration: none;'])
                    ->extraAttributes(['onmouseover' => 'this.style.textDecoration="underline"',
                     'onmouseout' => 'this.style.textDecoration="none"']),
                Tables\Columns\TextColumn::make('slug')
                    ->label('URL')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Статус')
                    ->options([
                        'published' => 'Опубликована',
                        'draft'     => 'Черновик',
                    ]),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Обновлено')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordUrl(
                fn ($record) => Pages\EditPage::getUrl(['record' => $record])
            )
            ->actions([
                Tables\Actions\EditAction::make(),
                self::getTrashAction('Страницы'),
                Tables\Actions\Action::make('view')
                    ->label('Просмотр')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => '/' . $record->slug)
                    ->openUrlInNewTab()
                    ->color('gray'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ManagePages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit'   => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}