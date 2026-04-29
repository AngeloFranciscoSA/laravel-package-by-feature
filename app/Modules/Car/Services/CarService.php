<?php

namespace App\Modules\Car\Services;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\Seller;
use App\Modules\Car\Repositories\CarRepository;
use App\Modules\Car\Repositories\Contracts\SellerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CarService
{
    public function __construct(
        protected CarRepository $repository,
        protected SellerRepositoryInterface $sellerRepository,
    ) {}

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
        $id = $data["id"];
        unset($data['id']);
        $data['price'] = str_replace(",", "", $data['price']);
        return $this->repository->update($id, $data);
    }

    public function deleteCar(int $id): ?bool
    {
        return $this->repository->destroy(id: $id);
    }

    public function getFeaturedCars(): Collection
    {
        return Car::where('featured', true)
            ->with('seller')
            ->orderBy('views', 'desc')
            ->get();
    }

    public function getMostViewedCars(int $limit = 6): Collection
    {
        return Car::orderBy('views', 'desc')
            ->with('seller')
            ->limit($limit)
            ->get();
    }

    public function getOtherCars(int $limit = 8): Collection
    {
        return Car::where('featured', false)
            ->orderBy('views', 'desc')
            ->with('seller')
            ->limit($limit)
            ->get();
    }

    public function searchCars(array $filters, string $sortBy = 'views'): Collection
    {
        $query = Car::query()->with('seller');

        if (isset($filters['brand']) && $filters['brand'] !== null && $filters['brand'] !== '' && $filters['brand'] !== 'All') {
            $query->where('brand', $filters['brand']);
        }
        if (isset($filters['state']) && $filters['state'] !== null && $filters['state'] !== '' && $filters['state'] !== 'All') {
            $query->where('state', $filters['state']);
        }
        if (isset($filters['fuel']) && $filters['fuel'] !== null && $filters['fuel'] !== '' && $filters['fuel'] !== 'All') {
            $query->where('fuel', $filters['fuel']);
        }
        if (isset($filters['transmission']) && $filters['transmission'] !== null && $filters['transmission'] !== '' && $filters['transmission'] !== 'All') {
            $query->where('transmission', $filters['transmission']);
        }
        if (isset($filters['color']) && $filters['color'] !== null && $filters['color'] !== '' && $filters['color'] !== 'All') {
            $query->where('color', $filters['color']);
        }
        if (isset($filters['min_price']) && $filters['min_price'] !== null && $filters['min_price'] !== '') {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (isset($filters['max_price']) && $filters['max_price'] !== null && $filters['max_price'] !== '') {
            $query->where('price', '<=', $filters['max_price']);
        }
        if (isset($filters['min_year']) && $filters['min_year'] !== null && $filters['min_year'] !== '') {
            $query->where('year', '>=', $filters['min_year']);
        }
        if (isset($filters['max_year']) && $filters['max_year'] !== null && $filters['max_year'] !== '') {
            $query->where('year', '<=', $filters['max_year']);
        }
        if (isset($filters['min_km']) && $filters['min_km'] !== null && $filters['min_km'] !== '') {
            $query->where('km', '>=', $filters['min_km']);
        }
        if (isset($filters['max_km']) && $filters['max_km'] !== null && $filters['max_km'] !== '') {
            $query->where('km', '<=', $filters['max_km']);
        }

        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'year':
                $query->orderBy('year', 'desc');
                break;
            default:
                $query->orderBy('views', 'desc');
                break;
        }

        return $query->get();
    }

    public function showCarWithSeller(int $id): Car
    {
        return Car::with(['seller', 'seller.cars'])->findOrFail($id);
    }

    public function getSellerById(int $id): Seller
    {
        return $this->sellerRepository->getSellerById($id);
    }
}