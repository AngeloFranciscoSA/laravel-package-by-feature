<?php

namespace Modules\Car\Feature\Actions;

use App\Modules\Car\Interfaces\Http\Action\UpdateCarAction;
use App\Modules\Car\Interfaces\Http\Requests\EditCarRequests;
use App\Modules\Car\Models\Car;
use App\Modules\Car\Services\CarService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class EditCarActionTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_edit_car()
    {
        $car = Car::factory()->create([
            'brand' => 'Old Brand',
            'model' => 'Old Mark',
            'year' => '2017',
            'color' => 'Red',
            'price' => 2000,
        ]);

        $updateInfos = [
            'brand' => 'New Brand',
            'model' => 'New Mark',
            'year' => '2020',
            'color' => 'Blue',
            'price' => 5000,
        ];

        $serviceMock = Mockery::mock(CarService::class);
        $serviceMock->shouldReceive('editCar')
            ->with($car, $updateInfos)
            ->andReturn(true);

        $requestMock = Mockery::mock(EditCarRequests::class);
        $requestMock->shouldReceive('all')
        ->once()
        ->andReturn($updateInfos);

        $action = new UpdateCarAction($serviceMock);
        $result = $action->__invoke($car, $requestMock);

        $this->assertTrue($result);
    }
}
