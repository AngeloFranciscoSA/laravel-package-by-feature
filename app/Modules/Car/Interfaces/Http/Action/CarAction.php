<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Http\Requests\CarRequests;
use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class CarAction
{
    private CarService $service;

    public function __construct()
    {
        $this->service = new CarService();
    }

    public function __invoke(CarRequests $request): Collection|JsonResponse
    {
        try {
            return $this->service->getCars(
                id: $request->get('id'),
                records: $request->get('records'),
                page: $request->get('page'),
            );
        }catch (Exception $exception){
            return response()->json([$exception->getMessage(), $exception->getCode()], 500);
        }
    }
}
