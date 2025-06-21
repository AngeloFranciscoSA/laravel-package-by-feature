<?php

namespace Tests\Modules\Car\Unit\Repositories;

use Tests\TestCase;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Repositories\CarRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test function: Return all carrs.
     * @return void
     */
    public function test_can_return_all_cars(): void
    {
        $car = Car::factory()->create();
        $repository = app(CarRepository::class);
        $cars = $repository->getAllCars();

        $this->assertCount(1, $cars);
        $this->assertEquals($car->id, $cars->first()->id);
    }

    /**
     * Test function: Return a single car.
     * @return void
     */
    public function test_can_return_a_single_car(): void
    {
        $car = Car::factory()->create();
        $repository = app(CarRepository::class);
        $car = $repository->getCarById($car->id);

        $this->assertInstanceOf(Car::class, $car);
        $this->assertEquals(1, $car->count());
        $this->assertEquals($car->id, $car->id);
    }

    /**
     * Test function: Insert a car.
     * @return void
     */
    public function test_can_insert_a_car(): void
    {
        $car = [
            'brand' => 'New Brand',
            'model' => 'New Mark',
            'year' => '2017',
            'color' => 'Red',
            'price' => 2000,
        ];

        $repository = new CarRepository();

        $created = $repository->create($car);

        $this->assertEquals($car['brand'], $created->brand);
        $this->assertEquals($car['model'], $created->model);
        $this->assertEquals($car['year'], $created->year);
        $this->assertEquals($car['color'], $created->color);
        $this->assertEquals($car['price'], $created->price);
        $this->assertDatabaseHas('cars', $car);
    }
}
