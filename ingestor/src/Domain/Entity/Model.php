<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

class Model
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly Brand $brand
    )
    {
    }

    public static function fromArray(array $item): self
    {
        return new self(
            $item['id'],
            $item['name'],
            Brand::fromArray($item['brand']),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand->toArray(),
        ];
    }
}
