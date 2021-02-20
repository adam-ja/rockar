<?php

declare(strict_types=1);

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Request\GetResourceRequest;

class GetCustomerRequest extends GetResourceRequest
{
    public const FIELDS = [
        'email',
        'forename',
        'surname',
        'contact_number',
        'postcode',
    ];

    protected function validFields(): array
    {
        return self::FIELDS;
    }
}
