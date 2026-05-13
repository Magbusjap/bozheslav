<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class TranslateStaticContent
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->shouldTranslate($request, $response)) {
            return $response;
        }

        $locale = App::getLocale();
        if ($locale === 'ru') {
            return $response;
        }

        $replacements = trans('site.replacements');
        if (! is_array($replacements) || $replacements === []) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || $content === '') {
            return $response;
        }

        $content = str_replace('<html lang="ru">', '<html lang="' . e($locale) . '">', $content);
        $response->setContent(strtr($content, $replacements));

        return $response;
    }

    private function shouldTranslate(Request $request, Response $response): bool
    {
        if (! $response->isSuccessful()) {
            return false;
        }

        $path = trim($request->path(), '/');
        foreach (['magbusjap', 'api', 'livewire', 'storage', 'icons', 'css', 'js', 'images', 'build', 'vendor'] as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return false;
            }
        }

        if ($path === 'portfolio' || str_starts_with($path, 'portfolio/')) {
            return false;
        }

        if (str_starts_with($path, 'blog/')) {
            return false;
        }

        return str_contains((string) $response->headers->get('Content-Type'), 'text/html');
    }
}
