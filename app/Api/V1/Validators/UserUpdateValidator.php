<?php

namespace App\Api\V1\Validators;

use App\Validators\RequestValidator;
use Illuminate\Validation\Rule;

class UserUpdateValidator extends RequestValidator
{
    public function getRules(): array
    {
        return [
            'name' => 'string',
            'email' => $this->getEmailRules(),
            'roles' => 'array|nullable',
        ];
    }

    protected function getEmailRules(): array
    {
        $unique = Rule::unique('users', 'email');
        if ($this->entity !== null) {
            $unique->ignore($this->entity->id);
        }

        return ['email', $unique];
    }
}
