<?php

namespace App\Api\V1\Validators;

class UserCreateValidator extends UserUpdateValidator
{
    public function getRules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users:email',
            'password' => 'required|string|min:8',
            'roles' => 'required|array',
        ];
    }
}
