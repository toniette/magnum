<?php

declare(strict_types=1);

namespace Src\Infrastructure\External\Http\Client;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Pool;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Log\Logger;
use Src\Domain\Entity\Brand;
use Src\Domain\Entity\Model;
use Src\Domain\Entity\Version;
use Src\Infrastructure\External\Http\Enum\Fipe\VehicleType;

class FipeClient extends RestClient
{
    public function __construct(
        private PendingRequest $client,
        private ConfigRepository $config,
        private ContextRepository $context,
        private Logger $logger,
    )
    {
        $baseUrl = $this->config->string('fipe.api.base_url');
        $headers = $this->config->array('fipe.api.headers');
        parent::__construct($baseUrl, $headers, $client, $config, $context, $logger);
    }

    public function getBrands(): array
    {
        $types = VehicleType::cases();

        $responses = $this->client->pool(function (Pool $pool) use ($types) {
            foreach ($types as $type) {
                $uri = sprintf('%s/%s/marcas', $this->baseUrl, $type->value);
                $pool->as($type->value)->get($uri);
            }
        });

        $brands = [];
        foreach ($responses as $type => $response) {
            $mappedBrands = array_map(
                function (array $item) use ($type) {
                    $item['tipo'] = $type;
                    return $item;
                },
                $response->json()
            );
            $brands = array_merge($brands, $mappedBrands);
        }
        return $brands;
    }

    public function getModels(Brand $brand): array
    {
        $response = $this->client->get(
            sprintf('%s/marcas/%s/modelos', $brand->type->value, $brand->id),
        );

        return $response->json('modelos');
    }

    public function getVersions(Model $model): array
    {
        $response = $this->client->get(
            sprintf(
                '%s/marcas/%s/modelos/%s/anos',
                $model->brand->type->value,
                $model->brand->id,
                $model->id
            ),
        );

        return $response->json();
    }

    public function getDetail(Version $version): array
    {
        $response = $this->client->get(
            sprintf(
                '%s/marcas/%s/modelos/%s/anos/%s',
                $version->model->brand->type->value,
                $version->model->brand->id,
                $version->model->id,
                $version->id
            ),
        );

        return $response->json();
    }
}
