<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Services\CarService;

class UpdateCarAction
{

    private CarService $service;

    public function __construct(CarService $service){
        $this->service = $service;
    }

    public function __invoke(Car $car): bool
    {
        return $this->service->deleteCar($car);
    }
}
