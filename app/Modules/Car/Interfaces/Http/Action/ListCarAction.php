<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Http\Requests\ListCarRequests;
use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;

class ListCarAction
{
    private CarService $service;

    public function __construct()
    {
        $this->service = new CarService();
    }

    /**
     * @param ListCarRequests $request
     * @return Paginator|JsonResponse
     */
    public function __invoke(ListCarRequests $request): Paginator|JsonResponse
    {
        try {
            $cars = $this->service->listCars(perPage: $request->input('perPage') ?? 15);

            if($request->wantsJson()){
                return response()->json($cars);
            }

            return $cars;
        }catch (Exception $exception){
            return response()->json([$exception->getMessage(), $exception->getCode()], 500);
        }
    }
}
