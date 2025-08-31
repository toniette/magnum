<?php

use Illuminate\Http\JsonResponse;
use Src\Presentation\Http\Controller\Sync;
use Src\Application\UseCase\Sync as SyncUseCase;

test('it should return a json response', function () {
    // Arrange
    $syncUseCase = Mockery::mock(SyncUseCase::class);
    $syncUseCase->shouldReceive('__invoke')->once()->andReturn(); // Partial Assertion
    $response = Mockery::mock(JsonResponse::class);

    //Act
    $response = new Sync($syncUseCase, $response)();

    // Assert
    expect($response)->toBeInstanceOf(JsonResponse::class);
});
