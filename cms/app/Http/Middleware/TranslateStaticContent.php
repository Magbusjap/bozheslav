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

        $content = $response->getContent();
        if (! is_string($content) || $content === '') {
            return $response;
        }

        $locale = App::getLocale();
        $replacements = trans('site.replacements');
        if (! is_array($replacements)) {
            $replacements = [];
        }

        $content = str_replace('<html lang="ru">', '<html lang="' . e($locale) . '">', $content);
        if ($replacements !== []) {
            $content = strtr($content, $replacements);
        }

        $response->setContent($this->injectClientTranslations($content, $locale, $replacements));

        return $response;
    }

    private function shouldTranslate(Request $request, Response $response): bool
    {
        if (! $response->isSuccessful()) {
            return false;
        }

        $path = trim($request->path(), '/');
        foreach (['admin', 'magbusjap', 'api', 'livewire', 'storage', 'icons', 'css', 'js', 'images', 'build', 'vendor'] as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return false;
            }
        }

        if (str_starts_with($path, 'blog/')) {
            return false;
        }

        return str_contains((string) $response->headers->get('Content-Type'), 'text/html');
    }

    private function injectClientTranslations(string $content, string $locale, array $replacements): string
    {
        if (! str_contains($content, '</head>')) {
            return $content;
        }

        $payload = [
            'locale' => $locale,
            'replacements' => $replacements,
            'client' => trans('site.client'),
        ];

        $json = json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
        );

        if (! is_string($json)) {
            return $content;
        }

        $script = "<script>window.SITE_I18N={$json};</script>\n";

        return str_replace('</head>', $script . '</head>', $content);
    }
}
