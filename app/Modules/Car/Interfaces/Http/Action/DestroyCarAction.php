<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Http\Requests\DestroyCarRequests;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class DestroyCarAction
{

    private CarService $service;

    public function __construct(CarService $service){
        $this->service = $service;
    }

    public function __invoke(DestroyCarRequests $request): RedirectResponse|JsonResponse
    {
        try {
            $car = $this->service->deleteCar(id: $request->get('id'));
            if($car){
                return redirect()->route('cars.index')
                    ->with([
                        'msg' => 'Car deleted!',
                        'type' => 'success',
                    ]);
            }
            return redirect()->route('cars.index')
                ->with([
                    'msg' => 'Car not deleted!',
                    'type' => 'error',
                ]);
        }catch (Exception $exception){
            return response()->json([$exception->getMessage(), $exception->getCode()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
