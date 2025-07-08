<?php

namespace App\Modules\Car\Repositories;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Repositories\Contracts\CarRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function getPaginatedCars(int $perPage): LengthAwarePaginator
    {
        return Car::query()->paginate($perPage);
    }

    public function create(array $car): Car
    {
        return Car::create($car);
    }

    public function update(int $id, array $data): bool
    {
        $car = Car::find($id);
        if($car){
            return $car->update($data);
        }
        return false;
    }

    public function destroy(int $id): bool
    {
        $car = Car::find($id);
        if($car){
            return $car->delete();
        }
        return false;
    }
}
