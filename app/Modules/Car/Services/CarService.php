<?php

namespace App\Modules\Car\Services;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Repositories\CarRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class CarService
{

    protected CarRepository $repository;

    public function __construct()
    {
        $this->repository = new CarRepository();
    }

    public function listCars(int $perPage): Paginator
    {
        return $this->repository->getPaginatedCars($perPage);
    }

    public function showCar(int $id): Car
    {
        return $this->repository->getCarById($id);
    }
}
