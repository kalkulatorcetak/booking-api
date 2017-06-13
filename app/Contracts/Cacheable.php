<?php

namespace App\Contracts;

interface Cacheable
{
    public static function getCacheKey(int $id): string;
}
