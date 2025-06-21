<?php

namespace App\Modules\Car\Repositories;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Repositories\Contracts\CarRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class CarRepository implements CarRepositoryInterface
{
    public function getAllCars(): Collection
    {
        return Car::all();
    }

    public function getCarById(int $id): Car
    {
        return Car::find($id);
    }

    public function getPaginatedCars(int $perPage): Paginator
    {
        return Car::simplePaginate($perPage);
    }

    public function create(array $car): Car
    {
        return Car::create($car);
    }

    public function update(Car $car, array $data): bool
    {
        return $car->update($data);
    }

    public function destroy(Car $car): bool
    {
        return $car->delete();
    }
}
