<?php

use Src\Presentation\Queue\Job\Sync;

test('can create an instance', function () {
    $job = new Sync([
        [
            'id' => 1,
            'name' => 'Fiat'
        ]
    ]);

    expect($job)->toBeInstanceOf(Sync::class);
});

test('withBrands sets brands and returns self', function () {
    $job = new Sync();
    $result = $job->withBrands([['id' => 1, 'name' => 'Fiat']]);

    expect((fn() => $this->brands)->call($job))->toBe([['id' => 1, 'name' => 'Fiat']]);
    expect($result)->toBe($job);
});
