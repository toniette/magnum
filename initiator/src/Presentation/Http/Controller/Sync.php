<?php

declare(strict_types=1);

namespace Src\Presentation\Http\Controller;

use Illuminate\Http\JsonResponse;
use Src\Application\UseCase\Sync as SyncUseCase;

class Sync
{
    public function __construct(
        private SyncUseCase $syncUseCase,
        private JsonResponse $response
    )
    {
    }

    public function __invoke(): JsonResponse
    {
        ($this->syncUseCase)();
        return $this->response;
    }
}
