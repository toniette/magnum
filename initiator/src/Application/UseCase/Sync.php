<?php

declare(strict_types=1);

namespace Src\Application\UseCase;

use Src\Domain\Repository\BrandRetriever;
use Src\Domain\Repository\Sync as SyncRepository;

class Sync
{
    public function __construct(
        private BrandRetriever $brandRepository,
        private SyncRepository $syncRepository
    )
    {
    }

    public function __invoke(): void
    {
        $brands = $this->brandRepository->all();
        $this->syncRepository->sync($brands);
    }
}
