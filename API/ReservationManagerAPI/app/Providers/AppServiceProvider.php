<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

//Interfaces
use App\Interfaces\IResourceRepository;
use App\Interfaces\IResourceService;
use App\Interfaces\IResourceTypeRepository;
use App\Interfaces\IReservationService;
use App\Interfaces\IReservationRepository;
use App\Interfaces\IStatusRepository;

//Repositorios
use App\Repositories\ResourceRepository;
use App\Repositories\ResourceTypeRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\StatusRepository;

//Servicios
use App\Services\ResourceService;
use App\Services\ReservationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Repositorios
        $this->app->bind(IResourceRepository::class, ResourceRepository::class);
        $this->app->bind(IResourceTypeRepository::class, ResourceTypeRepository::class);
        $this->app->bind(IReservationRepository::class, ReservationRepository::class);
        $this->app->bind(IStatusRepository::class, StatusRepository::class);
        
        //Servicios
        $this->app->bind(IResourceService::class, ResourceService::class);
        $this->app->bind(IReservationService::class, ReservationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
