<?php

namespace App\Validators;

interface RequestValidatorInterface
{
    public function getRules(): array;

    public function getMessages(): array;
}