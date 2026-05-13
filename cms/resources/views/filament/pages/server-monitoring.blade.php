<x-filament-panels::page>

    {{-- Server resources --}}
    <x-filament::section heading="Ресурсы сервера" icon="heroicon-o-server">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">ОЗУ</p>
                <p class="text-2xl font-bold">{{ $memUsed }} / {{ $memTotal }} МБ</p>
                <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $memPercent > 80 ? 'bg-red-500' : 'bg-primary-500' }}"
                         style="width: {{ $memPercent }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $memPercent }}% использовано</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Диск</p>
                <p class="text-2xl font-bold">{{ $diskUsed }} / {{ $diskTotal }} ГБ</p>
                <div class="mt-2 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="h-2 rounded-full {{ $diskPercent > 80 ? 'bg-red-500' : 'bg-primary-500' }}"
                         style="width: {{ $diskPercent }}%"></div>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $diskPercent }}% использовано</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Uptime</p>
                <p class="text-2xl font-bold">{{ $uptime }}</p>
            </div>
        </div>

        @if(count($software) > 0)
        <div class="mt-6">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Установленное ПО</p>
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

    {{-- Trusted IPs --}}
    <x-filament::section heading="Доверенные IP" icon="heroicon-o-check-badge" class="mt-6">
        <div class="flex gap-2 mb-4">
            <input
                wire:model="newTrustedIp"
                wire:keydown.enter="addTrustedIp"
                type="text"
                placeholder="Добавить IP..."
                class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
            <x-filament::button wire:click="addTrustedIp">
                Добавить
            </x-filament::button>
        </div>

        @if(count($trustedIps) > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($trustedIps as $ip)
                @php
                    $hasAttempts = collect($failedAttempts)->where('ip', $ip)->count();
                @endphp
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium
                    {{ $hasAttempts ? 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300' : 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' }}">
                    {{ $ip }}
                    @if($hasAttempts)
                        <span class="font-bold">⚠ {{ $hasAttempts }} попыток</span>
                    @endif
                    <button wire:click="removeTrustedIp('{{ $ip }}')" class="ml-1 hover:opacity-70">✕</button>
                </span>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-400">Нет доверенных IP. Добавьте свой IP для мониторинга.</p>
        @endif
    </x-filament::section>

    {{-- Banned IPs --}}
    <x-filament::section icon="heroicon-o-no-symbol" class="mt-6">
        <x-slot name="heading">
            <span>Заблокированные IP (fail2ban)</span>
            <span class="ml-2 text-xs font-normal text-gray-400">{{ count($bannedIps) }} записей</span>
        </x-slot>

        @if(count($bannedIps) > 0)
            @php $perPage = 10; $pages = ceil(count($bannedIps) / $perPage); @endphp
            <div x-data="{ page: 1, perPage: 10 }">
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($bannedIps as $i => $ip)
                    @php
                        $isTrusted = in_array($ip, $trustedIps);
                    @endphp
                    <span
                        x-show="({{ $i }} >= (page-1)*perPage) && ({{ $i }} < page*perPage)"
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            {{ $isTrusted ? 'bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300' : 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300' }}">
                        {{ $ip }}
                        @if($isTrusted) ⚠ доверенный @endif
                    </span>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center gap-2 text-xs text-gray-400">
                        <span>Показывать:</span>
                        @foreach([10, 25, 50] as $n)
                        <button x-on:click="perPage = {{ $n }}; page = 1"
                            :class="perPage === {{ $n }} ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600'"
                            class="px-2 py-0.5 rounded text-xs">{{ $n }}</button>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-2">
                        <button x-on:click="if(page > 1) page--"
                            :disabled="page === 1"
                            class="px-3 py-1 rounded-lg text-xs bg-gray-100 dark:bg-gray-800 disabled:opacity-40">← Назад</button>
                        <span class="text-xs text-gray-400" x-text="`${page} / ${Math.ceil({{ count($bannedIps) }} / perPage)}`"></span>
                        <button x-on:click="if(page < Math.ceil({{ count($bannedIps) }} / perPage)) page++"
                            :disabled="page >= Math.ceil({{ count($bannedIps) }} / perPage)"
                            class="px-3 py-1 rounded-lg text-xs bg-gray-100 dark:bg-gray-800 disabled:opacity-40">Вперёд →</button>
                    </div>
                </div>
            </div>
        @else
            <p class="text-sm text-gray-400">Заблокированных IP нет.</p>
        @endif
    </x-filament::section>

    {{-- Failed login attempts with period filter and pagination --}}
    <x-filament::section icon="heroicon-o-exclamation-triangle" class="mt-6">
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full flex-wrap gap-2">
                <span>Неудачные попытки входа
                    <span class="text-xs font-normal text-gray-400 ml-1">{{ count($failedAttempts) }} записей</span>
                </span>
                <div class="flex gap-1">
                    @foreach(['1h' => '1 ч', '24h' => '24 ч', '7d' => '7 дн', '30d' => '30 дн'] as $value => $label)
                    <button
                        wire:click="setPeriod('{{ $value }}')"
                        class="px-3 py-1 rounded-lg text-xs font-medium transition-colors
                            {{ $period === $value
                                ? 'bg-primary-500 text-white'
                                : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>
        </x-slot>

        @if(count($failedAttempts) > 0)
        <div x-data="{ page: 1, perPage: 25 }">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-400 border-b border-gray-200 dark:border-gray-700">
                            <th class="pb-2 pr-4">Время</th>
                            <th class="pb-2 pr-4">IP адрес</th>
                            <th class="pb-2 pr-4">Порт</th>
                            <th class="pb-2">Тип события</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($failedAttempts as $i => $attempt)
                        @php
                            $isTrustedAttempt = in_array($attempt['ip'], $trustedIps);
                        @endphp
                        <tr
                            x-show="({{ $i }} >= (page-1)*perPage) && ({{ $i }} < page*perPage)"
                            class="border-b border-gray-100 dark:border-gray-800 {{ $isTrustedAttempt ? 'bg-orange-50 dark:bg-orange-900/20' : '' }}">
                            <td class="py-2 pr-4 text-gray-500 whitespace-nowrap">{{ $attempt['time'] }}</td>
                            <td class="py-2 pr-4 font-mono font-medium {{ $isTrustedAttempt ? 'text-orange-500' : 'text-red-500' }}">
                                {{ $attempt['ip'] }}
                                @if($isTrustedAttempt)
                                    <span class="text-xs">⚠ доверенный</span>
                                @endif
                            </td>
                            <td class="py-2 pr-4 text-gray-400">{{ $attempt['port'] }}</td>
                            <td class="py-2">
                                @php
                                    $typeLabels = [
                                        'failed'       => ['Неверный пароль', 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300'],
                                        'invalid'      => ['Неверный формат', 'bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300'],
                                        'preauth'      => ['Обрыв до авторизации', 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300'],
                                        'invalid_user' => ['Несуществующий юзер', 'bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300'],
                                        'disconnect'   => ['Отключение', 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400'],
                                    ];
                                    [$label, $class] = $typeLabels[$attempt['type']] ?? ['Неизвестно', 'bg-gray-100 text-gray-500'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $class }}">
                                    {{ $label }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="flex items-center justify-between mt-4 flex-wrap gap-2">
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <span>Показывать:</span>
                    @foreach([10, 25, 50, 200] as $n)
                    <button x-on:click="perPage = {{ $n }}; page = 1"
                        :class="perPage === {{ $n }} ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600'"
                        class="px-2 py-0.5 rounded text-xs">{{ $n }}</button>
                    @endforeach
                </div>
                <div class="flex items-center gap-2">
                    <button x-on:click="if(page > 1) page--"
                        :disabled="page === 1"
                        class="px-3 py-1 rounded-lg text-xs bg-gray-100 dark:bg-gray-800 disabled:opacity-40">← Назад</button>
                    <span class="text-xs text-gray-400" x-text="`${page} / ${Math.ceil({{ count($failedAttempts) }} / perPage)}`"></span>
                    <button x-on:click="if(page < Math.ceil({{ count($failedAttempts) }} / perPage)) page++"
                        :disabled="page >= Math.ceil({{ count($failedAttempts) }} / perPage)"
                        class="px-3 py-1 rounded-lg text-xs bg-gray-100 dark:bg-gray-800 disabled:opacity-40">Вперёд →</button>
                </div>
            </div>
        </div>
        @else
            <p class="text-sm text-gray-400">Попыток взлома не обнаружено за выбранный период.</p>
        @endif
    </x-filament::section>

</x-filament-panels::page>