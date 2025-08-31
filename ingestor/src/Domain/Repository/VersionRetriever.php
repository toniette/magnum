<?php

declare(strict_types=1);

namespace Src\Domain\Repository;

use Src\Domain\Collection\VersionCollection;
use Src\Domain\Entity\Model;

interface VersionRetriever
{
    public function getVersions(Model $model): VersionCollection;
}
