<?php

declare(strict_types=1);

namespace Src\Domain\Repository;

use Src\Domain\Entity\Version;

interface VersionPersistence
{
    public function save(Version $version): Version;
}
