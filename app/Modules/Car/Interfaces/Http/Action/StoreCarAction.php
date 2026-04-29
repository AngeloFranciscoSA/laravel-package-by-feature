<?php

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Domain\Entities\Car;
use App\Modules\Car\Domain\Services\CarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreCarAction
{
    public function __construct(
        private readonly CarService $service,
    ) {}

    public function __invoke(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'brand'        => 'required|string|max:100',
            'model'        => 'required|string|max:100',
            'year'         => 'required|integer|min:1990|max:2030',
            'km'           => 'required|integer|min:0',
            'fuel'         => 'required|string',
            'transmission' => 'required|string',
            'color'        => 'required|string|max:50',
            'price'        => 'required|numeric|min:0',
            'state'        => 'required|string|max:2',
            'city'         => 'required|string|max:100',
            'description'  => 'nullable|string',
            'name'         => 'required|string|max:100',
            'phone'        => 'required|string|max:20',
            'email'        => 'required|email',
        ]);

        $car = Car::create([
            'brand'        => $validated['brand'],
            'model'        => $validated['model'],
            'year'         => $validated['year'],
            'km'           => $validated['km'],
            'fuel'         => $validated['fuel'],
            'transmission' => $validated['transmission'],
            'color'        => $validated['color'],
            'price'        => $validated['price'],
            'city'         => $validated['city'],
            'state'        => $validated['state'],
            'views'        => 0,
            'featured'     => false,
            'badge'        => null,
        ]);

        if ($request->wantsJson()) {
            return response()->json($car, 201);
        }

        return redirect()->route('cars.index')->with([
            'msg'  => 'Listing published!',
            'type' => 'success',
        ]);
    }
}