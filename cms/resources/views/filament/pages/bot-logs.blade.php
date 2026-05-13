<x-filament-panels::page>

    {{-- Активность --}}
    <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-900 mb-6">
        <h2 class="text-lg font-semibold mb-2">⏱ Активность</h2>
        <p class="text-sm text-gray-500">
            Последняя команда от вас:
            <span class="font-medium text-gray-800 dark:text-gray-200">
                {{ $this->lastCommandAt ? \Carbon\Carbon::parse($this->lastCommandAt)->diffForHumans() : 'нет данных' }}
            </span>
        </p>
    </div>

    {{-- Логи --}}
    <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-900">
        <h2 class="text-lg font-semibold mb-3">📋 Логи бота (последние 50)</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b dark:border-gray-700">
                    <th class="pb-2">Уровень</th>
                    <th class="pb-2">Событие</th>
                    <th class="pb-2">Сообщение</th>
                    <th class="pb-2">Время</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->logs as $log)
                <tr class="border-b dark:border-gray-700">
                    <td class="py-2">
                        @if($log->level === 'error')
                            <span class="text-red-600 font-medium">❌ error</span>
                        @elseif($log->level === 'warning')
                            <span class="text-yellow-600 font-medium">⚠️ warning</span>
                        @else
                            <span class="text-green-600">✅ info</span>
                        @endif
                    </td>
                    <td class="py-2 font-medium">{{ $log->event }}</td>
                    <td class="py-2 text-gray-500">{{ \Illuminate\Support\Str::limit($log->message, 80) }}</td>
                    <td class="py-2 text-gray-400">{{ \Carbon\Carbon::parse($log->created_at)->format('d.m.Y H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-filament-panels::page>