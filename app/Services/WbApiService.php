<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WbApiService
{
    private const BASE_URL = 'http://109.73.206.144:6969';
    private const API_KEY = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';

    private function fetch(string $endpoint, array $params): array
    {
        $params['key'] = self::API_KEY;

        $response = Http::get(self::BASE_URL . $endpoint, $params);

        if (!$response->ok()) return [];

        return $response->json('data') ?? [];
    }

    public function fetchSales(string $dateFrom, string $dateTo, int $page = 1, int $limit = 100): array
    {
        return $this->fetch(
            '/api/sales',
            [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => $page,
                'limit' => $limit
            ]
        );
    }

    public function fetchOrders(string $dateFrom, string $dateTo, int $page = 1, int $limit = 100): array
    {
        return $this->fetch(
            '/api/orders',
            [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => $page,
                'limit' => $limit
            ]
        );
    }

    public function fetchStocks(string $dateFrom, int $page = 1, int $limit = 100): array
    {
        return $this->fetch(
            '/api/stocks',
            [
                'dateFrom' => $dateFrom,
                'page' => $page,
                'limit' => $limit
            ]
        );
    }

    public function fetchIncomes(string $dateFrom, string $dateTo, int $page = 1, int $limit = 100): array
    {
        return $this->fetch(
            '/api/incomes',
            [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'page' => $page,
                'limit' => $limit
            ]
        );
    }
}
