<?php

declare(strict_types=1);

namespace Src\Domain\Repository;

use Src\Domain\Entity\Detail;

interface DetailPersistence
{
    public function save(Detail $detail): Detail;
}
