<?php

use Src\Application\UseCase\Sync;
use Src\Domain\Repository\Sync as SyncRepository;
use Src\Domain\Collection\BrandCollection;
use Src\Domain\Repository\BrandRetriever;

test('should retrieve brands using a brand retriever and sync them', function () {
    $collection = new BrandCollection();
    $brandRetriever = Mockery::mock(BrandRetriever::class);
    $brandRetriever->shouldReceive('all')->once()->andReturn($collection);

    $syncRepository = Mockery::mock(SyncRepository::class);
    $syncRepository->shouldReceive('sync')->once()->with($collection);

    $sut = new Sync($brandRetriever, $syncRepository);
    $sut();
});
