<x-filament-panels::page>
    @php
        $stats = $plane['stats'] ?? [];
        $projects = $plane['projects'] ?? [];
        $issues = $plane['issues'] ?? [];
        $overdueIssues = $plane['overdue_issues'] ?? [];
        $cycles = $plane['cycles'] ?? [];
        $modules = $plane['modules'] ?? [];
        $views = $plane['views'] ?? [];
    @endphp

    <div class="space-y-6">
        <x-filament::section heading="Plane Workspace" icon="heroicon-o-clipboard-document-list">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Поддомен</p>
                    <p class="text-lg font-semibold">{{ $plane['plane_url'] ?? 'https://plane.bozheslav.com' }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Workspace: {{ $plane['workspace_slug'] ?: 'не указан' }}
                    </p>
                </div>

                <x-filament::button
                    tag="a"
                    href="{{ $plane['plane_url'] ?? 'https://plane.bozheslav.com' }}"
                    target="_blank"
                    icon="heroicon-m-arrow-top-right-on-square"
                >
                    Открыть Plane
                </x-filament::button>
            </div>

            @if (!($plane['is_configured'] ?? false))
                <div class="mt-4 rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-800 dark:border-yellow-900 dark:bg-yellow-950 dark:text-yellow-200">
                    Для статистики нужно указать `PLANE_API_KEY` и `PLANE_WORKSPACE_SLUG` в `.env` проекта `cms`.
                    @if (!empty($plane['error']))
                        <div class="mt-2 font-mono text-xs">{{ $plane['error'] }}</div>
                    @endif
                </div>
            @endif
        </x-filament::section>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Проекты</p>
                <p class="mt-2 text-3xl font-bold">{{ $stats['projects'] ?? 0 }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Открытые задачи</p>
                <p class="mt-2 text-3xl font-bold">{{ $stats['issues_open'] ?? 0 }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">В работе</p>
                <p class="mt-2 text-3xl font-bold">{{ $stats['issues_in_progress'] ?? 0 }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Завершено за 7 дней</p>
                <p class="mt-2 text-3xl font-bold">{{ $stats['issues_completed_week'] ?? 0 }}</p>
            </x-filament::section>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Просрочено</p>
                <p class="mt-2 text-3xl font-bold text-danger-600 dark:text-danger-400">{{ $stats['issues_overdue'] ?? 0 }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Циклы</p>
                <p class="mt-2 text-3xl font-bold">{{ $stats['cycles'] ?? 0 }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Модули</p>
                <p class="mt-2 text-3xl font-bold">{{ $stats['modules'] ?? 0 }}</p>
            </x-filament::section>
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">Views</p>
                <p class="mt-2 text-3xl font-bold">{{ $stats['views'] ?? 0 }}</p>
            </x-filament::section>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <x-filament::section heading="Проекты">
                <div class="space-y-3">
                    @forelse($projects as $project)
                        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold">{{ $project['name'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $project['identifier'] ?: 'без идентификатора' }}</p>
                                </div>
                                @if (!empty($project['url']))
                                    <x-filament::link :href="$project['url']" target="_blank" icon="heroicon-m-arrow-top-right-on-square">Открыть</x-filament::link>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Проекты пока не загружены.</p>
                    @endforelse
                </div>
            </x-filament::section>

            <x-filament::section heading="Последние задачи">
                <div class="space-y-3">
                    @forelse($issues as $issue)
                        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold">{{ $issue['identifier'] ?: $issue['name'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $issue['name'] }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $issue['project_name'] }} · {{ $issue['state_name'] }} · {{ $issue['updated_at'] ?? '—' }}
                                    </p>
                                </div>
                                @if (!empty($issue['url']))
                                    <x-filament::link :href="$issue['url']" target="_blank" icon="heroicon-m-arrow-top-right-on-square">Открыть</x-filament::link>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Задачи пока не загружены.</p>
                    @endforelse
                </div>
            </x-filament::section>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <x-filament::section heading="Просроченные задачи">
                <div class="space-y-3">
                    @forelse($overdueIssues as $issue)
                        <div class="rounded-xl border border-danger-200 bg-danger-50 p-4 dark:border-danger-900 dark:bg-danger-950">
                            <p class="font-semibold">{{ $issue['identifier'] ?: $issue['name'] }}</p>
                            <p class="text-sm">{{ $issue['name'] }}</p>
                            <p class="mt-1 text-xs text-danger-700 dark:text-danger-300">
                                {{ $issue['project_name'] }} · дедлайн {{ $issue['target_date'] ?? '—' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Просроченных задач нет.</p>
                    @endforelse
                </div>
            </x-filament::section>

            <x-filament::section heading="Циклы">
                <div class="space-y-3">
                    @forelse($cycles as $cycle)
                        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                            <p class="font-semibold">{{ $cycle['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cycle['project_name'] }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Циклы пока не загружены.</p>
                    @endforelse
                </div>
            </x-filament::section>

            <x-filament::section heading="Модули и views">
                <div class="space-y-3">
                    @forelse($modules as $module)
                        <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-800">
                            <p class="font-semibold">{{ $module['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $module['project_name'] }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Модули пока не загружены.</p>
                    @endforelse

                    @if(count($views))
                        <div class="pt-2">
                            <p class="mb-2 text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Views</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($views as $view)
                                    @if (!empty($view['url']))
                                        <a href="{{ $view['url'] }}" target="_blank" class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                                            {{ $view['name'] }}
                                        </a>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-200">
                                            {{ $view['name'] }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
