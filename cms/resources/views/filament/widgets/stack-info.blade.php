<x-filament-widgets::widget>
    <x-filament::section heading="Стек" icon="heroicon-o-cpu-chip">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            {{-- PHP --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                @if($phpLogo)
                    <img src="{{ $phpLogo }}" alt="PHP" class="h-8 mb-2 object-contain">
                @else
                    <p class="text-xs text-gray-400 mb-1">PHP</p>
                @endif
                <p class="text-lg font-bold">{{ $phpVersion }}</p>
                <a href="{{ $phpDocsUrl }}" target="_blank"
                   class="text-xs text-primary-500 hover:underline mt-2 inline-block">
                    Документация →
                </a>
            </div>

            {{-- Laravel --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                @if($laravelLogo)
                    <img src="{{ $laravelLogo }}" alt="Laravel" class="h-8 mb-2 object-contain">
                @else
                    <p class="text-xs text-gray-400 mb-1">Laravel</p>
                @endif
                <p class="text-lg font-bold">{{ $laravelVersion }}</p>
                <div class="flex gap-3 mt-2">
                    <a href="{{ $laravelDocsUrl }}" target="_blank"
                       class="text-xs text-primary-500 hover:underline">Документация →</a>
                    <a href="{{ $laravelGithubUrl }}" target="_blank"
                       class="text-xs text-primary-500 hover:underline">GitHub →</a>
                </div>
            </div>

            {{-- Filament --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                @if($filamentLogo)
                    <img src="{{ $filamentLogo }}" alt="Filament" class="h-8 mb-2 object-contain">
                @else
                    <p class="text-xs text-gray-400 mb-1">Filament</p>
                @endif
                <p class="text-lg font-bold">{{ $filamentVersion }}</p>
                <div class="flex gap-3 mt-2">
                    <a href="{{ $filamentDocsUrl }}" target="_blank"
                       class="text-xs text-primary-500 hover:underline">Документация →</a>
                    <a href="{{ $filamentGithubUrl }}" target="_blank"
                       class="text-xs text-primary-500 hover:underline">GitHub →</a>
                </div>
            </div>

            {{-- nginx --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                @if($nginxLogo)
                    <img src="{{ $nginxLogo }}" alt="nginx" class="h-8 mb-2 object-contain">
                @else
                    <p class="text-xs text-gray-400 mb-1">nginx</p>
                @endif
                <p class="text-lg font-bold">{{ $nginxVersion }}</p>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>