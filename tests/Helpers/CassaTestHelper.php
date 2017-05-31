<?php

namespace Test\Helpers;

trait CassaTestHelper
{
    public function cassaStructure(): array
    {
        return [
            'data' => [
                'type',
                'id',
                'attributes' => [
                    'name',
                    'currency',
                    'users' => [],
                    'added',
                    'modified',
                ]
            ]
        ];
    }

    public function cassaListStructure(): array
    {
        return [
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'currency',
                        'users' => [],
                        'added',
                        'modified'
                    ]
                ]
            ]
        ];
    }
}