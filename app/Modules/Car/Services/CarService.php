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

    /**
     * @param null|int $id The Folder's id
     * @param null|int $records Number of elements per page
     * @param null|int $page Current page number
     * @return Collection Paginated folder's data
     */
    public function getCars(null|int $id, null|int $records, null|int $page): Collection
    {
        return $this->repository->getCars();
    }
}
