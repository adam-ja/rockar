<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Request;

use App\Http\Request\GetCustomerRequest;

class GetCustomerRequestTest extends GetResourceRequestTest
{
    protected function route(): string
    {
        return 'api.customers.get';
    }

    protected function validData(): array
    {
        return [
            'identifier'      => $this->faker->word,
            'identifierField' => $this->faker->randomElement(GetCustomerRequest::FIELDS),
            'fields'          => $this->faker->randomElements(
                GetCustomerRequest::FIELDS,
                $this->faker->numberBetween(1, count(GetCustomerRequest::FIELDS))
            ),
        ];
    }
}
