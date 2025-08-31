<?php

declare(strict_types=1);

namespace Src\Domain\Repository;

use Src\Domain\Collection\BrandCollection;

interface Sync
{
    public function sync(BrandCollection $brands): void;
}
