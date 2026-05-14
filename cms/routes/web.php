<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

$siteLocales = ['ru', 'en', 'sr'];

if (! function_exists('contactCaptcha')) {
    function contactCaptcha(): array
    {
        $left = random_int(2, 9);
        $right = random_int(2, 9);

        session(['contact_captcha_answer' => $left + $right]);

        return [
            'question' => "{$left} + {$right}",
        ];
    }
}

$home = function () {
    $posts = \App\Models\Post::where('status', 'published')
        ->when(Schema::hasColumn('posts', 'locale'), fn ($query) => $query->where('locale', app()->getLocale()))
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    $projects = \App\Models\PortfolioProject::where('status', 'published')
        ->when(Schema::hasColumn('portfolio_projects', 'locale'), fn ($query) => $query->where('locale', app()->getLocale()))
        ->orderBy('sort_order')
        ->get();
    $contactCaptcha = contactCaptcha();

    return view('index', compact('posts', 'projects', 'contactCaptcha'));
};

$skills = fn() => view('skills');

$portfolio = function () {
    $projects = \App\Models\PortfolioProject::where('status', 'published')
        ->when(Schema::hasColumn('portfolio_projects', 'locale'), fn ($query) => $query->where('locale', app()->getLocale()))
        ->orderBy('sort_order')
        ->get();
    $categories = \App\Models\PortfolioCategory::where('status', 'published')
        ->when(Schema::hasColumn('portfolio_categories', 'locale'), fn ($query) => $query->where('locale', app()->getLocale()))
        ->orderBy('sort_order')
        ->get();
    return view('portfolio', compact('projects', 'categories'));
};

$experience = fn() => view('experience');
$contacts = fn() => view('contacts', ['contactCaptcha' => contactCaptcha()]);

$blog = function () {
    $posts = \App\Models\Post::where('status', 'published')
        ->when(Schema::hasColumn('posts', 'locale'), fn ($query) => $query->where('locale', app()->getLocale()))
        ->orderBy('created_at', 'desc')
        ->get();
    $categories = \App\Models\Category::query()
        ->when(Schema::hasColumn('categories', 'locale'), fn ($query) => $query->where('locale', app()->getLocale()))
        ->get();
    return view('blog', compact('posts', 'categories'));
};

$article = function (...$params) {
    $slug = end($params);
    $query = \App\Models\Post::where('slug', $slug)
        ->when(Schema::hasColumn('posts', 'locale'), fn ($query) => $query->where('locale', app()->getLocale()));
    
    if (!auth()->check()) {
        $query->where('status', 'published');
    }
    
    $post = $query->first();
    
    if (!$post) {
        $redirect = localizedRecordRedirect(\App\Models\Post::class, $slug, app()->getLocale(), fn ($record) => '/' . $record->locale . '/blog/' . $record->slug);
        if ($redirect) {
            return $redirect;
        }

        abort(404);
    }
    
    return view('article', compact('post'));
};

$portfolioPage = function (...$params) {
    $slug = end($params);
    $query = \App\Models\PortfolioPage::where('slug', $slug)
        ->when(Schema::hasColumn('portfolio_pages', 'locale'), fn ($query) => $query->where('locale', app()->getLocale()));

    if (!auth()->check()) {
        $query->where('status', 'published');
    }

    $page = $query->first();

    if (!$page) {
        $redirect = localizedRecordRedirect(\App\Models\PortfolioPage::class, $slug, app()->getLocale(), fn ($record) => '/' . $record->locale . '/portfolio/pages/' . $record->slug);
        if ($redirect) {
            return $redirect;
        }

        abort(404);
    }

    $related = \App\Models\PortfolioProject::where('status', 'published')
        ->when(Schema::hasColumn('portfolio_projects', 'locale'), fn ($query) => $query->where('locale', app()->getLocale()))
        ->inRandomOrder()
        ->take(10)
        ->get();

    return view('page', compact('page', 'related'));
};

$page = function (...$params) {
    $slug = end($params);
    $query = \App\Models\Page::where('slug', $slug)
        ->when(Schema::hasColumn('pages', 'locale'), fn ($query) => $query->where('locale', app()->getLocale()));
    
    if (!auth()->check()) {
        $query->where('status', 'published');
    }
    
    $page = $query->first();
    
    if (!$page) {
        $redirect = localizedRecordRedirect(\App\Models\Page::class, $slug, app()->getLocale(), fn ($record) => '/' . $record->locale . '/' . $record->slug);
        if ($redirect) {
            return $redirect;
        }

        abort(404);
    }
    
    return view('page', compact('page'));
};

if (! function_exists('localizedRecordRedirect')) {
    function localizedRecordRedirect(string $modelClass, string $slug, string $locale, callable $urlBuilder): ?\Illuminate\Http\RedirectResponse
    {
        $model = new $modelClass();

        if (! Schema::hasColumn($model->getTable(), 'locale') || ! Schema::hasColumn($model->getTable(), 'translation_group_id')) {
            return null;
        }

        $sourceQuery = $modelClass::query()
            ->where('slug', $slug)
            ->where('locale', '!=', $locale);

        if (! auth()->check() && Schema::hasColumn($model->getTable(), 'status')) {
            $sourceQuery->where('status', 'published');
        }

        $source = $sourceQuery->first();
        if (! $source?->translation_group_id) {
            return null;
        }

        $targetQuery = $modelClass::query()
            ->where('translation_group_id', $source->translation_group_id)
            ->where('locale', $locale);

        if (! auth()->check() && Schema::hasColumn($model->getTable(), 'status')) {
            $targetQuery->where('status', 'published');
        }

        $target = $targetQuery->first();

        return $target ? redirect($urlBuilder($target)) : null;
    }
}

$feedback = function (\Illuminate\Http\Request $request) {

    $request->validate([
        'name'    => 'required|string|max:100',
        'email'   => 'required|email|max:100',
        'subject' => 'required|string|max:200',
        'message' => 'nullable|string|max:2000',
        'honeypot' => 'max:0', // защита от спамботов
        'privacy_consent' => 'accepted',
        'captcha_answer' => 'required|integer',
    ]);

    if ((int) $request->input('captcha_answer') !== (int) session('contact_captcha_answer')) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'captcha_answer' => 'Проверьте защитный вопрос и попробуйте ещё раз.',
        ]);
    }

    session()->forget('contact_captcha_answer');

    try {
        \Illuminate\Support\Facades\Mail::to(option('contact_email', 'i@mankudinov.ru'))
            ->send(new \App\Mail\ContactFormMail(
                senderName:   $request->name,
                senderEmail:  $request->email,
                mailSubject:  $request->subject ?? 'Без темы',
                mailMessage:  $request->message,
            ));
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Contact form mail error: ' . $e->getMessage());
    }


    //  Send in the Telegram
    try {
    $text = "📩 Новое сообщение с bozheslav.com\n\n"
        . "👤 Имя: " . $request->name . "\n"
        . "📧 Email: " . $request->email . "\n"
        . "📌 Тема: " . ($request->subject ?? 'Без темы') . "\n"
        . "💬 Сообщение: " . ($request->message ?? 'Не указано');

    $response = Http::post(
        'https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/sendMessage',
        [
            'chat_id'    => env('TELEGRAM_CHAT_ID'),
            'text'       => $text,
            'parse_mode' => 'HTML',
        ]
    );
    \Illuminate\Support\Facades\Log::info('Telegram response: ' . $response->body());
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Telegram error: ' . $e->getMessage());
    }

    
    return response()->json(['success' => true]);
};

$siteRoutes = function () use ($home, $skills, $portfolio, $experience, $contacts, $blog, $article, $portfolioPage, $page, $feedback) {
    Route::get('/', $home);
    Route::get('/skills', $skills);
    Route::get('/portfolio', $portfolio);
    Route::get('/experience', $experience);
    Route::get('/contacts', $contacts);
    Route::post('/contacts', $feedback);
    Route::get('/blog', $blog);
    Route::get('/blog/{slug}', $article);
    Route::get('/portfolio/pages/{slug}', $portfolioPage);
    Route::get('/resume/download', [App\Http\Controllers\ResumeController::class, 'download']);
    Route::get('/{slug}', $page);
};

// hh_parser
Route::get('/api/vacancies', [App\Http\Controllers\ParserController::class, 'search']);

Route::prefix('{locale}')
    ->whereIn('locale', $siteLocales)
    ->group($siteRoutes);

$siteRoutes();
