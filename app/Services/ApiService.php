<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

class ApiService
{
    protected static function client(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withToken(config('services.api.token'))
            ->acceptJson()
            ->timeout(config('services.api.timeout', 10))
            ->retry(2, 200);
    }

    public static function datatables(array $params): array
    {
        try {
            $response = self::client()
                ->get(config('services.api.url') . '/datatables', $params);

            if ($response->failed()) {
                return self::error($response);
            }

            return $response->json();
        } catch (\Throwable $e) {
            return [
                'data' => [],
                'total' => 0,
                'error' => $e->getMessage(),
            ];
        }
    }

    protected static function error(Response $response): array
    {
        return [
            'data' => [],
            'total' => 0,
            'error' => $response->status(),
        ];
    }
}
