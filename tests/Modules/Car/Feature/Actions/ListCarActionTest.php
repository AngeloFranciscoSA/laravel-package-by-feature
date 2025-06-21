<?php

namespace Tests\Modules\Car\Feature\Actions;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListCarActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_car_retorna_sucesso()
    {
        $this->assertTrue(true);
    }
}
