<x-filament-panels::page>
    @php
        $server = $affine['server'] ?? [];
        $upgrade = $affine['available_upgrade'] ?? null;
    @endphp

    <div class="space-y-6">
        <x-filament::section heading="AFFiNE Workspace" icon="heroicon-o-document-duplicate">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Поддомен</p>
                    <p class="text-lg font-semibold">{{ $affine['affine_url'] ?? 'https://affine.bozheslav.com' }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Сервер: {{ $server['name'] ?? 'AFFiNE' }}
                    </p>
                </div>

                <x-filament::button
                    tag="a"
                    href="{{ $affine['affine_url'] ?? 'https://affine.bozheslav.com' }}"
                    target="_blank"
                    icon="heroicon-m-arrow-top-right-on-square"
                >
                    Открыть AFFiNE
                </x-filament::button>
            </div>

            @if (!($affine['is_online'] ?? false))
                <div class="mt-4 rounded-xl border border-danger-200 bg-danger-50 p-4 text-sm text-danger-800 dark:border-danger-900 dark:bg-danger-950 dark:text-danger-200">
                    AFFiNE пока недоступен по сети или еще не завершил запуск.
                    @if (!empty($affine['error']))
                        <div class="mt-2 font-mono text-xs">{{ $affine['error'] }}</div>
                    @endif
                </div>
            @elseif (!($server['initialized'] ?? false))
                <div class="mt-4 rounded-xl border border-warning-200 bg-warning-50 p-4 text-sm text-warning-800 dark:border-warning-900 dark:bg-warning-950 dark:text-warning-200">
                    AFFiNE уже запущен, но первичная настройка еще не завершена. При открытии сервиса вас перенаправит на `/admin/setup`.
                </div>
            @endif
        </x-filament::section>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Статус</p>
                <p class="mt-2 text-3xl font-bold {{ ($affine['is_online'] ?? false) ? 'text-success-600 dark:text-success-400' : 'text-danger-600 dark:text-danger-400' }}">
                    {{ ($affine['is_online'] ?? false) ? 'Online' : 'Offline' }}
                </p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Версия</p>
                <p class="mt-2 text-3xl font-bold">{{ $server['version'] ?? 'unknown' }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Тип</p>
                <p class="mt-2 text-3xl font-bold">{{ $server['type'] ?? 'unknown' }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Инициализирован</p>
                <p class="mt-2 text-3xl font-bold">{{ ($server['initialized'] ?? false) ? 'Да' : 'Нет' }}</p>
            </x-filament::section>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-filament::section heading="Конфигурация">
                <div class="space-y-3 text-sm">
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                        <p class="font-semibold">Base URL</p>
                        <p class="mt-1 text-gray-600 dark:text-gray-300">{{ $server['base_url'] ?? ($affine['affine_url'] ?? '—') }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                        <p class="font-semibold">Требования к паролю</p>
                        <p class="mt-1 text-gray-600 dark:text-gray-300">
                            Минимум: {{ $server['password_min'] ?? 0 }}, максимум: {{ $server['password_max'] ?? 0 }}
                        </p>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section heading="Обновление">
                @if ($upgrade)
                    <div class="rounded-xl border border-warning-200 bg-warning-50 p-4 dark:border-warning-900 dark:bg-warning-950">
                        <p class="font-semibold">Доступна новая версия: {{ $upgrade['version'] }}</p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $upgrade['published_at'] ?: 'Дата не указана' }}</p>
                        @if (!empty($upgrade['url']))
                            <div class="mt-3">
                                <x-filament::link :href="$upgrade['url']" target="_blank" icon="heroicon-m-arrow-top-right-on-square">
                                    Посмотреть релиз
                                </x-filament::link>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-400">Новых обновлений сейчас не найдено.</p>
                @endif
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
