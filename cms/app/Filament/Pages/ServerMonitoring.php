<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ServerMonitoring extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Отслеживание';
    protected static ?string $navigationGroup = 'Отслеживание';
    protected static ?int $navigationSort = 1;
    protected static ?string $title = 'Отслеживание';
    protected static string $view = 'filament.pages.server-monitoring';

    public string $period = '24h';

    public array $trustedIps = [];
    public string $newTrustedIp = '';

    public function mount(): void
    {
        $saved = \App\Models\Option::get('trusted_ips');
        $this->trustedIps = $saved ? json_decode($saved, true) : [];
    }

    public function addTrustedIp(?string $ip = null): void
    {
        $ip = trim($ip ?? $this->newTrustedIp);
        if ($ip && !in_array($ip, $this->trustedIps)) {
            $this->trustedIps[] = $ip;
            $this->saveTrustedIps();
        }
        $this->newTrustedIp = '';
    }

    public function removeTrustedIp(string $ip): void
    {
        $this->trustedIps = array_values(array_filter($this->trustedIps, fn($i) => $i !== $ip));
        $this->saveTrustedIps();
    }

    private function saveTrustedIps(): void
    {
        \App\Models\Option::updateOrCreate(
            ['key' => 'trusted_ips'],
            ['value' => json_encode($this->trustedIps), 'label' => 'Доверенные IP', 'group' => 'security']
        );
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    public function getViewData(): array
    {
        // RAM
        $memInfo = @file_get_contents('/proc/meminfo');
        $memTotal = $memUsed = $memPercent = 0;
        if ($memInfo) {
            preg_match('/MemTotal:\s+(\d+)/', $memInfo, $m);
            $memTotal = isset($m[1]) ? round($m[1] / 1024) : 0;
            preg_match('/MemAvailable:\s+(\d+)/', $memInfo, $m);
            $memAvailable = isset($m[1]) ? round($m[1] / 1024) : 0;
            $memUsed = $memTotal - $memAvailable;
            $memPercent = $memTotal > 0 ? round(($memUsed / $memTotal) * 100) : 0;
        }

        // Disk
        $diskTotal   = round(disk_total_space('/') / 1024 / 1024 / 1024, 1);
        $diskFree    = round(disk_free_space('/') / 1024 / 1024 / 1024, 1);
        $diskUsed    = round($diskTotal - $diskFree, 1);
        $diskPercent = $diskTotal > 0 ? round(($diskUsed / $diskTotal) * 100) : 0;

        // Uptime
        $uptime = trim(@shell_exec('uptime -p') ?? '—');

        // Period filter for failed attempts
        $periodMinutes = match($this->period) {
            '1h'  => 60,
            '24h' => 1440,
            '7d'  => 10080,
            '30d' => 43200,
            default => 1440,
        };
        $since = now()->subMinutes($periodMinutes);

        // Banned IPs from fail2ban
        $bannedIps = [];
        $fail2banLog = @shell_exec('sudo fail2ban-client status sshd 2>/dev/null');
        if ($fail2banLog && preg_match('/Banned IP list:\s*(.+)/i', $fail2banLog, $m)) {
            $bannedIps = array_filter(explode(' ', trim($m[1])));
        }

        // Security events from auth.log filtered by period
        $failedAttempts = [];
        $allLines = @file('/var/log/auth.log', FILE_IGNORE_NEW_LINES) ?: [];

        $patterns = [
            'failed'    => '/Failed password/i',
            'invalid'   => '/invalid format/i',
            'preauth'   => '/Connection closed.*preauth/i',
            'invalid_user' => '/Invalid user/i',
            'disconnect' => '/Disconnected.*invalid/i',
        ];

        foreach (array_reverse((array)$allLines) as $line) {
            // Parse ISO timestamp: 2026-03-27T17:55:08.699970+00:00
            preg_match('/^(\d{4}-\d{2}-\d{2}T[\d:]+)/', $line, $tMatch);
            if (!isset($tMatch[1])) continue;

            $logTime = @strtotime($tMatch[1]);
            if (!$logTime || $logTime < $since->timestamp) continue;

            // Determine event type
            $type = null;
            foreach ($patterns as $key => $pattern) {
                if (preg_match($pattern, $line)) {
                    $type = $key;
                    break;
                }
            }
            if (!$type) continue;

            // Extract IP
            preg_match('/(?:from|by)\s+([\d.]+)/i', $line, $ipMatch);
            $ip = $ipMatch[1] ?? '—';

            // Extract port
            preg_match('/port\s+(\d+)/i', $line, $portMatch);
            $port = $portMatch[1] ?? '—';

            // Format time for display
            $displayTime = date('d.m H:i:s', $logTime);

            $failedAttempts[] = [
                'time' => $displayTime,
                'ip'   => $ip,
                'port' => $port,
                'type' => $type,
            ];
        }

        // Installed software
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
            'memTotal'       => $memTotal,
            'memUsed'        => $memUsed,
            'memPercent'     => $memPercent,
            'diskTotal'      => $diskTotal,
            'diskUsed'       => $diskUsed,
            'diskPercent'    => $diskPercent,
            'uptime'         => $uptime,
            'bannedIps'      => array_values($bannedIps),
            'failedAttempts' => array_reverse($failedAttempts),
            'software'       => $software,
            'period'         => $this->period,
            'trustedIps'     => $this->trustedIps,
        ];
    }
}