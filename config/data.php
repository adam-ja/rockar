<?php

return [
    'customers' => [
        'source'      => env('CUSTOMER_DATA_SOURCE', 'csv'),
        'path_to_csv' => storage_path('data/customer.csv'),
    ],
];
