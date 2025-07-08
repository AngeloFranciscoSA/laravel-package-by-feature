<?php

namespace App\Modules\Car\Services;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Repositories\CarRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CarService
{

    protected CarRepository $repository;

    public function __construct(CarRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCarsPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->repository->getPaginatedCars($perPage);
    }

    public function showCar(int $id): Car
    {
        return $this->repository->getCarById($id);
    }

    public function editCar(array $data): bool
    {
        //Capturando ID
        $id = $data["id"];
        unset($data['id']);

        // Tratando valores com strings erradas.
        $data['price'] = str_replace(",", "", $data['price']);

        return $this->repository->update($id, $data);
    }

    public function deleteCar(int $id): ?bool
    {
        return $this->repository->destroy(id: $id);
    }
}
