<?php

namespace Tests\Modules\Car\Unit\Services;

use Tests\TestCase;
use App\Modules\Car\Services\CarService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CarServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_exemplo_de_servico()
    {
        $service = new CarService();

        $this->assertTrue(true);
    }
}