<?php

declare(strict_types=1);

namespace Src\Presentation\Queue\Job;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Src\Application\UseCase\Sync as SyncUseCase;
use Src\Domain\Collection\BrandCollection;

class Sync implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected SyncUseCase $useCase,
        protected array       $brands = []
    )
    {
    }

    public function withBrands(array $brands): self
    {
        $this->brands = $brands;

        return $this;
    }

    public function handle(): void
    {
        ($this->useCase)(BrandCollection::fromArray($this->brands));
    }
}
