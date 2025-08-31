<?php

namespace Src\Infrastructure\Repository;

use Src\Domain\Entity\Version;
use Src\Domain\Repository\VersionPersistence;
use Src\Infrastructure\Persistence\Model\Version as VersionModel;

class EloquentVersionRepository implements VersionPersistence
{
    public function __construct(
        protected VersionModel $model,
    )
    {
    }

    public function save(Version $version): Version
    {
        $this->model->firstOrCreate(
            ['id' => $version->id, 'model_id' => $version->model->id],
            ['name' => $version->name]
        );

        return $version;
    }
}
