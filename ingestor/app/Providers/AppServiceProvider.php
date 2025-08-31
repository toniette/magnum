<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\Repository\BrandPersistence;
use Src\Domain\Repository\BrandRetriever;
use Src\Domain\Repository\DetailPersistence;
use Src\Domain\Repository\DetailRetriever;
use Src\Domain\Repository\ModelPersistence;
use Src\Domain\Repository\ModelRetriever;
use Src\Domain\Repository\Sync;
use Src\Domain\Repository\VersionPersistence;
use Src\Domain\Repository\VersionRetriever;
use Src\Infrastructure\Repository\EloquentBrandRepository;
use Src\Infrastructure\Repository\EloquentDetailRepository;
use Src\Infrastructure\Repository\EloquentModelRepository;
use Src\Infrastructure\Repository\EloquentSyncRepository;
use Src\Infrastructure\Repository\EloquentVersionRepository;
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

        $this->app->bind(BrandPersistence::class, EloquentBrandRepository::class);
        $this->app->bind(ModelPersistence::class, EloquentModelRepository::class);
        $this->app->bind(VersionPersistence::class, EloquentVersionRepository::class);
        $this->app->bind(DetailPersistence::class, EloquentDetailRepository::class);

        $this->app->bind(Sync::class, EloquentSyncRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
