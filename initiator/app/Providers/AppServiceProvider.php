<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Repository\BrandRetriever;
use Src\Domain\Repository\Sync;
use Src\Infrastructure\Repository\HttpBrandRetriever;
use Src\Infrastructure\Repository\RedisSync;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BrandRetriever::class, HttpBrandRetriever::class);
        $this->app->bind(Sync::class, RedisSync::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
