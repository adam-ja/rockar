<?php

namespace App\Providers;

use App\Http\Controllers\Api\CustomerController;
use App\Repository\CsvRepository;
use App\Repository\SimpleResourceFetcher;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app
            ->when(CustomerController::class)
            ->needs(SimpleResourceFetcher::class)
            ->give(function (): SimpleResourceFetcher {
                return new CsvRepository(Config::get('data.customers.path_to_csv'));
            });
    }
}
