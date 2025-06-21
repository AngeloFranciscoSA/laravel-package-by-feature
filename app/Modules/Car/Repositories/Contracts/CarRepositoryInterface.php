<?php

namespace App\Modules\Car\Repositories\Contracts;

use App\Modules\Car\Models\Car;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

interface CarRepositoryInterface
{
    public function getAllCars(): Collection;

    public function getCarById(int $id): Car;

    public function getPaginatedCars(int $perPage): Paginator;
    public function create(array $car): Car;
    public function update(Car $car);
    public function destroy(Car $car);
}
