<?php

namespace App\Services\Affine;

use Illuminate\Support\Facades\Cache;
use Throwable;

class AffineDashboardService
{
    public function __construct(
        private readonly AffineClient $client,
    ) {
    }

    public function getDashboardData(): array
    {
        $ttl = (int) config('affine.cache_seconds', 300);

        return Cache::remember('affine.dashboard.overview', $ttl, function (): array {
            return $this->buildDashboardData();
        });
    }

    private function buildDashboardData(): array
    {
        try {
            $serverConfig = $this->client->serverConfig();
            $passwordLimits = (array) data_get($serverConfig, 'credentialsRequirement.password', []);
            $availableUpgrade = data_get($serverConfig, 'availableUpgrade');

            return [
                'is_configured' => true,
                'is_online' => true,
                'error' => null,
                'affine_url' => $this->client->baseUrl(),
                'server' => [
                    'name' => (string) data_get($serverConfig, 'name', 'AFFiNE'),
                    'version' => (string) data_get($serverConfig, 'version', 'unknown'),
                    'base_url' => (string) data_get($serverConfig, 'baseUrl', $this->client->baseUrl()),
                    'type' => (string) data_get($serverConfig, 'type', 'unknown'),
                    'initialized' => (bool) data_get($serverConfig, 'initialized', false),
                    'password_min' => (int) ($passwordLimits['minLength'] ?? 0),
                    'password_max' => (int) ($passwordLimits['maxLength'] ?? 0),
                ],
                'available_upgrade' => is_array($availableUpgrade) ? [
                    'version' => (string) data_get($availableUpgrade, 'version', ''),
                    'url' => (string) data_get($availableUpgrade, 'url', ''),
                    'published_at' => (string) data_get($availableUpgrade, 'publishedAt', ''),
                ] : null,
            ];
        } catch (Throwable $e) {
            return [
                'is_configured' => true,
                'is_online' => false,
                'error' => $e->getMessage(),
                'affine_url' => config('affine.base_url'),
                'server' => [
                    'name' => 'AFFiNE',
                    'version' => 'unknown',
                    'base_url' => config('affine.base_url'),
                    'type' => 'unknown',
                    'initialized' => false,
                    'password_min' => 0,
                    'password_max' => 0,
                ],
                'available_upgrade' => null,
            ];
        }
    }
}
