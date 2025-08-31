<?php

declare(strict_types=1);

namespace Src\Domain\Aggregate;

use Src\Domain\Entity\Brand;
use Src\Domain\Entity\Detail;
use Src\Domain\Entity\Model;
use Src\Domain\Entity\Version;

class Vehicle
{
    public function __construct(
        private Brand   $brand,
        private Model   $model,
        private Version $version,
        private Detail  $detail
    )
    {
    }


    public function toArray(): array
    {
        return [
            'brand' => $this->brand->toArray(),
            'model' => $this->model->toArray(),
            'version' => $this->version->toArray(),
            'detail' => $this->detail->toArray()
        ];
    }
}
