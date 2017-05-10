<?php

namespace App\Validators;

use Illuminate\Http\Request;

interface RequestValidatorInterface
{
    public function authorize(Request $request): void;

    public function getRules(): array;

    public function getMessages(): array;
}