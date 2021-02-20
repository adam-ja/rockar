<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Request\GetProductRequest;
use App\Repository\SimpleResourceFetcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController
{
    public function __construct(private SimpleResourceFetcher $products)
    {
    }

    public function get(GetProductRequest $request): JsonResponse
    {
        $identifierField = $request->input('identifierField');
        $identifier      = $request->input('identifier');
        $product         = $this->products->byIdentifier($identifierField, $identifier, $request->input('fields'));

        if (! $product) {
            return new JsonResponse(
                ['message' => "No product found with $identifierField $identifier."],
                Response::HTTP_NOT_FOUND,
            );
        }

        return new JsonResponse($product);
    }
}
