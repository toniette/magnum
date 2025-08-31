<?php

namespace Src\Infrastructure\Repository;

use Src\Domain\Entity\Brand;
use Src\Infrastructure\Persistence\Model\Brand as BrandModel;
use Src\Domain\Repository\BrandPersistence;

class EloquentBrandRepository implements BrandPersistence
{
    public function __construct(
        protected BrandModel $model,
    )
    {
    }

    public function save(Brand $brand): Brand
    {
        $this->model->firstOrCreate(
            ['id' => $brand->id],
            ['name' => $brand->name, 'type' => $brand->type->value]
        );

        return $brand;
    }
}
