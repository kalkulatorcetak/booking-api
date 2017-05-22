<?php

namespace Test\Helpers;

trait UserTestHelper
{
    public function userStructure(): array
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

    public function userListStructure(): array
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
}
