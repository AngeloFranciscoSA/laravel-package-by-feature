<?php

namespace App\Modules\Car\Providers;

use App\Modules\Car\Repositories\CarRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CarServicesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('getApiLibrary', CarRepository::class);
    }

    public function boot(): void
    {
        // Rotas para HTML -> Front-end
        Route::prefix('')
            ->group(__DIR__ . '/../Interfaces/Routes/web.php');

        // Rotas para API
        Route::prefix('api')
            ->group(__DIR__ . '/../Interfaces/Routes/api.php');
    }
}
