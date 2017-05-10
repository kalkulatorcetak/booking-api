<?php

namespace App\Api\V1\Validators;

class UserCreateValidator extends UserValidator
{
    public function getRules(): array
    {
        return array_merge(parent::getRules(), ['password' => 'required|string|min:8']);
    }
}