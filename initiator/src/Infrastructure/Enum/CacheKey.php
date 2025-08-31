<?php

namespace Src\Infrastructure\Enum;

enum CacheKey: string
{
    case BRANDS = 'brands';
    case DETAILS = 'details:%s';

    public function with(string ...$params): string
    {
        return sprintf($this->value, ...$params);
    }
}
