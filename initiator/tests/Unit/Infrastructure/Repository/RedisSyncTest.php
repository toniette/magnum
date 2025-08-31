<?php

use Illuminate\Support\Facades\Queue;
use Src\Domain\Collection\BrandCollection;
use Src\Infrastructure\Repository\RedisSync;
use Src\Presentation\Queue\Job\Sync as SyncJob;


test('sync dispatches a sync job to the queue', function () {
    // Arrange
    Queue::fake();
    $brands = Mockery::mock(BrandCollection::class);
    $brands->shouldReceive('toArray')->once()->andReturn([
        ['id' => 1, 'name' => 'Brand 1']
    ]); // Assert
    $job = Mockery::mock(SyncJob::class);
    $job->shouldReceive('withBrands')->once()->andReturn($job);
    $job->shouldReceive('dispatch')->once()->andReturn(); // Assert
    $sut = new RedisSync($job);

    // Act
    $sut->sync($brands);
});
