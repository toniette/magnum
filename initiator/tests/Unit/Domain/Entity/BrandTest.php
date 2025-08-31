<?php

use Src\Domain\Entity\Brand;
use Src\Infrastructure\External\Http\Enum\Fipe\VehicleType;

test('should create a brand using the constructor', function () {
    $sut = new Brand('1', 'slug', VehicleType::CAR);
    expect($sut)->toBeInstanceOf(Brand::class);
});

test('should create a brand using the fromArray method', function () {
    $sut = Brand::fromArray([
        'id' => '1',
        'name' => 'slug',
        'type' => VehicleType::CAR->value,
    ]);

    expect($sut)->toBeInstanceOf(Brand::class);
});

test('should return an array from the toArray method', function () {
    $sut = Brand::fromArray([
        'id' => '1',
        'name' => 'slug',
        'type' => VehicleType::CAR->value,
    ]);

    expect($sut->toArray())->toBe([
        'id' => '1',
        'name' => 'slug',
        'type' => VehicleType::CAR->value,
    ]);
});
