<?php

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Log\Logger;
use Psr\Log\LoggerInterface;
use Src\Infrastructure\External\Http\Client\FipeClient;
use Src\Infrastructure\Repository\HttpRetriever;

test('debug', function () {
    $client = new PendingRequest();

    $config = new ConfigRepository();
    $config->set('fipe.api', [
        'base_url' => 'https://parallelum.com.br/fipe/api/v1',
        'headers' => [
            'content-type' => 'application/json',
            'accept' => 'application/json'
        ]
    ]);
    $config->set('app', [
        'trace_header' => 'x-trace-id',
        'trace_key' => 'trace_id',
    ]);

    $dispatcher = Mockery::mock(Dispatcher::class);
    $context = new ContextRepository($dispatcher);

    $logger = new Logger(Mockery::mock(LoggerInterface::class));

    $fipeClient = new FipeClient($client, $config, $context, $logger);
    $sut = new HttpRetriever($fipeClient);

    $brands = $sut->getBrands();
    foreach ($brands as $brand) {
        $models = $sut->getModels($brand);
        foreach ($models as $model) {
            $versions = $sut->getVersions($model);
            foreach ($versions as $version) {
                $detail = $sut->getDetail($version);
                dd($detail);
            }
        }
    }
});
