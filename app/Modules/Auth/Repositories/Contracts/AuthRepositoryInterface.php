<?php

declare(strict_types=1);

namespace App\Modules\Auth\Repositories\Contracts;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function create(array $data): User;
}