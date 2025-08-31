<?php

use Illuminate\Http\Request;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Config\Repository as ConfigRepository;
use Src\Presentation\Http\Middleware\GenerateTraceId;
use Illuminate\Http\JsonResponse;

test('should add the trace id to the context before returning the next closure', function () {
    // Arrange
    $request = new Request();
    $next = function () { return Mockery::mock(JsonResponse::class); };

    $configKey = 'trace_id';
    $config = Mockery::mock(ConfigRepository::class);
    $config->shouldReceive('string')
        ->with('app.trace_key')
        ->andReturn($configKey);

    $context = Mockery::mock(ContextRepository::class);
    $context->shouldReceive('remember')
        ->once()
        ->with($configKey, Mockery::type('string'));

    $sut = new GenerateTraceId($context, $config);

    // Act
    $response = $sut->handle($request, $next);

    // Assert
    $this->assertInstanceOf(JsonResponse::class, $response);
});
