<?php

namespace App\Filament\Widgets;

use App\Models\Option;
use Filament\Widgets\Widget;

class StackInfoWidget extends Widget
{
    protected static string $view = 'filament.widgets.stack-info';
    protected static ?int $sort = 10;
    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        $nginxVersion = trim(@shell_exec('nginx -v 2>&1 | grep -oP "[\d.]+"') ?? '—');

        return [
            'phpVersion'       => phpversion(),
            'laravelVersion'   => app()->version(),
            'filamentVersion'  => \Composer\InstalledVersions::getVersion('filament/filament') ?? '—',
            'nginxVersion'     => $nginxVersion ?: '—',
            'phpDocsUrl'       => Option::get('php_docs_url', 'https://www.php.net/manual/ru/'),
            'phpLogo'          => Option::get('php_logo') ? asset('storage/' . Option::get('php_logo')) : null,
            'laravelDocsUrl'   => Option::get('laravel_docs_url', 'https://laravel.su/docs'),
            'laravelGithubUrl' => Option::get('laravel_github_url', 'https://github.com/laravel/laravel'),
            'laravelLogo'      => Option::get('laravel_logo') ? asset('storage/' . Option::get('laravel_logo')) : null,
            'filamentDocsUrl'  => Option::get('filament_docs_url', 'https://filamentphp.com/docs'),
            'filamentGithubUrl'=> Option::get('filament_github_url', 'https://github.com/filamentphp/filament'),
            'filamentLogo'     => Option::get('filament_logo') ? asset('storage/' . Option::get('filament_logo')) : null,
            'nginxLogo'        => Option::get('nginx_logo') ? asset('storage/' . Option::get('nginx_logo')) : null,
        ];
    }
}