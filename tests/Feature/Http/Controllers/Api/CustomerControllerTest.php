<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Request\CustomerRequest;
use Faker\Factory;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    public function testReturns404ResponseIfCustomerDoesNotExist(): void
    {
        $faker = Factory::create();

        $this->getJson(route('api.customers.get', [
            'identifierField' => $identifierField = $faker->randomElement(CustomerRequest::FIELDS),
            'identifier'      => $identifier = $faker->word,
            'fields'          => CustomerRequest::FIELDS,
        ]))
            ->assertNotFound()
            ->assertJson(['message' => "No customer found with $identifierField $identifier."]);
    }

    public function testReturnsRequestedFieldsForMatchingCustomer(): void
    {
        $this->getJson(route('api.customers.get', [
            'identifierField' => 'email',
            'identifier'      => 'dominic.sutton@rockar.com',
            'fields'          => [
                'forename',
                'surname',
            ],
        ]))
            ->assertOk()
            ->assertJson([
                'forename' => 'Dominic',
                'surname' => 'Sutton',
            ])
            ->assertJsonMissing([
                'email'          => 'dominic.sutton@rockar.com',
                'contact_number' => '+44 (0) 7950 244 036',
                'postcode'       => 'W12 7SL',
            ]);
    }
}
