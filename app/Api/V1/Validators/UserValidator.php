<?php

namespace App\Api\V1\Validators;

use App\Validators\RequestValidator;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserValidator extends RequestValidator
{
    public function authorize(Request $request): void
    {
        if (!app('Dingo\Api\Auth\Auth')->user()) {
            throw new UnauthorizedHttpException("This operation is allowed only for authenticated users");
        }
    }

    public function getRules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
        ];
    }
}