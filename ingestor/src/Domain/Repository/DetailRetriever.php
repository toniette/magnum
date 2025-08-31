<?php

declare(strict_types=1);

namespace Src\Domain\Repository;

use Src\Domain\Entity\Detail;
use Src\Domain\Entity\Version;

interface DetailRetriever
{
    public function getDetail(Version $version): Detail;
}
