<?php

namespace Tests\Modules\Car\Unit\Repositories;

use Tests\TestCase;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Repositories\CarRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_exemplo_de_repositorio()
    {
        $repository = new CarRepository();

        $this->assertTrue(true);
    }
}