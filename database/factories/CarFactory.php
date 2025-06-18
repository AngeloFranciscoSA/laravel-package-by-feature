<?php

namespace Database\Factories;


use App\Modules\Car\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand' => $this->faker->randomElement(['Toyota', 'Ford', 'Chevrolet', 'Honda', 'BMW']),
            'model' => $this->faker->word(),
            'year' => $this->faker->year(),
            'color' => $this->faker->safeColorName(),
            'price' => $this->faker->randomFloat(2, 10000, 100000),
        ];
    }
}
