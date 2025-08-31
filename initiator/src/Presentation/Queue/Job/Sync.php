<?php

declare(strict_types=1);

namespace Src\Presentation\Queue\Job;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class Sync implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected array $brands = []
    )
    {
    }

    public function withBrands(array $brands): self
    {
        $this->brands = $brands;

        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function handle(): void
    {
    }
}
