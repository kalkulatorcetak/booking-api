<?php

namespace Test\Helpers;

trait CassaTestHelper
{
    protected function cassaStructure(): array
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

    protected function cassaListStructure(): array
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
