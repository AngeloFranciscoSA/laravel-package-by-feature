<?php

declare(strict_types=1);

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Application\Services\CarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class SearchCarAction
{
    private CarService $service;

    public function __construct(CarService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request): Response|JsonResponse
    {
        $filterKeys = [
            'brand',
            'state',
            'fuel',
            'transmission',
            'color',
            'min_price',
            'max_price',
            'min_year',
            'max_year',
            'min_km',
            'max_km',
        ];

        $filters = [];
        foreach ($filterKeys as $key) {
            $value = $request->input($key);
            if ($value !== null && $value !== '' && $value !== 'All') {
                $filters[$key] = $value;
            }
        }

        $sortBy = $request->input('sort', 'views');
        $cars = $this->service->searchCars($filters, $sortBy);

        if ($request->wantsJson()) {
            return response()->json([
                'cars' => $cars,
                'total' => $cars->count(),
            ]);
        }

        return Inertia::render('Car/Search', [
            'cars' => $cars,
            'filters' => $request->only($filterKeys),
            'sort' => $sortBy,
        ]);
    }
}