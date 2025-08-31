<?php

declare(strict_types=1);

namespace Src\Application\UseCase;

use Src\Domain\Collection\BrandCollection;
use Src\Domain\Repository\Sync as SyncRepository;

class Sync
{
    public function __construct(
        private SyncRepository $syncRepository
    )
    {
    }

    public function __invoke(BrandCollection $brands): void
    {
        $this->syncRepository->sync($brands);
    }
}
