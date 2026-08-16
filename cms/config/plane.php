<?php

return [
    'base_url' => rtrim((string) env('PLANE_BASE_URL', ''), '/'),
    'api_key' => (string) env('PLANE_API_KEY', ''),
    'workspace_slug' => (string) env('PLANE_WORKSPACE_SLUG', ''),
    'timeout' => (int) env('PLANE_TIMEOUT', 15),
    'cache_seconds' => (int) env('PLANE_CACHE_SECONDS', 300),
];
