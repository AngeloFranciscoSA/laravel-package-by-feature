<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Http\Requests\ShowCarRequests;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ShowCarAction
{
    private CarService $service;

    public function __construct()
    {
        $this->service = new CarService();
    }

    public function __invoke(ShowCarRequests $request): JsonResponse|Car
    {
        try {
            $car = $this->service->showCar(id: $request->input('id'));
            if($request->isJson()){
                return response()->json($car, Response::HTTP_OK);
            }

            return $car;
        }catch (Exception $exception){
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
