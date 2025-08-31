<?php

namespace Src\Infrastructure\Repository;

use Src\Domain\Entity\Model;
use Src\Domain\Repository\ModelPersistence;
use Src\Infrastructure\Persistence\Model\Model as ModelModel;

class EloquentModelRepository implements ModelPersistence
{
    public function __construct(
        protected ModelModel $model,
    )
    {
    }

    public function save(Model $model): Model
    {
        $this->model->firstOrCreate(
            ['id' => $model->id, 'brand_id' => $model->brand->id],
            ['name' => $model->name]
        );

        return $model;
    }
}
