<?php

declare(strict_types=1);

namespace App\Modules\Car\Interfaces\Http\Action;

use App\Modules\Car\Services\CarService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Inertia\Inertia;

class ShowSellerAction
{
    public function __construct(
        private CarService $service
    ) {}

    public function __invoke(Request $request, int $id): Response|JsonResponse
    {
        try {
            $seller = $this->service->getSellerById($id);
        } catch (ModelNotFoundException $e) {
            if ($request->wantsJson()) {
                return new JsonResponse(['message' => 'Seller not found'], 404);
            }

            return redirect()->back()->with('error', 'Seller not found');
        }

        if ($request->wantsJson()) {
            return new JsonResponse($seller);
        }

        return Inertia::render('Car/Seller', [
            'seller' => $seller,
            'cars' => $seller->cars,
        ]);
    }
}