<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\WbApiService;

class FetchWbData extends Command
{
    protected $signature = 'wb:fetch {type} {dateFrom=2026-01-01} {dateTo=2026-06-27}';
    protected $description = 'Fetch data from WB API and save to database';

    private WbApiService $api;

    public function __construct(WbApiService $api)
    {
        parent::__construct();
        $this->api = $api;
    }

    private function fetchAndSave(string $table, callable $fetcher, int $max = 100): void
    {
        $page = 1;
        $total = 0;

        while (true) {
            $data = $fetcher($page);

            if (empty($data)) break;

            foreach ($data as $row) {
                DB::table($table)->insert($row);
                $total++;

                if ($total >= $max) break 2;
            }

            $this->info("{$table}: page {$page} — {$total} records");
            $page++;
        }

        $this->info("{$table}: done — {$total} records total");
    }

    public function handle(): int
    {
        $type = $this->argument('type');
        $dateFrom = $this->argument('dateFrom');
        $dateTo = $this->argument('dateTo') ?? $dateFrom;

        $this->info("Fetching {$type} from {$dateFrom} to {$dateTo}");

        switch ($type) {
            case 'sales':
                $this->fetchAndSave('sales', fn($page) => $this->api->fetchSales($dateFrom, $dateTo, $page));
                break;
            case 'orders':
                $this->fetchAndSave('orders', fn($page) => $this->api->fetchOrders($dateFrom, $dateTo, $page));
                break;
            case 'stocks':
                $this->fetchAndSave('stocks', fn($page) => $this->api->fetchStocks($dateFrom, $page));
                break;
            case 'incomes':
                $this->fetchAndSave('incomes', fn($page) => $this->api->fetchIncomes($dateFrom, $dateTo, $page));
                break;
            default:
                $this->error("Unknown type: {$type}");
                return self::FAILURE;
        }

        $this->info('Done.');
        return self::SUCCESS;
    }
}
