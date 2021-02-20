<?php

declare(strict_types=1);

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class GetResourceRequest extends FormRequest
{
    public function rules(): array
    {
        $validFieldsRule = Rule::in($this->validFields());

        return [
            'identifier'      => 'required|string',
            'identifierField' => [
                'required',
                $validFieldsRule,
            ],
            'fields'   => 'required|array',
            'fields.*' => [$validFieldsRule],
        ];
    }

    public function messages(): array
    {
        return [
            'identifier.required'      => 'An identifier must be provided.',
            'identifierField.required' => 'An identifier field must be provided.',
            'fields.required'          => 'The fields to be retrieved must be provided.',
        ];
    }

    abstract protected function validFields(): array;
}
