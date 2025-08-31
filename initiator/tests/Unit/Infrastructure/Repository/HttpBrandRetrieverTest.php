<?php

use Src\Domain\Collection\BrandCollection;
use Src\Infrastructure\External\Http\Client\FipeClient;
use Src\Infrastructure\Repository\HttpBrandRetriever;

test('all method returns a BrandCollection', function () {
    // Arrange
    $fipeClient = Mockery::mock(FipeClient::class);
    $inputBrands = [
        ['codigo' => '1', 'nome' => 'Brand 1'],
        ['codigo' => '2', 'nome' => 'Brand 2'],
    ];
    $fipeClient->shouldReceive('getBrands')
        ->andReturn($inputBrands);

    // Act
    $sut = new HttpBrandRetriever($fipeClient);
    $brands = $sut->all();

    // Assert
    expect($brands)->toHaveCount(2);
    expect($brands)->toBeInstanceOf(BrandCollection::class);
});
