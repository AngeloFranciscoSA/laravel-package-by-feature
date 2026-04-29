<?php

declare(strict_types=1);

namespace App\Modules\Car\Repositories;

use App\Modules\Car\Models\Seller;
use App\Modules\Car\Repositories\Contracts\SellerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SellerRepository implements SellerRepositoryInterface
{
    public function getSellerById(int $id): Seller
    {
        return Seller::with('cars')->findOrFail($id);
    }

    public function getAllSellers(): Collection
    {
        return Seller::all();
    }
}