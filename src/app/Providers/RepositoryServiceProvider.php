<?php

namespace App\Providers;

use App\Repositories\Repository;
use App\Repositories\RepositoryInterface;
use App\Services\OS\Api\AbstractOsApi;
use App\Services\OS\Api\OsApiInterface;
use App\Services\OS\Type\AbstractOsType;
use App\Services\OS\Type\OsTypeInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(
            RepositoryInterface::class,
            Repository::class
        );



    }
}
