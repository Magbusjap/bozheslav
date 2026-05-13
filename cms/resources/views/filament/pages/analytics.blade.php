<x-filament-panels::page>

    {{-- Period filter --}}
    <div class="flex gap-2 mb-6">
        @foreach(['1d' => 'Сегодня', '7d' => '7 дней', '30d' => '30 дней', 'all' => 'Всё время'] as $value => $label)
        <button
            wire:click="setPeriod('{{ $value }}')"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                {{ $period === $value
                    ? 'bg-primary-500 text-white'
                    : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- Summary stats --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <x-filament::section>
            <p class="text-sm text-gray-500">Всего посещений</p>
            <p class="text-3xl font-bold">{{ number_format($total) }}</p>
        </x-filament::section>
        <x-filament::section>
            <p class="text-sm text-gray-500">Уникальных IP</p>
            <p class="text-3xl font-bold">{{ number_format($unique) }}</p>
        </x-filament::section>
    </div>

    {{-- Visits by day --}}
    @if($byDay->count() > 0)
    <x-filament::section heading="Посещения по дням" icon="heroicon-o-chart-bar" class="mb-6">
        <div class="flex items-end gap-1 h-32">
            @php $max = $byDay->max('count') ?: 1; @endphp
            @foreach($byDay as $day)
            <div class="flex-1 flex flex-col items-center gap-1">
                <div class="w-full bg-primary-500 rounded-t"
                     style="height: {{ round(($day->count / $max) * 100) }}%"
                     title="{{ $day->date }}: {{ $day->count }}"></div>
                <span class="text-xs text-gray-400 rotate-45 origin-left" style="font-size:9px">
                    {{ \Carbon\Carbon::parse($day->date)->format('d.m') }}
                </span>
            </div>
            @endforeach
        </div>
    </x-filament::section>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        {{-- Top pages --}}
        <x-filament::section heading="Топ страниц" icon="heroicon-o-document-text">
            @forelse($topPages as $page)
            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
                <span class="text-sm font-mono text-gray-700 dark:text-gray-300 truncate max-w-xs">
                    /{{ $page->url }}
                </span>
                <span class="text-sm font-bold text-primary-500 ml-2">{{ $page->count }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400">Нет данных</p>
            @endforelse
        </x-filament::section>

        {{-- Countries --}}
        <x-filament::section heading="Страны" icon="heroicon-o-globe-alt">
            @forelse($countries as $country)
            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    {{ $country->country_name }}
                </span>
                <span class="text-sm font-bold text-primary-500">{{ $country->count }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400">Нет данных</p>
            @endforelse
        </x-filament::section>

        {{-- Cities --}}
        <x-filament::section heading="Города" icon="heroicon-o-map-pin">
            @forelse($cities as $city)
            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
                <span class="text-sm text-gray-700 dark:text-gray-300">
                    {{ $city->city }}
                    <span class="text-gray-400 text-xs">({{ $city->country }})</span>
                </span>
                <span class="text-sm font-bold text-primary-500">{{ $city->count }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400">Нет данных</p>
            @endforelse
        </x-filament::section>

        {{-- Browsers & Devices --}}
        <x-filament::section heading="Браузеры и устройства" icon="heroicon-o-device-phone-mobile">
            @forelse($browsers as $browser)
            <div class="flex justify-between items-center py-1">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $browser->browser }}</span>
                <span class="text-sm font-bold text-primary-500">{{ $browser->count }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400">Нет данных</p>
            @endforelse
            <hr class="my-3 border-gray-200 dark:border-gray-700">
            @foreach($devices as $device)
            <div class="flex justify-between items-center py-1">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $device->device_type }}</span>
                <span class="text-sm font-bold text-primary-500">{{ $device->count }}</span>
            </div>
            @endforeach
        </x-filament::section>

    </div>

</x-filament-panels::page>