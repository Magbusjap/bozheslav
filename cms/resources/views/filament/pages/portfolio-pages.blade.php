<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">Страницы проектов</x-slot>

        @if(empty($pages))
            <p class="text-gray-400">Нет созданных страниц.</p>
        @else
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-gray-700">
                    <th class="pb-2">Название</th>
                    <th class="pb-2">URL</th>
                    <th class="pb-2">Статус</th>
                    <th class="pb-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $page)
                <tr class="border-b border-gray-800">
                    <td class="py-2">{{ $page['title'] }}</td>
                    <td class="py-2">
                        <a href="/{{ $page['slug'] }}" target="_blank" class="text-primary-400 hover:underline">
                            /{{ $page['slug'] }}
                        </a>
                    </td>
                    <td class="py-2">{{ $page['status'] }}</td>
                    <td class="py-2 text-right flex gap-2 justify-end">
                        <a href="/admin/site-pages/{{ $page['id'] }}/edit" class="text-blue-400 hover:underline text-xs">
                            Редактировать
                        </a>
                        <button
                            wire:click="deletePage({{ $page['id'] }})"
                            wire:confirm="Удалить страницу {{ $page['title'] }}?"
                            class="text-red-400 hover:text-red-300 text-xs ml-2"
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