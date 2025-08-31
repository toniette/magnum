<?php

declare(strict_types=1);

namespace Src\Domain\Repository;

use Src\Domain\Entity\Brand;

interface ModelPersistence
{
    public function save(Brand $brand): Brand;
}
