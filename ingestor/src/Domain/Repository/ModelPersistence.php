<?php

declare(strict_types=1);

namespace Src\Domain\Repository;

use Src\Domain\Entity\Model;

interface ModelPersistence
{
    public function save(Model $model): Model;
}
