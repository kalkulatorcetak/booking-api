<?php

namespace App\Validators;

abstract class RequestValidator implements RequestValidatorInterface
{
    public function getMessages(): array
    {
        return [];
    }
}
