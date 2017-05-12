<?php

namespace App\Validators;

use Illuminate\Database\Eloquent\Model;

abstract class RequestValidator implements RequestValidatorInterface
{
    protected $entity;

    public function __construct(Model $entity = null)
    {
        $this->entity = $entity;
    }

    public function getMessages(): array
    {
        return [];
    }
}
