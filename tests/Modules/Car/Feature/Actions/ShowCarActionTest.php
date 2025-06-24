<?php

namespace Modules\Car\Feature\Actions;

use App\Modules\Car\Interfaces\Http\Action\ShowCarAction;
use App\Modules\Car\Interfaces\Http\Requests\ShowCarRequests;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Services\CarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;

class ShowCarActionTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_can_return_one_car()
    {
        $car = Car::factory()->create();

        $serviceMock = Mockery::mock(CarService::class);
        $serviceMock->shouldReceive('showCar')
            ->with($car->id)
            ->andReturn($car);

        $requestMock = Mockery::mock(ShowCarRequests::class);
        $requestMock->shouldReceive('input')->with('id')->andReturn($car->id);
        $requestMock->shouldReceive('isJson')->andReturn(true);

        $action = new ShowCarAction($serviceMock);
        $response = $action($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($car->toArray(), $response->getData(true));
    }
}
