<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Request;

use App\Http\Request\GetProductRequest;

class GetProductRequestTest extends GetResourceRequestTest
{
    protected function route(): string
    {
        return 'api.products.get';
    }

    protected function validData(): array
    {
        return [
            'identifier'      => $this->faker->word,
            'identifierField' => $this->faker->randomElement(GetProductRequest::FIELDS),
            'fields'          => $this->faker->randomElements(
                GetProductRequest::FIELDS,
                $this->faker->numberBetween(1, count(GetProductRequest::FIELDS))
            ),
        ];
    }
}
