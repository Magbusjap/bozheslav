<?php

return [
    'base_url' => rtrim((string) env('AFFINE_BASE_URL', ''), '/'),
    'timeout' => (int) env('AFFINE_TIMEOUT', 15),
    'cache_seconds' => (int) env('AFFINE_CACHE_SECONDS', 300),
    'env_file' => (string) env('AFFINE_ENV_FILE', base_path('../affine/.env')),
    'private_key_path' => (string) env('AFFINE_PRIVATE_KEY_PATH', base_path('../affine/config/private.key')),
    'db_host' => (string) env('AFFINE_DB_HOST', '127.0.0.1'),
    'db_port' => (int) env('AFFINE_DB_PORT', 55433),
    'reset_ttl' => (int) env('AFFINE_RESET_TTL', 1800),
    'reset_callback_path' => (string) env('AFFINE_RESET_CALLBACK_PATH', '/auth/changePassword'),
    'recovery_email' => (string) env('AFFINE_RECOVERY_EMAIL', ''),
];
