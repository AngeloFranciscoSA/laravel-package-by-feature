<?php

declare(strict_types=1);

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected AuthRepositoryInterface $repository;

    public function __construct(AuthRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function login(string $email, string $password): ?string
    {
        $user = $this->repository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        Auth::login($user);

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function register(array $data): string
    {
        $user = $this->repository->create($data);

        Auth::login($user);

        return $user->createToken('auth_token')->plainTextToken;
    }

    public function logout(): void
    {
        $user = Auth::user();

        if ($user && method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        Auth::logout();
    }
}