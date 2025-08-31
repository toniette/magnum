<?php

declare(strict_types=1);

namespace Src\Domain\Repository;

use Src\Domain\Entity\Brand;

interface BrandPersistence
{
    public function save(Brand $brand): Brand;
}
