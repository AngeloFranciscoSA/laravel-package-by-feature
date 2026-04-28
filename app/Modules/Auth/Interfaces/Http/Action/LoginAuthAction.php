<?php

declare(strict_types=1);

namespace App\Modules\Auth\Interfaces\Http\Action;

use App\Modules\Auth\Interfaces\Http\Requests\LoginAuthRequest;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LoginAuthAction
{
    public function __construct(
        private readonly AuthService $service,
    ) {}

    public function __invoke(LoginAuthRequest $request): Response|JsonResponse|RedirectResponse
    {
        if ($request->isMethod('GET')) {
            return Inertia::render('Auth/Login');
        }

        $token = $this->service->login($request->email, $request->password);

        if ($token === null) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Invalid credentials.'], 401);
            }

            return redirect()->back()->withErrors(['email' => 'Invalid credentials.']);
        }

        if ($request->wantsJson()) {
            return response()->json(['token' => $token]);
        }

        return redirect()->route('cars.index');
    }
}
