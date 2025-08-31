<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

class Version
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly Model $model,
    )
    {
    }

    public static function fromArray(array $item): self
    {
        return new self(
            $item['id'],
            $item['name'],
            Model::fromArray($item['model']),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'model' => $this->model->toArray(),
        ];
    }
}
