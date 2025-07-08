<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Http\Requests\EditCarRequests;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateCarAction
{

    private CarService $service;

    public function __construct(CarService $service){
        $this->service = $service;
    }

    public function __invoke(EditCarRequests $request): RedirectResponse|JsonResponse
    {
        try {
            $data = $request->validated();
            $car = $this->service->editCar(data: $data);

            if($car){
                return redirect()->route('cars.index')
                    ->with([
                        'msg' => 'Car updated successfully!',
                        'type' => 'success',
                    ]);
            }
            return redirect()->route('cars.index')
                ->with([
                    'msg' => 'Car not updated!',
                    'type' => 'error',
                ]);

        }catch (Exception $exception){
            return response()->json([$exception->getMessage(), $exception->getCode()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
