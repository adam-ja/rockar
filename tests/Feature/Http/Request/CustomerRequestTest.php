<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Request;

use App\Http\Request\CustomerRequest;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CustomerRequestTest extends TestCase
{
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function testValidDataPassesValidation(): void
    {
        $this->getJson(route('api.customers.get', $this->validData()))
            ->assertJsonMissingValidationErrors();
    }

    /**
     * @dataProvider provideInvalidData
     *
     * @param string $key
     * @param mixed $value
     * @param string $message
     */
    public function testInvalidDataFailsValidationWithExpectedMessage(string $key, $value, string $message): void
    {
        $data = $this->validData();

        is_null($value) ? Arr::forget($data, $key) : Arr::set($data, $key, $value);

        $this->getJson(route('api.customers.get', $data))
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([$key => $message]);
    }

    public function provideInvalidData(): array
    {
        return [
            'missing identifier' => [
                'identifier',
                null,
                'An identifier must be provided.',
            ],
            'non-string identifier' => [
                'identifier',
                ['not a' => 'string'],
                'The identifier must be a string.',
            ],
            'missing identifierField' => [
                'identifierField',
                null,
                'An identifier field must be provided.',
            ],
            'unrecognised identifierField' => [
                'identifierField',
                'abcdef',
                'The selected identifier field is invalid.',
            ],
            'missing fields' => [
                'fields',
                null,
                'The fields to be retrieved must be provided.',
            ],
            'non-array fields' => [
                'fields',
                'not an array',
                'The fields must be an array.',
            ],
            'unrecognised field in fields' => [
                'fields.0',
                'abcdef',
                'The selected fields.0 is invalid.',
            ],
        ];
    }

    private function validData(): array
    {
        return [
            'identifier'      => $this->faker->word,
            'identifierField' => $this->faker->randomElement(CustomerRequest::FIELDS),
            'fields'          => $this->faker->randomElements(
                CustomerRequest::FIELDS,
                $this->faker->numberBetween(1, count(CustomerRequest::FIELDS))
            ),
        ];
    }
}
