<?php

namespace Src\Infrastructure\Repository;

use Src\Domain\Collection\BrandCollection;
use Src\Domain\Entity\Brand;
use Src\Domain\Repository\BrandPersistence;
use Src\Domain\Repository\Sync;

class HttpToEloquentSyncRepository implements Sync
{
    public function __construct(
        protected BrandPersistence $brandRepository,
    )
    {
    }

    public function sync(BrandCollection $brands): void
    {
        foreach ($brands as $brand) {
            $this->syncBrand($brand);
        }
    }

    protected function syncBrand(Brand $brand) {
        $this->brandRepository->save($brand);
    }
}
