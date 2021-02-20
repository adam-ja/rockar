<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Request\GetProductRequest;
use Faker\Factory;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function testReturns404ResponseIfProductDoesNotExist(): void
    {
        $faker = Factory::create();

        $this->getJson(route('api.products.get', [
            'identifierField' => $identifierField = $faker->randomElement(GetProductRequest::FIELDS),
            'identifier'      => $identifier = $faker->word,
            'fields'          => GetProductRequest::FIELDS,
        ]))
            ->assertNotFound()
            ->assertJson(['message' => "No product found with $identifierField $identifier."]);
    }

    public function testReturnsRequestedFieldsForMatchingProduct(): void
    {
        $this->getJson(route('api.products.get', [
            'identifierField' => 'vin',
            'identifier'      => 'WVGCV7AX7AW000784',
            'fields'          => [
                'make',
                'model',
                'colour',
            ],
        ]))
            ->assertOk()
            ->assertJson([
                'make'   => 'Ford',
                'model'  => 'Fiesta',
                'colour' => 'Red',
            ])
            ->assertJsonMissing([
                'vin'   => 'WVGCV7AX7AW000784',
                'price' => '10000',
            ]);
    }
}
