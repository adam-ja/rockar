<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Request\CustomerRequest;
use Illuminate\Http\JsonResponse;

class CustomerController
{
    public function get(CustomerRequest $request): JsonResponse
    {
        return new JsonResponse([]);
    }
}
