<?php

use Illuminate\Support\Facades\Route;

Route::get('/customers', [
    'uses' => 'CustomerController@get',
    'as'   => 'customers.get',
]);

Route::get('/products', [
    'uses' => 'ProductController@get',
    'as'   => 'products.get',
]);
