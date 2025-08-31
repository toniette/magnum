<?php

declare(strict_types=1);

namespace Src\Domain\Repository;

use Src\Domain\Collection\ModelCollection;
use Src\Domain\Entity\Brand;

interface ModelRetriever
{
    public function getModels(Brand $brand): ModelCollection;
}
