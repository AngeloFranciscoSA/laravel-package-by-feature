<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Services\CarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DestroyCarAction
{

    private CarService $service;

    public function __construct(CarService $service){
        $this->service = $service;
    }

    public function __invoke(Car $car): RedirectResponse|JsonResponse
    {
        try {
            $car = $this->service->deleteCar($car);

            if($car){
                return redirect()->route('cars.index')->with('sucess', 'car deleted successfully!');
            }
            return redirect()->route('cars.index')->with('error', 'car not deleted!');
        }catch (\Exception $exception){
            return response()->json([$exception->getMessage(), $exception->getCode()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
