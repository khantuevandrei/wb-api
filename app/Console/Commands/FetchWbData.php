<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\WbApiService;

class FetchWbData extends Command
{
    protected $signature = 'wb:fetch {dateFrom=2026-01-01} {dateTo=2026-06-27}';
    protected $description = 'Fetch data from WB API and save to database';

    private WbApiService $api;

    public function __construct(WbApiService $api)
    {
        parent::__construct();
        $this->api = $api;
    }

    private function fetchAndSave(string $table, callable $fetcher): void
    {
        $page = 1;
        $total = 0;

        while (true) {
            $data = $fetcher($page);

            if (empty($data)) break;

            foreach ($data as $row) {
                DB::table($table)->insert($row);
                $total++;
            }

            $this->info("{$table}: page {$page} — {$total} records");
            $page++;
        }

        $this->info("{$table}: done — {$total} records total");
    }

    public function handle(): int
    {
        $dateFrom = $this->argument('dateFrom') ?? now()->subDay()->format('Y-m-d');

        $dateTo = $this->argument('dateTo') ?? now()->format('Y-m-d');

        $this->info("Fetching data from {$dateFrom} to {$dateTo}");

        $this->fetchAndSave('sales', fn($page) => $this->api->fetchSales($dateFrom, $dateTo, $page));
        $this->fetchAndSave('orders', fn($page) => $this->api->fetchOrders($dateFrom, $dateTo, $page));
        $this->fetchAndSave('stocks', fn($page) => $this->api->fetchStocks($dateFrom, $page));
        $this->fetchAndSave('incomes', fn($page) => $this->api->fetchIncomes($dateFrom, $dateTo, $page));

        $this->info('Done.');

        return self::SUCCESS;
    }
}
