<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ShowCarAction
{
    private CarService $service;

    public function __construct(CarService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request, int $id): JsonResponse|\Illuminate\Http\RedirectResponse|\Inertia\Response
    {
        try {
            $car = $this->service->showCarWithSeller($id);
            $seller = $car->seller;
            $relatedCars = $seller ? $seller->cars->reject(fn($c) => $c->id === $car->id) : collect();

            if ($request->wantsJson()) {
                return response()->json($car->toArray(), SymfonyResponse::HTTP_OK);
            }

            return Inertia::render('Car/Detail', [
                'car' => $car,
                'relatedCars' => $relatedCars,
                'seller' => $seller,
            ]);
        } catch (ModelNotFoundException $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Car not found.'], SymfonyResponse::HTTP_NOT_FOUND);
            }

            return redirect()->back()->withErrors(['error' => 'Car not found.']);
        } catch (Exception $exception) {
            if ($request->wantsJson()) {
                return response()->json(['error' => $exception->getMessage()], SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }
}