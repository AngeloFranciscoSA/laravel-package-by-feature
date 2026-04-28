<?php

namespace App\Modules\Auth\Providers;

use App\Modules\Auth\Repositories\AuthRepository;
use App\Modules\Auth\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthServicesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
        );
    }

    public function boot(): void
    {
        Route::prefix('auth')
            ->group(__DIR__ . '/../Interfaces/Routes/web.php');

        Route::prefix('api')
            ->group(__DIR__ . '/../Interfaces/Routes/api.php');
    }
}