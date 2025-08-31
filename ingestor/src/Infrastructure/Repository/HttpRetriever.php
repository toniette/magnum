<?php

declare(strict_types=1);

namespace Src\Infrastructure\Repository;

use Src\Domain\Collection\BrandCollection;
use Src\Domain\Collection\ModelCollection;
use Src\Domain\Collection\VersionCollection;
use Src\Domain\Entity\Brand;
use Src\Domain\Entity\Detail;
use Src\Domain\Entity\Model;
use Src\Domain\Entity\Version;
use Src\Domain\Repository\BrandRetriever;
use Src\Domain\Repository\DetailRetriever;
use Src\Domain\Repository\ModelRetriever;
use Src\Domain\Repository\VersionRetriever;
use Src\Infrastructure\External\Http\Client\FipeClient;

readonly class HttpRetriever implements
    BrandRetriever,
    ModelRetriever,
    VersionRetriever,
    DetailRetriever
{
    public function __construct(
        private FipeClient $fipeClient
    )
    {
    }

    public function getBrands(): BrandCollection
    {
        $brands = new BrandCollection();

        collect($this->fipeClient->getBrands())->each(function (array $item) use ($brands) {
            $brands->attach(
                Brand::fromArray([
                    'id' => (string) data_get($item, 'codigo'),
                    'name' => data_get($item, 'nome'),
                    'type' => data_get($item, 'tipo'),
                ])
            );
        });

        return $brands;
    }

    public function getDetail(Version $version): Detail
    {
        $detail = $this->fipeClient->getDetail($version);

        return Detail::fromArray([
            'version' => $version->toArray(),
            'type' => (string) data_get($detail, 'TipoVeiculo'),
            'value' => data_get($detail, 'Valor'),
            'brand' => data_get($detail, 'Marca'),
            'model' => data_get($detail, 'Modelo'),
            'fuel' => data_get($detail, 'Combustivel'),
            'code' => data_get($detail, 'CodigoFipe'),
            'reference' => data_get($detail, 'MesReferencia'),
            'fuel_type' => data_get($detail, 'SiglaCombustivel'),
        ]);
    }

    public function getModels(Brand $brand): ModelCollection
    {
        $models = new ModelCollection();

        collect($this->fipeClient->getModels($brand))->each(function (array $item) use ($models, $brand) {
            $models->attach(
                Model::fromArray([
                    'id' => (string) data_get($item, 'codigo'),
                    'name' => data_get($item, 'nome'),
                    'brand' => $brand->toArray(),
                ])
            );
        });

        return $models;
    }

    public function getVersions(Model $model): VersionCollection
    {
        $versions = new VersionCollection();

        collect($this->fipeClient->getVersions($model))->each(function (array $item) use ($versions, $model) {
            $versions->attach(
                Version::fromArray([
                    'id' => (string) data_get($item, 'codigo'),
                    'name' => data_get($item, 'nome'),
                    'model' => $model->toArray(),
                ])
            );
        });

        return $versions;
    }
}
