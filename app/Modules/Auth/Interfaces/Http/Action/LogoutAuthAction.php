<?php

declare(strict_types=1);

namespace App\Modules\Auth\Interfaces\Http\Action;

use App\Modules\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LogoutAuthAction
{
    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request): JsonResponse|RedirectResponse
    {
        $this->service->logout();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully.']);
        }

        return redirect()->route('auth.login');
    }
}