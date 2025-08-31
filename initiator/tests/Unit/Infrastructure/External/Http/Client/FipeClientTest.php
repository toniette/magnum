<?php

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Http\Response;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Log\Logger;
use Src\Infrastructure\External\Http\Client\FipeClient;
use Src\Infrastructure\External\Http\Enum\Fipe\VehicleType;
use Symfony\Component\Uid\Ulid;
use Illuminate\Http\Client\Pool;

test('construct returns valid instance', function () {
    // Arrange
    $client = getClientBaseMock();

    $config = Mockery::mock(ConfigRepository::class);
    $config->shouldReceive('string')->with('fipe.api.base_url')->andReturn('https://parallelum.com.br/fipe/api/v1');
    $config->shouldReceive('array')->with('fipe.api.headers')->andReturn([]);
    $config->shouldReceive('string')->with('app.trace_header')->andReturn('x-trace-id');
    $config->shouldReceive('string')->with('app.trace_key')->andReturn('trace_id');

    $context = Mockery::mock(ContextRepository::class);
    $context->shouldReceive('get')->with('trace_id')->andReturn(Ulid::generate());

    $logger = Mockery::mock(Logger::class);

    // Act
    $sut = new FipeClient($client, $config, $context, $logger);

    // Assert
    expect($sut)->toBeInstanceOf(FipeClient::class);
});

test('getBrands returns valid array', function () {
    // Arrange
    $client = getClientBaseMock();

    $carsResponse = Mockery::mock(Response::class);
    $carsResponse->shouldReceive('json')->andReturn([
        ['codigo' => 1, 'nome' => 'Fiat', 'tipo' => VehicleType::CAR->value],
        ['codigo' => 2, 'nome' => 'Ford', 'tipo' => VehicleType::CAR->value]
    ]);

    $motorcyclesResponse = Mockery::mock(Response::class);
    $motorcyclesResponse->shouldReceive('json')->andReturn([
        ['codigo' => 1, 'nome' => 'Honda', 'tipo' => VehicleType::MOTORCYCLE->value],
        ['codigo' => 2, 'nome' => 'Yamaha', 'tipo' => VehicleType::MOTORCYCLE->value],
    ]);

    $trucksResponse = Mockery::mock(Response::class);
    $trucksResponse->shouldReceive('json')->andReturn([
        ['codigo' => 1, 'nome' => 'Volvo', 'tipo' => VehicleType::TRUCK->value],
        ['codigo' => 2, 'nome' => 'Mercedes', 'tipo' => VehicleType::TRUCK->value],
    ]);

    $poolMock = Mockery::mock(Pool::class);

    foreach (VehicleType::cases() as $type) {
        $poolMock->shouldReceive('as')
            ->once()
            ->with($type->value)
            ->andReturnSelf();
        $poolMock->shouldReceive('get')
            ->once()
            ->with($this->stringContains('/' . $type->value . '/marcas'));
    }

    $client->shouldReceive('pool')
        ->once()
        ->with(Mockery::on(function ($callback) use ($poolMock) {
            $callback($poolMock);
            return true;
        }))
        ->andReturn([
            'carros' => $carsResponse,
            'motos' => $motorcyclesResponse,
            'caminhoes' => $trucksResponse,
        ]);

    $sut = getFipeClient(client: $client);

    // Act
    $brands = $sut->getBrands();

    // Assert
    expect($brands)->toBeArray();
    expect($brands)->toHaveCount(6);
    expect($brands)->toContain(['codigo' => 1, 'nome' => 'Fiat', 'tipo' => VehicleType::CAR->value]);
    expect($brands)->toContain(['codigo' => 2, 'nome' => 'Ford', 'tipo' => VehicleType::CAR->value]);
    expect($brands)->toContain(['codigo' => 1, 'nome' => 'Honda', 'tipo' => VehicleType::MOTORCYCLE->value]);
    expect($brands)->toContain(['codigo' => 2, 'nome' => 'Yamaha', 'tipo' => VehicleType::MOTORCYCLE->value]);
    expect($brands)->toContain(['codigo' => 1, 'nome' => 'Volvo', 'tipo' => VehicleType::TRUCK->value]);
    expect($brands)->toContain(['codigo' => 2, 'nome' => 'Mercedes', 'tipo' => VehicleType::TRUCK->value]);
});
