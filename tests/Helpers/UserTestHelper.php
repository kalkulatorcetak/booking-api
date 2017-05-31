<?php

namespace Test\Helpers;

use App\Api\V1\Models\User;

trait UserTestHelper
{
    protected function userStructure(): array
    {
        return [
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'email',
                    'roles' => [],
                    'added',
                    'modified',
                ]
            ]
        ];
    }

    protected function userListStructure(): array
    {
        return [
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'email',
                        'roles',
                        'added',
                        'modified'
                    ]
                ]
            ]
        ];
    }

    protected function getAdminUser(): User
    {
        return User::findOrFail(1);
    }
}
