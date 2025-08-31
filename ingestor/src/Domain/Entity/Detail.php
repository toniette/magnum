<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

class Detail
{
    public function __construct(
        public readonly Version $version,
        public readonly string  $type,
        public readonly string  $value,
        public readonly string  $brand,
        public readonly string  $model,
        public readonly string  $fuel,
        public readonly string  $code,
        public readonly string  $reference,
        public readonly string  $fuelType,
    )
    {
    }

    public static function fromArray(array $item): self
    {
        return new self(
            Version::fromArray($item['version']),
            $item['type'],
            $item['value'],
            $item['brand'],
            $item['model'],
            $item['fuel'],
            $item['code'],
            $item['reference'],
            $item['fuel_type'],
        );
    }

    public function toArray(): array
    {
        return [
            'version' => $this->version->toArray(),
            'type' => $this->type,
            'value' => $this->value,
            'brand' => $this->brand,
            'model' => $this->model,
            'fuel' => $this->fuel,
            'code' => $this->code,
            'reference' => $this->reference,
            'fuel_type' => $this->fuelType,
        ];
    }
}
