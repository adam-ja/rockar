<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Request\GetCustomerRequest;
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

    public function get(GetCustomerRequest $request): JsonResponse
    {
        $identifierField = $request->input('identifierField');
        $identifier      = $request->input('identifier');
        $customer        = $this->customers->byIdentifier($identifierField, $identifier, $request->input('fields'));

        if (! $customer) {
            return new JsonResponse(
                ['message' => "No customer found with $identifierField $identifier."],
                Response::HTTP_NOT_FOUND,
            );
        }

        return new JsonResponse($customer);
    }
}
