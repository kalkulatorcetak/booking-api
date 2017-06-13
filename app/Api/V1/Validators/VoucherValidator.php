<?php

namespace App\Api\V1\Validators;

use App\Api\V1\Enums\VoucherType;
use App\Validators\RequestValidator;
use Illuminate\Validation\Rule;

class VoucherValidator extends RequestValidator
{
    public function getRules(): array
    {
        return [
            'cassa_id' => 'required|integer',
            'type' => ['required', Rule::in(VoucherType::listValues())],
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'cashier_id' => 'required|integer',
            'comment' => 'string|nullable',
        ];
    }
}
