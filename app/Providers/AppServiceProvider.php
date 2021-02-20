<?php

namespace App\Providers;

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Repository\CsvRepository;
use App\Repository\DatabaseRepository;
use App\Repository\SimpleResourceFetcher;
use Exception;
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
                switch (Config::get('data.customers.source')) {
                    case 'csv':
                        return new CsvRepository(Config::get('data.customers.path_to_csv'));
                    case 'database':
                        return new DatabaseRepository('customers');
                    default:
                        throw new Exception('Invalid data source configured for customers.');
                }
            });

        $this->app
            ->when(ProductController::class)
            ->needs(SimpleResourceFetcher::class)
            ->give(function (): SimpleResourceFetcher {
                switch (Config::get('data.products.source')) {
                    case 'csv':
                        return new CsvRepository(Config::get('data.products.path_to_csv'));
                    case 'database':
                        return new DatabaseRepository('products');
                    default:
                        throw new Exception('Invalid data source configured for products.');
                }
            });
    }
}
