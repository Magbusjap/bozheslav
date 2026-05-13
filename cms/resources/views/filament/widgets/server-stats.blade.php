<x-filament-widgets::widget>
    <x-filament::section heading="Сервер" icon="heroicon-o-server">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- ОЗУ --}}
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">ОЗУ</p>
                <p class="text-2xl font-bold">{{ $memUsed }} / {{ $memTotal }} МБ</p>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $memPercent > 80 ? 'bg-red-500' : 'bg-primary-500' }}"
                         style="width: {{ $memPercent }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $memPercent }}% использовано</p>
            </div>

            {{-- Диск --}}
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Диск</p>
                <p class="text-2xl font-bold">{{ $diskUsed }} / {{ $diskTotal }} ГБ</p>
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $diskPercent > 80 ? 'bg-red-500' : 'bg-primary-500' }}"
                         style="width: {{ $diskPercent }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $diskPercent }}% использовано</p>
            </div>

            {{-- Безопасность --}}
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Попытки взлома</p>
                <p class="text-2xl font-bold {{ $bruteForce > 100 ? 'text-red-500' : 'text-green-500' }}">
                    {{ number_format($bruteForce) }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Failed password в auth.log</p>
            </div>

        </div>

        {{-- Установленное ПО --}}
        @if(count($software) > 0)
        <div class="mt-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Установленное ПО</p>
            <div class="flex flex-wrap gap-2">
                @foreach($software as $sw)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    {{ $sw['name'] }} {{ $sw['version'] }}
                </span>
                @endforeach
            </div>
        </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>