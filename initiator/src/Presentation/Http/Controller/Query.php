<?php

declare(strict_types=1);

namespace Src\Presentation\Http\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Src\Infrastructure\Enum\CacheKey;

class Query
{
    public function brands(): JsonResponse
    {
        return response()->json(Cache::get('brands', []));
    }

    public function details(string $brandId): JsonResponse
    {
        return response()->json(Cache::get(CacheKey::DETAILS->with($brandId), []));
    }
}
