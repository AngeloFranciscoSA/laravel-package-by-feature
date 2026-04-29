<?php

declare(strict_types=1);

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Services\CarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class CompareCarAction
{
    public function __construct(
        private readonly CarService $service
    ) {}

    public function __invoke(Request $request): Response|JsonResponse
    {
        $allCars = Car::with('seller')
            ->orderBy('views', 'desc')
            ->get();

        if ($request->wantsJson()) {
            return response()->json($allCars);
        }

        return Inertia::render('Car/Compare', [
            'allCars' => $allCars,
        ]);
    }
}
