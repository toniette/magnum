<?php

declare(strict_types=1);

namespace Src\Infrastructure\Repository;

use Src\Domain\Collection\BrandCollection;
use Src\Domain\Repository\Sync as SyncRepositoryInterface;
use Src\Presentation\Queue\Job\Sync;

class RedisSync implements SyncRepositoryInterface
{
    public function __construct(
        private readonly Sync $job
    )
    {
    }

    public function sync(BrandCollection $brands): void
    {
        $this->job->withBrands($brands->toArray());
        $this->job->dispatch();
    }
}
