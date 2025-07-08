<?php

namespace App\Modules\Car\Repositories\Contracts;

use App\Modules\Car\Models\Car;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CarRepositoryInterface
{
    public function getAllCars(): Collection;

    public function getCarById(int $id): Car;

    public function getPaginatedCars(int $perPage): LengthAwarePaginator;
    public function create(array $car): Car;
    public function update(int $id, array $data): bool;
    public function destroy(int $id): ?bool;
}
