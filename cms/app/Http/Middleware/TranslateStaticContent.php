<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class TranslateStaticContent
{
    private const BLADE_FILES = [
        'common-blade',
        'header-blade',
        'footer-blade',
        'index-blade',
        'blog-page-blade',
        'article-page-blade',
        'contact-page-blade',
        'page-blade',
        'skills-blade',
        'experience-blade',
        'portfolio-blade',
        'errors-blade',
        'legal-page-blade',
    ];

    private const JS_FILES = [
        'header-js',
        'cookie-banner-js',
        'skill-levels-js',
        'modal-skills-js',
        'experience-page-js',
        'not-found-js',
        'typewriter-js',
    ];

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
        $replacements = $this->collectBladeReplacements();

        $content = str_replace('<html lang="ru">', '<html lang="' . e($locale) . '">', $content);
        if ($replacements !== [] && $this->shouldApplyServerReplacements($request)) {
            $content = strtr($content, $replacements);
        }

        $response->setContent($this->injectClientTranslations($content, $locale, $replacements));

        return $response;
    }

    private function shouldTranslate(Request $request, Response $response): bool
    {
        if (! $response->isSuccessful() && $response->getStatusCode() !== 404) {
            return false;
        }

        $path = trim($request->path(), '/');
        foreach (['admin', 'magbusjap', 'api', 'livewire', 'storage', 'icons', 'css', 'js', 'images', 'build', 'vendor'] as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return false;
            }
        }

        return str_contains((string) $response->headers->get('Content-Type'), 'text/html');
    }

    private function shouldApplyServerReplacements(Request $request): bool
    {
        $path = trim($request->path(), '/');
        $pathWithoutLocale = preg_replace('#^(ru|en|sr)(/|$)#', '', $path);

        if (! is_string($pathWithoutLocale)) {
            return true;
        }

        if ($pathWithoutLocale === '' || in_array($pathWithoutLocale, [
            'skills',
            'portfolio',
            'experience',
            'contacts',
            'blog',
            'privacy',
            'o-razrabotchike',
        ], true)) {
            return true;
        }

        if (str_starts_with($pathWithoutLocale, 'blog/')) {
            return false;
        }

        if (str_starts_with($pathWithoutLocale, 'portfolio/pages/')) {
            return false;
        }

        return false;
    }

    private function injectClientTranslations(string $content, string $locale, array $replacements): string
    {
        if (! str_contains($content, '</head>')) {
            return $content;
        }

        $payload = [
            'locale' => $locale,
            'replacements' => $replacements,
            'js' => $this->collectSection('js', self::JS_FILES),
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

    /**
     * @param  array<int, string>  $files
     * @return array<string, mixed>
     */
    private function collectSection(string $section, array $files): array
    {
        $collected = [];

        foreach ($files as $file) {
            $values = trans($file . '.' . $section);

            if (is_array($values)) {
                $collected = array_replace_recursive($collected, $values);
            }
        }

        return $collected;
    }

    /**
     * @return array<string, string>
     */
    private function collectBladeReplacements(): array
    {
        $replacements = [];

        foreach (self::BLADE_FILES as $file) {
            $blade = trans($file . '.blade');
            if (is_array($blade)) {
                $replacements = array_replace($replacements, $this->flattenStringMap($blade));
            }

            $legacy = trans($file . '.replacements');
            if (is_array($legacy)) {
                $replacements = array_replace($replacements, $legacy);
            }
        }

        return $replacements;
    }

    /**
     * @param  array<mixed>  $values
     * @return array<string, string>
     */
    private function flattenStringMap(array $values): array
    {
        $flat = [];

        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $flat = array_replace($flat, $this->flattenStringMap($value));
                continue;
            }

            if (is_string($key) && is_string($value)) {
                $flat[$key] = $value;
            }
        }

        return $flat;
    }
}
