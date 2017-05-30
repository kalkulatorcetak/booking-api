<?php

namespace App\Api\V1\Validators;

use App\Api\V1\Enums\Currency;
use App\Validators\RequestValidator;
use Illuminate\Validation\Rule;

class CassaValidator extends RequestValidator
{
    public function getRules(): array
    {
        return [
            'name' => 'required|unique:cassas,name',
            'currency' => ['required', Rule::in(Currency::listValues())],
        ];
    }
}
