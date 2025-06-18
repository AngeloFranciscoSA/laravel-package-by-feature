<?php

namespace App\Modules\Car\Repositories;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Repositories\Contracts\CarRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CarRepository implements CarRepositoryInterface
{
    public function getCars(): Collection
    {
        return Car::all();
    }
}
