<?php

declare(strict_types=1);

namespace App\Http\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Request\GetResourceRequest;

class GetProductRequest extends GetResourceRequest
{
    public const FIELDS = [
        'vin',
        'colour',
        'make',
        'model',
        'price',
    ];

    protected function validFields(): array
    {
        return self::FIELDS;
    }
}
