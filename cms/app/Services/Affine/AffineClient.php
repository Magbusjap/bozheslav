<?php

namespace App\Services\Affine;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class AffineClient
{
    public function baseUrl(): string
    {
        $baseUrl = (string) config('affine.base_url');

        if ($baseUrl === '') {
            throw new RuntimeException('AFFINE_BASE_URL is not configured.');
        }

        return rtrim($baseUrl, '/');
    }

    public function serverConfig(): array
    {
        $response = Http::baseUrl($this->baseUrl())
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('affine.timeout', 15))
            ->post('/graphql', [
                'query' => <<<'GRAPHQL'
query AffineServerConfig {
  serverConfig {
    name
    version
    baseUrl
    type
    initialized
    availableUpgrade {
      version
      url
      publishedAt
    }
    credentialsRequirement {
      password {
        minLength
        maxLength
      }
    }
  }
}
GRAPHQL,
            ])
            ->throw()
            ->json();

        return data_get($response, 'data.serverConfig', []);
    }
}
