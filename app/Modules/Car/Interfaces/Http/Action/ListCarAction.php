<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Http\Requests\ListCarRequests;
use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class ListCarAction
{
    private CarService $service;

    public function __construct(CarService $service)
    {
        $this->service = $service;
    }

    public function __invoke(ListCarRequests $request): Response|JsonResponse
    {
        try {
            $cars = $this->service->getAllCarsPaginated(perPage: $request->input('perPage') ?? 15);

            // Rota da API continua funcionando normalmente
            if ($request->wantsJson()) {
                return response()->json($cars);
            }

            // 'Car/Index' → o resolver do app.js vai buscar:
            // app/Modules/Car/Resources/Pages/Index.vue
            return Inertia::render('Car/Index', [
                'cars' => $cars,
            ]);
        } catch (Exception $exception) {
            return response()->json([$exception->getMessage(), $exception->getCode()], 500);
        }
    }
}
