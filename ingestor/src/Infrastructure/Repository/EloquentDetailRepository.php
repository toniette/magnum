<?php

namespace Src\Infrastructure\Repository;

use Src\Domain\Entity\Detail;
use Src\Domain\Repository\DetailPersistence;
use Src\Infrastructure\Persistence\Model\Detail as DetailModel;

class EloquentDetailRepository implements DetailPersistence
{
    public function __construct(
        protected DetailModel $model,
    )
    {
    }

    public function save(Detail $detail): Detail
    {
        $this->model->firstOrCreate(
            ['id' => $detail->code, 'version_id' => $detail->version->id],
            [
                'type' => $detail->type,
                'value' => $detail->value,
                'brand' => $detail->brand,
                'model' => $detail->model,
                'fuel' => $detail->fuel,
                'reference' => $detail->reference,
                'fuel_type' => $detail->fuelType,
            ]
        );

        return $detail;
    }
}
