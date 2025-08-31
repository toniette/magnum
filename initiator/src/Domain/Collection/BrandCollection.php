<?php

declare(strict_types=1);

namespace Src\Domain\Collection;

use Src\Domain\Entity\Brand;

class BrandCollection extends Collection
{
    protected ?string $type = Brand::class;

    public static function fromArray(array $data): self
    {
        return new self(...array_map(fn(array $item) => Brand::fromArray($item), $data));
    }

    public function toArray(): array
    {
        return array_map(fn(Brand $brand) => $brand->toArray(), iterator_to_array($this));
    }
}
