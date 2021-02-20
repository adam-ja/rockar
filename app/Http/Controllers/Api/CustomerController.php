<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Request\CustomerRequest;
use App\Repository\SimpleResourceFetcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerController
{
    public function __construct(private SimpleResourceFetcher $customers)
    {
    }

    public function get(CustomerRequest $request): JsonResponse
    {
        $identifierField = $request->input('identifierField');
        $identifier      = $request->input('identifier');
        $customer        = $this->customers->byIdentifier($identifierField, $identifier);

        if (! $customer) {
            return new JsonResponse(
                ['message' => "No customer found with $identifierField $identifier."],
                Response::HTTP_NOT_FOUND,
            );
        }

        return new JsonResponse(array_intersect_key($customer, array_flip($request->input('fields'))));
    }
}
