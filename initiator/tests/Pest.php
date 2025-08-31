<?php

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Log\Logger;
use Src\Infrastructure\External\Http\Client\FipeClient;
use Src\Infrastructure\External\Http\Client\RestClient;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Contracts\Events\Dispatcher;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/


function getRestClient(
    string $baseUrl,
    array $headers = [],
    PendingRequest $client = new PendingRequest(),
    ?ConfigRepository $config = null,
    ?ContextRepository $context = null,
    ?Logger $logger = null,
): RestClient
{
    if (! $context) {
        $context = new ContextRepository(Mockery::mock(Dispatcher::class));
    }

    if (! $logger) {
        $logger = Mockery::mock(Logger::class);
        $logger->shouldReceive('info')->andReturnSelf();
    }

    if (! $config) {
        $config = Mockery::mock(ConfigRepository::class);
        $config->shouldReceive('string')->with('app.trace_header')->andReturn('x-trace-id');
        $config->shouldReceive('string')->with('app.trace_key')->andReturn('trace_id');
    }

    return new class($baseUrl, $context, $logger, $headers, $client, $config) extends RestClient {
        public function __construct(
            string $baseUrl,
            ContextRepository $context,
            Logger $logger,
            array $headers = [],
            PendingRequest $client = new PendingRequest(),
            ConfigRepository $config = new ConfigRepository(),
        ) {
            parent::__construct($baseUrl, $headers, $client, $config, $context, $logger);
        }
    };
}

function getFipeClient(
    PendingRequest $client = new PendingRequest(),
    ?ConfigRepository $config = null,
    ?ContextRepository $context = null,
    ?Logger $logger = null,
): FipeClient
{
    if (! $context) {
        $context = new ContextRepository(Mockery::mock(Dispatcher::class));
    }

    if (! $logger) {
        $logger = Mockery::mock(Logger::class);
        $logger->shouldReceive('info')->andReturnSelf();
    }

    if (! $config) {
        $config = Mockery::mock(ConfigRepository::class);
        $config->shouldReceive('string')->with('app.trace_header')->andReturn('x-trace-id');
        $config->shouldReceive('string')->with('app.trace_key')->andReturn('trace_id');
        $config->shouldReceive('string')->with('fipe.api.base_url')->andReturn('https://test/fipe/api/v1');
        $config->shouldReceive('array')->with('fipe.api.headers')->andReturn([]);
    }

    return new FipeClient($client, $config, $context, $logger);
}

function getClientBaseMock(): PendingRequest
{
    $client = Mockery::mock(PendingRequest::class);

    $client->shouldReceive('baseUrl')->andReturnSelf();
    $client->shouldReceive('withHeaders')->andReturnSelf();
    $client->shouldReceive('status')->andReturn(200);
    $client->shouldReceive('body')->andReturn('');
    $client->shouldReceive('headers')->andReturn([]);

    return $client;
}

function getResponseMock(): Response
{
    $response = Mockery::mock(Response::class);

    $response->shouldReceive('status')->andReturn(200);
    $response->shouldReceive('body')->andReturn('');
    $response->shouldReceive('headers')->andReturn([]);

    return $response;
}
