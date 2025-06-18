<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Http\Requests\CarRequests;
use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Http\JsonResponse;

class CarAction
{
    private CarService $service;

    public function __construct()
    {
        $this->service = new CarService();
    }

    public function __invoke(CarRequests $request): JsonResponse
    {
        try {
            $cars = $this->service->listCars();
            return response()->json($cars);
        }catch (Exception $exception){
            return response()->json([$exception->getMessage(), $exception->getCode()], 500);
        }
    }
}
