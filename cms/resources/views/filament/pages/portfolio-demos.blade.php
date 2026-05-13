<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Загруженные demo-проекты</x-slot>

        @if(empty($demos))
            <p class="text-gray-400">Нет загруженных проектов.</p>
        @else
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-700">
                    <th class="pb-2">Папка</th>
                    <th class="pb-2">URL</th>
                    <th class="pb-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($demos as $demo)
                <tr class="border-b border-gray-800">
                    <td class="py-2">{{ $demo }}</td>
                    <td class="py-2">
                        <a href="/portfolio/{{ $demo }}/" target="_blank" class="text-primary-400 hover:underline">
                            /portfolio/{{ $demo }}/
                        </a>
                    </td>
                    <td class="py-2 text-right">
                        <button
                            wire:click="deleteDemo('{{ $demo }}')"
                            wire:confirm="Удалить проект {{ $demo }}?"
                            class="text-red-400 hover:text-red-300 text-xs"
                        >
                            Удалить
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </x-filament::section>
</x-filament-panels::page>
