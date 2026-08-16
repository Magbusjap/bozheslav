<?php

namespace App\Providers\Filament;

use Filament\View\PanelsRenderHook;
use Awcodes\Curator\CuratorPlugin;
use Illuminate\Support\Facades\Vite;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('magbusjap')
            ->login()
            ->profile(isSimple: false)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandName(fn() => \App\Models\Option::get('admin_name', 'Админка'))
            ->brandLogo(fn() => \App\Models\Option::get('admin_logo')
                ? asset('storage/' . \App\Models\Option::get('admin_logo'))
                : null)
            ->brandLogoHeight('2rem')
            ->plugins([
                CuratorPlugin::make(),
            ])
            ->renderHook(
                'panels::head.end',
                fn () => '<link rel="stylesheet" href="/css/filament/media.css">' . Vite::withEntryPoints(['resources/js/mind-maps.js'])->toHtml(),
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn () => <<<'HTML'
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pingUrl = '/magbusjap/session/keepalive';
        const pingIntervalMs = 5 * 60 * 1000;

        const pingSession = () => {
            fetch(pingUrl, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                cache: 'no-store',
            }).catch(() => {});
        };

        pingSession();

        window.setInterval(() => {
            if (document.visibilityState === 'visible') {
                pingSession();
            }
        }, pingIntervalMs);

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                pingSession();
            }
        });
    });
</script>
HTML,
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->navigationGroups([
                'Портфолио',
                'Аналитика',
                'Мониторинг',
                'Рассылки',
                'Боты и AI',
                'Профиль',
                'Настройки',
                'Система',
                'Информация',
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
