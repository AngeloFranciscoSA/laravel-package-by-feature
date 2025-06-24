<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Http\Requests\ShowCarRequests;
use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ShowCarAction
{
    private CarService $service;

    public function __construct(CarService $service)
    {
        $this->service = $service;
    }

    public function __invoke(ShowCarRequests $request): JsonResponse|View
    {
        try {
            $car = $this->service->showCar(id: $request->input('id'));
            if($request->isJson()){
                return response()->json($car, Response::HTTP_OK);
            }

            return view('car::show', compact('car'));
        }catch (ModelNotFoundException $e){
            return response()->json(['error' => 'Carro não encontrado.'], Response::HTTP_NOT_FOUND);
        }
        catch (Exception $exception){
            return response()->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
