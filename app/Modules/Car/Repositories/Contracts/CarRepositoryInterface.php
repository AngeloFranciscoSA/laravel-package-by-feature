<?php

namespace App\Modules\Car\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CarRepositoryInterface
{
    public function getCars(): Collection;
}
