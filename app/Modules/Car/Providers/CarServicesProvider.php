<?php

namespace App\Modules\Car\Providers;

use App\Modules\Car\Repositories\CarRepository;
use App\Modules\Car\Repositories\Contracts\CarRepositoryInterface;
use App\Modules\Car\Repositories\Contracts\SellerRepositoryInterface;
use App\Modules\Car\Repositories\SellerRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CarServicesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('getApiLibrary', CarRepository::class);
        $this->app->bind(SellerRepositoryInterface::class, SellerRepository::class);
        $this->app->bind(CarRepositoryInterface::class, CarRepository::class);
    }

    public function boot(): void
    {
        // Rotas para HTML -> Front-end
        Route::prefix('cars')
            ->group(__DIR__ . '/../Interfaces/Routes/web.php');

        // Rotas para API
        Route::prefix('api')
            ->group(__DIR__ . '/../Interfaces/Routes/api.php');

        // Rotas para Sellers
        Route::prefix('sellers')
            ->middleware('auth')
            ->group(__DIR__ . '/../Interfaces/Routes/sellers.php');

        // Carregar as views
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'car');
    }
}