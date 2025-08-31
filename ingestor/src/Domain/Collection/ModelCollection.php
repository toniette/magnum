<?php

declare(strict_types=1);

namespace Src\Domain\Collection;

use Src\Domain\Entity\Model;

class ModelCollection extends Collection
{
    protected ?string $type = Model::class;

    public static function fromArray(array $data): self
    {
        return new self(...array_map(fn(array $item) => Model::fromArray($item), $data));
    }

    public function toArray(): array
    {
        return array_map(fn(Model $item) => $item->toArray(), iterator_to_array($this));
    }
}
