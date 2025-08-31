<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

class Brand
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
    )
    {
    }

    public static function fromArray(array $item): self
    {
        return new self(
            $item['id'],
            $item['name'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
