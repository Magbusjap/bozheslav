<?php

namespace App\Services\Plane;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Throwable;

class PlaneDashboardService
{
    public function __construct(
        private readonly PlaneClient $client,
    ) {
    }

    public function getDashboardData(): array
    {
        $ttl = (int) config('plane.cache_seconds', 300);

        return Cache::remember('plane.dashboard.overview', $ttl, function (): array {
            return $this->buildDashboardData();
        });
    }

    private function buildDashboardData(): array
    {
        try {
            $projects = collect($this->client->listProjects())
                ->map(fn (array $project) => $this->normalizeProject($project))
                ->values();

            $issues = collect();
            $cycles = collect();
            $modules = collect();
            $views = collect();

            foreach ($projects as $project) {
                $projectId = (string) ($project['id'] ?? '');

                if ($projectId === '') {
                    continue;
                }

                $states = collect($this->client->listStates($projectId))
                    ->filter(fn (array $state) => ! empty($state['id']))
                    ->mapWithKeys(fn (array $state) => [
                        (string) $state['id'] => [
                            'group' => (string) ($state['group'] ?? 'unknown'),
                            'name' => (string) ($state['name'] ?? 'Unknown'),
                        ],
                    ]);

                $issues = $issues->concat(
                    collect($this->client->listIssues($projectId))
                        ->map(fn (array $issue) => $this->normalizeIssue($issue, $project, $states->all()))
                );

                $cycles = $cycles->concat(
                    collect($this->client->listCycles($projectId))
                        ->map(fn (array $cycle) => $this->normalizeCycle($cycle, $project))
                );

                $modules = $modules->concat(
                    collect($this->client->listModules($projectId))
                        ->map(fn (array $module) => $this->normalizeModule($module, $project))
                );

                $views = $views->concat(
                    collect($this->client->listViews($projectId))
                        ->map(fn (array $view) => $this->normalizeView($view, $project))
                );
            }

            $now = CarbonImmutable::now();
            $completedSince = $now->subDays(7);

            return [
                'is_configured' => true,
                'error' => null,
                'plane_url' => $this->client->baseUrl(),
                'workspace_slug' => $this->client->workspaceSlug(),
                'stats' => [
                    'projects' => $projects->count(),
                    'issues_total' => $issues->count(),
                    'issues_in_progress' => $issues->where('state_group', 'started')->count(),
                    'issues_open' => $issues->filter(
                        fn (array $issue) => in_array($issue['state_group'], ['backlog', 'unstarted', 'started'], true)
                    )->count(),
                    'issues_overdue' => $issues->filter(fn (array $issue) => $this->isOverdue($issue, $now))->count(),
                    'issues_completed_week' => $issues->filter(
                        fn (array $issue) => $issue['completed_at'] !== null && $issue['completed_at']->greaterThanOrEqualTo($completedSince)
                    )->count(),
                    'cycles' => $cycles->count(),
                    'modules' => $modules->count(),
                    'views' => $views->count(),
                ],
                'projects' => $projects->sortBy('name')->values()->all(),
                'issues' => $issues->sortByDesc('updated_at_ts')->take(15)->values()->all(),
                'overdue_issues' => $issues->filter(fn (array $issue) => $this->isOverdue($issue, $now))
                    ->sortBy('target_date_ts')
                    ->take(10)
                    ->values()
                    ->all(),
                'cycles' => $cycles->sortByDesc('updated_at_ts')->take(10)->values()->all(),
                'modules' => $modules->sortByDesc('updated_at_ts')->take(10)->values()->all(),
                'views' => $views->sortBy('name')->take(10)->values()->all(),
            ];
        } catch (Throwable $e) {
            return [
                'is_configured' => false,
                'error' => $e->getMessage(),
                'plane_url' => config('plane.base_url'),
                'workspace_slug' => config('plane.workspace_slug'),
                'stats' => [
                    'projects' => 0,
                    'issues_total' => 0,
                    'issues_in_progress' => 0,
                    'issues_open' => 0,
                    'issues_overdue' => 0,
                    'issues_completed_week' => 0,
                    'cycles' => 0,
                    'modules' => 0,
                    'views' => 0,
                ],
                'projects' => [],
                'issues' => [],
                'overdue_issues' => [],
                'cycles' => [],
                'modules' => [],
                'views' => [],
            ];
        }
    }

    private function normalizeProject(array $project): array
    {
        $projectId = (string) ($project['id'] ?? '');

        return [
            'id' => $projectId,
            'name' => (string) ($project['name'] ?? 'Untitled project'),
            'identifier' => (string) ($project['identifier'] ?? ''),
            'description' => (string) ($project['description'] ?? ''),
            'url' => $projectId !== '' ? $this->client->projectUrl($projectId) : null,
            'updated_at' => $this->formatDate($project['updated_at'] ?? null),
            'updated_at_ts' => $this->parseDate($project['updated_at'] ?? null),
        ];
    }

    private function normalizeIssue(array $issue, array $project, array $statesById = []): array
    {
        $projectId = (string) ($issue['project_id'] ?? $project['id'] ?? '');
        $issueId = (string) ($issue['id'] ?? '');
        $stateId = (string) ($issue['state'] ?? '');
        $stateGroup = (string) data_get(
            $issue,
            'state_detail.group',
            data_get($issue, 'state.group', data_get($statesById, "{$stateId}.group", data_get($issue, 'state_group', 'unknown')))
        );
        $stateName = (string) data_get(
            $issue,
            'state_detail.name',
            data_get($issue, 'state.name', data_get($statesById, "{$stateId}.name", $stateGroup))
        );

        return [
            'id' => $issueId,
            'project_id' => $projectId,
            'project_name' => (string) ($project['name'] ?? ''),
            'project_identifier' => (string) ($project['identifier'] ?? ''),
            'sequence_id' => (string) ($issue['sequence_id'] ?? ''),
            'identifier' => trim(((string) ($project['identifier'] ?? '')) . '-' . ((string) ($issue['sequence_id'] ?? '')), '-'),
            'name' => (string) ($issue['name'] ?? 'Untitled issue'),
            'priority' => (string) ($issue['priority'] ?? 'none'),
            'state_group' => $stateGroup,
            'state_name' => $stateName,
            'target_date' => $this->formatDate($issue['target_date'] ?? null),
            'target_date_ts' => $this->parseDate($issue['target_date'] ?? null),
            'completed_at' => $this->parseDate($issue['completed_at'] ?? null),
            'updated_at' => $this->formatDate($issue['updated_at'] ?? null),
            'updated_at_ts' => $this->parseDate($issue['updated_at'] ?? null),
            'url' => ($projectId !== '' && $issueId !== '') ? $this->client->issueUrl($projectId, $issueId) : null,
        ];
    }

    private function normalizeCycle(array $cycle, array $project): array
    {
        $projectId = (string) ($project['id'] ?? '');
        $cycleId = (string) ($cycle['id'] ?? '');

        return [
            'id' => $cycleId,
            'project_id' => $projectId,
            'project_name' => (string) ($project['name'] ?? ''),
            'name' => (string) ($cycle['name'] ?? 'Untitled cycle'),
            'start_date' => $this->formatDate($cycle['start_date'] ?? null),
            'target_date' => $this->formatDate($cycle['end_date'] ?? ($cycle['target_date'] ?? null)),
            'updated_at' => $this->formatDate($cycle['updated_at'] ?? null),
            'updated_at_ts' => $this->parseDate($cycle['updated_at'] ?? null),
            'url' => ($projectId !== '' && $cycleId !== '') ? $this->client->cycleUrl($projectId, $cycleId) : null,
        ];
    }

    private function normalizeModule(array $module, array $project): array
    {
        $projectId = (string) ($project['id'] ?? '');
        $moduleId = (string) ($module['id'] ?? '');

        return [
            'id' => $moduleId,
            'project_id' => $projectId,
            'project_name' => (string) ($project['name'] ?? ''),
            'name' => (string) ($module['name'] ?? 'Untitled module'),
            'target_date' => $this->formatDate($module['target_date'] ?? null),
            'updated_at' => $this->formatDate($module['updated_at'] ?? null),
            'updated_at_ts' => $this->parseDate($module['updated_at'] ?? null),
            'url' => ($projectId !== '' && $moduleId !== '') ? $this->client->moduleUrl($projectId, $moduleId) : null,
        ];
    }

    private function normalizeView(array $view, array $project): array
    {
        $projectId = (string) ($project['id'] ?? '');
        $viewId = (string) ($view['id'] ?? '');

        return [
            'id' => $viewId,
            'project_id' => $projectId,
            'project_name' => (string) ($project['name'] ?? ''),
            'name' => (string) ($view['name'] ?? 'Untitled view'),
            'updated_at' => $this->formatDate($view['updated_at'] ?? null),
            'updated_at_ts' => $this->parseDate($view['updated_at'] ?? null),
            'url' => ($projectId !== '' && $viewId !== '') ? $this->client->viewUrl($projectId, $viewId) : null,
        ];
    }

    private function isOverdue(array $issue, CarbonImmutable $now): bool
    {
        if ($issue['target_date_ts'] === null) {
            return false;
        }

        if (in_array($issue['state_group'], ['completed', 'cancelled'], true)) {
            return false;
        }

        return $issue['target_date_ts']->startOfDay()->lessThan($now->startOfDay());
    }

    private function parseDate(mixed $value): ?CarbonImmutable
    {
        if (! is_string($value) || $value === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (Throwable) {
            return null;
        }
    }

    private function formatDate(mixed $value): ?string
    {
        return $this->parseDate($value)?->format('Y-m-d H:i');
    }
}
