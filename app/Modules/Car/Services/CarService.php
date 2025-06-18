<?php

namespace App\Modules\Car\Services;

use App\Modules\Car\Repositories\CarRepository;
use Illuminate\Database\Eloquent\Collection;

class CarService
{

    protected CarRepository $repository;

    public function __construct()
    {
        $this->repository = new CarRepository();
    }

    public function listCars(int $perPage = 15): Collection
    {
        return $this->repository->getPaginatedCars($perPage);
    }
}
