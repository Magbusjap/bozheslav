<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

// Home
Route::get('/', function () {
    $posts = \App\Models\Post::where('status', 'published')
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    $projects = \App\Models\PortfolioProject::where('status', 'published')
        ->orderBy('sort_order')
        ->get();
    return view('index', compact('posts', 'projects'));
});

// Pages
Route::get('/skills', fn() => view('skills'));
Route::get('/portfolio', function () {
    $projects = \App\Models\PortfolioProject::where('status', 'published')
        ->orderBy('sort_order')
        ->get();
    $categories = \App\Models\PortfolioCategory::orderBy('sort_order')->get();
    $categories = \App\Models\PortfolioCategory::where('status', 'published')
        ->orderBy('sort_order')
        ->get();
    return view('portfolio', compact('projects', 'categories'));
});
Route::get('/experience', fn() => view('experience'));
Route::get('/contacts', fn() => view('contacts'));

// Blog — post list
Route::get('/blog', function () {
    $posts = \App\Models\Post::where('status', 'published')
        ->orderBy('created_at', 'desc')
        ->get();
    $categories = \App\Models\Category::all();
    return view('blog', compact('posts', 'categories'));
});


// Blog — solo article
Route::get('/blog/{slug}', function ($slug) {
    $query = \App\Models\Post::where('slug', $slug);
    
    if (!auth()->check()) {
        $query->where('status', 'published');
    }
    
    $post = $query->first();
    
    if (!$post) {
        abort(404);
    }
    
    return view('article', compact('post'));
});

// Portfolio Project Pages
Route::get('/portfolio/pages/{slug}', function ($slug) {
    $query = \App\Models\PortfolioPage::where('slug', $slug);

    if (!auth()->check()) {
        $query->where('status', 'published');
    }

    $page = $query->first();

    if (!$page) {
        abort(404);
    }

    $related = \App\Models\PortfolioProject::where('status', 'published')
        ->inRandomOrder()
        ->take(10)
        ->get();

    return view('page', compact('page', 'related'));
});

// Page two
Route::get('/{slug}', function ($slug) {
    $query = \App\Models\Page::where('slug', $slug);
    
    if (!auth()->check()) {
        $query->where('status', 'published');
    }
    
    $page = $query->first();
    
    if (!$page) {
        abort(404);
    }
    
    return view('page', compact('page'));
});

// Feedback
Route::post('/contacts', function (\Illuminate\Http\Request $request) {

    $request->validate([
        'name'    => 'required|string|max:100',
        'email'   => 'required|email|max:100',
        'subject' => 'required|string|max:200',
        'message' => 'nullable|string|max:2000',
        'honeypot' => 'max:0', // защита от спамботов
    ]);

    \Illuminate\Support\Facades\Mail::to('i@mankudinov.ru')
        ->send(new \App\Mail\ContactFormMail(
            senderName:   $request->name,
            senderEmail:  $request->email,
            mailSubject:  $request->subject ?? 'Без темы',
            mailMessage:  $request->message,
        ));


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
});

// Resume
Route::get('/resume/download', [App\Http\Controllers\ResumeController::class, 'download'])
    ->name('resume.download');


// hh_parser
Route::get('/api/vacancies', [App\Http\Controllers\ParserController::class, 'search']);


