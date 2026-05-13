<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ServerStats extends Widget
{
    protected static string $view = 'filament.widgets.server-stats';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function getViewData(): array
    {
        // ОЗУ
        $memInfo = @file_get_contents('/proc/meminfo');
        $memTotal = $memFree = $memAvailable = 0;
        if ($memInfo) {
            preg_match('/MemTotal:\s+(\d+)/', $memInfo, $m);
            $memTotal = isset($m[1]) ? round($m[1] / 1024) : 0;
            preg_match('/MemAvailable:\s+(\d+)/', $memInfo, $m);
            $memAvailable = isset($m[1]) ? round($m[1] / 1024) : 0;
            $memUsed = $memTotal - $memAvailable;
            $memPercent = $memTotal > 0 ? round(($memUsed / $memTotal) * 100) : 0;
        }

        // Диск
        $diskTotal = round(disk_total_space('/') / 1024 / 1024 / 1024, 1);
        $diskFree  = round(disk_free_space('/') / 1024 / 1024 / 1024, 1);
        $diskUsed  = round($diskTotal - $diskFree, 1);
        $diskPercent = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100) : 0;

        // Попытки взлома из auth.log
        $bruteForce = 0;
        $authLog = @file_get_contents('/var/log/auth.log');
        if ($authLog) {
            $bruteForce = substr_count($authLog, 'Failed password');
        }

        // Установленное ПО
        $software = [];
        $checks = [
            'PHP'        => fn() => phpversion(),
            'nginx'      => fn() => trim(@shell_exec('nginx -v 2>&1 | grep -oP "[\d.]+"') ?? ''),
            'PostgreSQL' => fn() => trim(@shell_exec('psql --version 2>/dev/null | grep -oP "[\d.]+"') ?? ''),
            'Composer'   => fn() => trim(@shell_exec('composer --version 2>/dev/null | grep -oP "[\d.]+"') ?? ''),
            'Node.js'    => fn() => trim(@shell_exec('node -v 2>/dev/null') ?? ''),
            'Git'        => fn() => trim(@shell_exec('git --version 2>/dev/null | grep -oP "[\d.]+"') ?? ''),
        ];
        foreach ($checks as $name => $fn) {
            $ver = $fn();
            if ($ver) $software[] = ['name' => $name, 'version' => $ver];
        }

        return [
            'memTotal'    => $memTotal,
            'memUsed'     => $memUsed ?? 0,
            'memPercent'  => $memPercent ?? 0,
            'diskTotal'   => $diskTotal,
            'diskUsed'    => $diskUsed,
            'diskPercent' => $diskPercent,
            'bruteForce'  => $bruteForce,
            'software'    => $software,
        ];
    }
}