<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Interfaces\Services\CarService;
use App\Modules\Car\Models\Car;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeCarAction
{
    public function __construct(
        private CarService $service
    ) {}

    public function __invoke(Request $request): Response|JsonResponse
    {
        $featured = $this->service->getFeaturedCars();
        $mostViewed = $this->service->getMostViewedCars(6);
        $others = $this->service->getOtherCars(8);
        $allCars = Car::orderBy('views', 'desc')->get();

        if ($request->wantsJson()) {
            return response()->json(compact('featured', 'mostViewed', 'others', 'allCars'));
        }

        return Inertia::render('Car/Home', [
            'featured' => $featured,
            'mostViewed' => $mostViewed,
            'others' => $others,
        ]);
    }
}