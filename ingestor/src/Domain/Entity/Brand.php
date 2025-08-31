<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

use Src\Infrastructure\External\Http\Enum\Fipe\VehicleType;

class Brand
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly VehicleType $type,
    )
    {
    }

    public static function fromArray(array $item): self
    {
        return new self(
            $item['id'],
            $item['name'],
            VehicleType::from($item['type']),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->value,
        ];
    }
}
