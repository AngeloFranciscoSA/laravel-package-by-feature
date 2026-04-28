<?php

use App\Modules\Auth\Providers\AuthServicesProvider;
use App\Modules\Car\Providers\CarServicesProvider;
use App\Modules\Comms\Providers\PaginationServiceProvider;

return [
    PaginationServiceProvider::class,
    CarServicesProvider::class,
    AuthServicesProvider::class,
];
