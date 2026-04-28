<?php

declare(strict_types=1);

namespace App\Modules\Auth\Interfaces\Http\Action;

use App\Modules\Auth\Interfaces\Http\Requests\RegisterAuthRequest;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RegisterAuthAction
{
    public function __construct(
        private readonly AuthService $service,
    ) {}

    public function __invoke(RegisterAuthRequest $request): Response|JsonResponse|RedirectResponse
    {
        if ($request->isMethod('GET')) {
            return Inertia::render('Auth/Register');
        }

        $token = $this->service->register(
            $request->only('name', 'email', 'password')
        );

        if ($request->wantsJson()) {
            return response()->json(['token' => $token], 201);
        }

        return redirect()->route('cars.index');
    }
}
