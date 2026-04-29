<?php

namespace App\Modules\Car\Repositories\Contracts;

use App\Modules\Car\Models\Seller;
use Illuminate\Database\Eloquent\Collection;

interface SellerRepositoryInterface
{
    public function getSellerById(int $id): Seller;
    public function getAllSellers(): Collection;
}