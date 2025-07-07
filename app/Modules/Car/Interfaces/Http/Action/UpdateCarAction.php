<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Http\Requests\EditCarRequests;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Services\CarService;

class UpdateCarAction
{

    private CarService $service;

    public function __construct(CarService $service){
        $this->service = $service;
    }

    public function __invoke(Car $car, EditCarRequests $request): bool
    {
        $data = $request->all();
        return $this->service->editCar($car, $data);
    }
}
