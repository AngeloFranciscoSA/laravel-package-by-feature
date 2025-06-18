<?php

namespace Database\Seeders;



use App\Modules\Car\Models\Car;
use Illuminate\Database\Seeder;


class CarSeeder extends Seeder {
    public function run(): void {
        Car::factory()->count(50)->create();
    }
}
