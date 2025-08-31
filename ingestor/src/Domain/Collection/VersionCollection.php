<?php

declare(strict_types=1);

namespace Src\Domain\Collection;

use Src\Domain\Entity\Version;

class VersionCollection extends Collection
{
    protected ?string $type = Version::class;

    public static function fromArray(array $data): self
    {
        return new self(...array_map(fn(array $item) => Version::fromArray($item), $data));
    }

    public function toArray(): array
    {
        return array_map(fn(Version $item) => $item->toArray(), iterator_to_array($this));
    }
}
