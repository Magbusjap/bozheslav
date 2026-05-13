<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6">
        <x-filament::section>
            <x-slot name="heading">Управление кэшем</x-slot>
            <p class="text-sm text-gray-500">
                Используйте кнопки вверху страницы для очистки кэша, конфига и скомпилированных views.
            </p>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">Последние логи</x-slot>
            <pre class="text-xs text-gray-400 bg-gray-950 p-4 rounded-lg overflow-auto max-h-96">{{ $logs }}</pre>
        </x-filament::section>
    </div>
</x-filament-panels::page>
