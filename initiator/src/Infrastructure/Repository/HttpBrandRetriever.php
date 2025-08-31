<?php

declare(strict_types=1);

namespace Src\Infrastructure\Repository;

use Src\Domain\Collection\BrandCollection;
use Src\Domain\Entity\Brand;
use Src\Domain\Repository\BrandRetriever;
use Src\Infrastructure\External\Http\Client\FipeClient;

readonly class HttpBrandRetriever implements BrandRetriever
{
    public function __construct(
        private FipeClient $fipeClient
    )
    {
    }

    public function all(): BrandCollection
    {
        $brands = new BrandCollection();

        collect($this->fipeClient->getBrands())->each(function (array $item) use ($brands) {
            $brands->attach(
                Brand::fromArray([
                    'id' => data_get($item, 'codigo'),
                    'name' => data_get($item, 'nome'),
                    'type' => data_get($item, 'tipo'),
                ])
            );
        });

        return $brands;
    }
}
