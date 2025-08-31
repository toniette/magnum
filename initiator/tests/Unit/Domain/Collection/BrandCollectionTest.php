<?php

use Src\Domain\Collection\BrandCollection;
use Src\Domain\Entity\Brand;

test('fromArray should return an new instance of the collection', function () {
    $sut = BrandCollection::fromArray([]);

    expect($sut)->toBeInstanceOf(BrandCollection::class);
});

test('toArray should return an array of arrays', function () {
    // Arrange
    $brand = ['id' => '1', 'name' => 'slug'];
    $sut = BrandCollection::fromArray([$brand]);

    // Act
    $brands = $sut->toArray();

    // Assert
    expect($brands)->toBe([$brand]);
});
