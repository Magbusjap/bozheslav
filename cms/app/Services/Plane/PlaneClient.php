<?php

namespace App\Services\Plane;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PlaneClient
{
    private const API_PREFIX = '/api/v1';

    public function workspaceSlug(): string
    {
        $slug = (string) config('plane.workspace_slug');

        if ($slug === '') {
            throw new RuntimeException('PLANE_WORKSPACE_SLUG is not configured.');
        }

        return $slug;
    }

    public function baseUrl(): string
    {
        $baseUrl = (string) config('plane.base_url');

        if ($baseUrl === '') {
            throw new RuntimeException('PLANE_BASE_URL is not configured.');
        }

        return rtrim($baseUrl, '/');
    }

    public function request(): PendingRequest
    {
        $apiKey = (string) config('plane.api_key');

        if ($apiKey === '') {
            throw new RuntimeException('PLANE_API_KEY is not configured.');
        }

        return Http::baseUrl($this->baseUrl())
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('plane.timeout', 15))
            ->withHeaders([
                'X-Api-Key' => $apiKey,
            ]);
    }

    public function get(string $uri, array $query = []): array
    {
        $response = $this->request()->get($uri, $query)->throw();
        $json = $response->json();

        return is_array($json) ? $json : [];
    }

    public function listProjects(): array
    {
        return $this->extractItems(
            $this->get(self::API_PREFIX . "/workspaces/{$this->workspaceSlug()}/projects/")
        );
    }

    public function listIssues(string $projectId): array
    {
        return $this->extractItems(
            $this->get(self::API_PREFIX . "/workspaces/{$this->workspaceSlug()}/projects/{$projectId}/issues/")
        );
    }

    public function listCycles(string $projectId): array
    {
        return $this->extractItems(
            $this->get(self::API_PREFIX . "/workspaces/{$this->workspaceSlug()}/projects/{$projectId}/cycles/")
        );
    }

    public function listModules(string $projectId): array
    {
        return $this->extractItems(
            $this->get(self::API_PREFIX . "/workspaces/{$this->workspaceSlug()}/projects/{$projectId}/modules/")
        );
    }

    public function listStates(string $projectId): array
    {
        return $this->extractItems(
            $this->get(self::API_PREFIX . "/workspaces/{$this->workspaceSlug()}/projects/{$projectId}/states/")
        );
    }

    public function listViews(string $projectId): array
    {
        try {
            return $this->extractItems(
                $this->get(self::API_PREFIX . "/workspaces/{$this->workspaceSlug()}/projects/{$projectId}/views/")
            );
        } catch (RequestException $e) {
            if (in_array($e->response?->status(), [401, 403, 404, 405], true)) {
                return [];
            }

            throw $e;
        }
    }

    public function projectUrl(string $projectId): string
    {
        return "{$this->baseUrl()}/{$this->workspaceSlug()}/projects/{$projectId}/issues/";
    }

    public function issueUrl(string $projectId, string $issueId): string
    {
        return "{$this->baseUrl()}/{$this->workspaceSlug()}/projects/{$projectId}/issues/{$issueId}/";
    }

    public function cycleUrl(string $projectId, string $cycleId): string
    {
        return "{$this->baseUrl()}/{$this->workspaceSlug()}/projects/{$projectId}/cycles/{$cycleId}/";
    }

    public function moduleUrl(string $projectId, string $moduleId): string
    {
        return "{$this->baseUrl()}/{$this->workspaceSlug()}/projects/{$projectId}/modules/{$moduleId}/";
    }

    public function viewUrl(string $projectId, string $viewId): string
    {
        return "{$this->baseUrl()}/{$this->workspaceSlug()}/projects/{$projectId}/views/{$viewId}/";
    }

    private function extractItems(array $payload): array
    {
        if (array_is_list($payload)) {
            return $payload;
        }

        foreach (['results', 'data', 'items'] as $key) {
            $items = Arr::get($payload, $key);

            if (is_array($items)) {
                return $items;
            }
        }

        return [];
    }
}
