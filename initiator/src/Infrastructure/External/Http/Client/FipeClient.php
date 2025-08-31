<?php

declare(strict_types=1);

namespace Src\Infrastructure\External\Http\Client;

use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Pool;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Log\Logger;
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
        foreach ($responses as $response) {
            $brands = array_merge($brands, $response->json());
        }
        return $brands;
    }
}
