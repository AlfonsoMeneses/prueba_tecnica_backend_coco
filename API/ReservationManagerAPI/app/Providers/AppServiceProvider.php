<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//Interfaces
use App\Interfaces\IResourceRepository;
use App\Interfaces\IResourceService;

//Repositorios
use App\Repositories\ResourceRepository;

//Servicios
use App\Services\ResourceService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Repositorios
        $this->app->bind(IResourceRepository::class, ResourceRepository::class);

        //Servicios
        $this->app->bind(IResourceService::class, ResourceService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
