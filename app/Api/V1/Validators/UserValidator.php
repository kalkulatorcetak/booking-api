<?php

namespace App\Api\V1\Validators;

use App\Validators\RequestValidator;

class UserValidator extends RequestValidator
{
    public function getRules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ];
    }
}