<?php

namespace Src\Infrastructure\Repository;

use Src\Domain\Collection\BrandCollection;
use Src\Domain\Entity\Brand;
use Src\Domain\Entity\Model;
use Src\Domain\Entity\Version;
use Src\Domain\Repository\BrandPersistence;
use Src\Domain\Repository\DetailPersistence;
use Src\Domain\Repository\DetailRetriever;
use Src\Domain\Repository\ModelPersistence;
use Src\Domain\Repository\ModelRetriever;
use Src\Domain\Repository\Sync;
use Src\Domain\Repository\VersionPersistence;
use Src\Domain\Repository\VersionRetriever;

class EloquentSyncRepository implements Sync
{
    public function __construct(
        protected BrandPersistence   $brandRepository,
        protected ModelPersistence   $modelRepository,
        protected DetailPersistence  $detailRepository,
        protected VersionPersistence $versionRepository,
        protected ModelRetriever     $modelRetriever,
        protected DetailRetriever    $detailRetriever,
        protected VersionRetriever   $versionRetriever
    )
    {
    }

    public function sync(BrandCollection $brands): void
    {
        foreach ($brands as $brand) {
            $this->syncBrand($brand);
            $this->syncModels($brand);
        }
    }

    protected function syncBrand(Brand $brand): void
    {
        $this->brandRepository->save($brand);
    }

    protected function syncModels(Brand $brand): void
    {
        $models = $this->modelRetriever->getModels($brand);
        foreach ($models as $model) {
            $this->modelRepository->save($model);
            $this->syncVersions($model);
        }
    }

    protected function syncVersions(Model $model): void
    {
        $versions = $this->versionRetriever->getVersions($model);
        foreach ($versions as $version) {
            $this->versionRepository->save($version);
            $this->syncDetails($version);
        }
    }

    protected function syncDetails(Version $version): void
    {
        $detail = $this->detailRetriever->getDetail($version);
        $this->detailRepository->save($detail);
    }
}
