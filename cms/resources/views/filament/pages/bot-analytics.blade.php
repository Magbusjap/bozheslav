<x-filament-panels::page>

    {{-- Общая статистика --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="fi-wi-stats-overview-stat rounded-xl bg-white p-4 shadow dark:bg-gray-900">
            <div class="text-sm text-gray-500">Всего найдено</div>
            <div class="text-3xl font-bold">{{ $this->stats['total'] }}</div>
        </div>
        <div class="fi-wi-stats-overview-stat rounded-xl bg-white p-4 shadow dark:bg-gray-900">
            <div class="text-sm text-gray-500">✅ Взято</div>
            <div class="text-3xl font-bold text-green-600">{{ $this->stats['taken'] }}</div>
        </div>
        <div class="fi-wi-stats-overview-stat rounded-xl bg-white p-4 shadow dark:bg-gray-900">
            <div class="text-sm text-gray-500">❌ Пропущено</div>
            <div class="text-3xl font-bold text-red-600">{{ $this->stats['skipped'] }}</div>
        </div>
        <div class="fi-wi-stats-overview-stat rounded-xl bg-white p-4 shadow dark:bg-gray-900">
            <div class="text-sm text-gray-500">⚡ Переопределено</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $this->stats['overridden'] }}</div>
        </div>
        <div class="fi-wi-stats-overview-stat rounded-xl bg-white p-4 shadow dark:bg-gray-900">
            <div class="text-sm text-gray-500">💰 Оплачено заказов</div>
            <div class="text-3xl font-bold text-blue-600">{{ $this->stats['paid'] }}</div>
        </div>
        <div class="fi-wi-stats-overview-stat rounded-xl bg-white p-4 shadow dark:bg-gray-900">
            <div class="text-sm text-gray-500">💵 Заработано</div>
            <div class="text-3xl font-bold text-green-700">{{ number_format($this->stats['total_earned'], 0, '.', ' ') }} ₽</div>
        </div>
    </div>

    {{-- По площадкам --}}
    <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-900 mb-6">
        <h2 class="text-lg font-semibold mb-3">По площадкам</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b dark:border-gray-700">
                    <th class="pb-2">Площадка</th>
                    <th class="pb-2">Найдено</th>
                    <th class="pb-2">Взято</th>
                    <th class="pb-2">Конверсия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->bySource as $row)
                <tr class="border-b dark:border-gray-700">
                    <td class="py-2 font-medium">{{ strtoupper($row->source) }}</td>
                    <td class="py-2">{{ $row->total }}</td>
                    <td class="py-2 text-green-600">{{ $row->taken }}</td>
                    <td class="py-2">{{ $row->total > 0 ? round($row->taken / $row->total * 100) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- По дням --}}
    <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-900 mb-6">
        <h2 class="text-lg font-semibold mb-3">По дням (последние 14 дней)</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b dark:border-gray-700">
                    <th class="pb-2">День</th>
                    <th class="pb-2">Найдено</th>
                    <th class="pb-2">Взято</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->byDay as $row)
                <tr class="border-b dark:border-gray-700">
                    <td class="py-2">{{ $row->day }}</td>
                    <td class="py-2">{{ $row->total }}</td>
                    <td class="py-2 text-green-600">{{ $row->taken }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Ангел --}}
    <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-900 mb-6">
        <h2 class="text-lg font-semibold mb-3">😇 Ангел — взятые задания</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b dark:border-gray-700">
                    <th class="pb-2">Название</th>
                    <th class="pb-2">Бюджет</th>
                    <th class="pb-2">Площадка</th>
                    <th class="pb-2">Переопределено</th>
                    <th class="pb-2">Оплачено</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->angelJobs as $job)
                <tr class="border-b dark:border-gray-700">
                    <td class="py-2">{{ \Illuminate\Support\Str::limit($job->title, 50) }}</td>
                    <td class="py-2">{{ $job->budget }}</td>
                    <td class="py-2">{{ strtoupper($job->source) }}</td>
                    <td class="py-2">{{ $job->human_override ? '⚡ Да' : '—' }}</td>
                    <td class="py-2">{{ $job->is_paid ? '✅ ' . number_format($job->price_paid, 0, '.', ' ') . ' ₽' : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Демон --}}
    <div class="rounded-xl bg-white p-4 shadow dark:bg-gray-900">
        <h2 class="text-lg font-semibold mb-3">😈 Демон — пропущенные вопреки боту</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b dark:border-gray-700">
                    <th class="pb-2">Название</th>
                    <th class="pb-2">Бюджет</th>
                    <th class="pb-2">Площадка</th>
                    <th class="pb-2">Дата</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->demonJobs as $job)
                <tr class="border-b dark:border-gray-700">
                    <td class="py-2">{{ \Illuminate\Support\Str::limit($job->title, 50) }}</td>
                    <td class="py-2">{{ $job->budget }}</td>
                    <td class="py-2">{{ strtoupper($job->source) }}</td>
                    <td class="py-2">{{ \Carbon\Carbon::parse($job->found_at)->format('d.m.Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</x-filament-panels::page>
