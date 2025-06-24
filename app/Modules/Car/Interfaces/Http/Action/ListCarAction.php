<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Http\Requests\ListCarRequests;
use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ListCarAction
{
    private CarService $service;

    public function __construct(CarService $service)
    {
        $this->service = $service;
    }


    /**
     * @param ListCarRequests $request
     * @return JsonResponse|View
     */
    public function __invoke(ListCarRequests $request): JsonResponse|View
    {
        try {
            $cars = $this->service->getAllCarsPaginated(perPage: $request->input('perPage') ?? 15);

            if($request->wantsJson()){
                return response()->json($cars);
            }

            return view('car::index', compact('cars'));
        }catch (Exception $exception){
            return response()->json([$exception->getMessage(), $exception->getCode()], 500);
        }
    }
}
