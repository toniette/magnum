<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Repository\BrandRetriever;
use Src\Domain\Repository\DetailRetriever;
use Src\Domain\Repository\ModelRetriever;
use Src\Domain\Repository\VersionRetriever;
use Src\Infrastructure\Repository\HttpRetriever;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BrandRetriever::class, HttpRetriever::class);
        $this->app->bind(ModelRetriever::class, HttpRetriever::class);
        $this->app->bind(VersionRetriever::class, HttpRetriever::class);
        $this->app->bind(DetailRetriever::class, HttpRetriever::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
