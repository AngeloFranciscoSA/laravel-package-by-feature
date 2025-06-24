<?php

namespace Tests\Modules\Car\Unit\Services;

use App\Modules\Car\Models\Car;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use App\Modules\Car\Services\CarService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_all_cars_paginated()
    {
        Car::factory()->count(30)->create();
        $service = new CarService();

        $result = $service->getAllCarsPaginated(15);

        $this->assertTrue($result->isNotEmpty());
        $this->assertEquals(30, $result->total());
        $this->assertEquals(15, $result->perPage());
        $this->assertEquals(2, $result->lastPage());
        $this->assertEquals(1, $result->currentPage());

        $this->assertContainsOnlyInstancesOf(Car::class, $result->items());
    }

    public function test_it_can_list_one_car()
    {
        $car = Car::factory()->create([
            'brand' => 'New Brand',
            'model' => 'New Mark',
            'year' => '2017',
            'color' => 'Red',
            'price' => 2000,
        ]);
        $service = new CarService();

        $result = $service->showCar($car->id);

        $this->assertEquals($car->id, $result->id);
        $this->assertEquals($car->brand, $result->brand);
        $this->assertEquals($car->model, $result->model);
        $this->assertEquals($car->year, $result->year);
        $this->assertEquals($car->color, $result->color);
        $this->assertEquals($car->price, $result->price);

    }

    public function test_it_can_edit_a_car()
    {
        $car = Car::factory()->create([
            'brand' => 'Old Brand',
            'model' => 'Old Mark',
            'year' => '2017',
            'color' => 'Red',
            'price' => 2000,
        ]);

        $service = new CarService();

        $updateInfos = [
            'brand' => 'New Brand',
            'model' => 'New Mark',
            'year' => '2020',
            'color' => 'Blue',
            'price' => 5000,
        ];

        $result = $service->editCar($car, $updateInfos);

        $car->refresh();

        $this->assertTrue($result);

        $this->assertEquals($updateInfos['brand'], $car->brand);
        $this->assertEquals($updateInfos['model'], $car->model);
        $this->assertEquals($updateInfos['year'], $car->year);
        $this->assertEquals($updateInfos['color'], $car->color);
        $this->assertEquals($updateInfos['price'], $car->price);
    }

    public function test_it_can_delete_a_car()
    {
        $car = Car::factory()->create();

        $service = new CarService();

        $result = $service->deleteCar($car);

        $this->assertTrue($result);
        $car->refresh();

        $this->assertDatabaseMissing('cars', [
            'id' => $car->id,
        ]);
    }
}
