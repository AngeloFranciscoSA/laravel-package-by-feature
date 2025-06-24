<?php

namespace Tests\Modules\Car\Feature\Actions;

use App\Modules\Car\Interfaces\Http\Action\ListCarAction;
use App\Modules\Car\Interfaces\Http\Requests\ListCarRequests;
use App\Modules\Car\Services\CarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListCarActionTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_returns_view_when_request_does_not_want_json()
    {
        $cars = new LengthAwarePaginator([], 0, 15);

        $serviceMock = Mockery::mock(CarService::class);
        $serviceMock->shouldReceive('getAllCarsPaginated')
            ->with(15)
            ->andReturn($cars);

        $requestMock = Mockery::mock(ListCarRequests::class);
        $requestMock->shouldReceive('input')
            ->with('perPage')
            ->andReturnNull();
        $requestMock->shouldReceive('wantsJson')
            ->andReturn(false);

        $action = new ListCarAction($serviceMock);
        $response = $action($requestMock);

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('car::index', $response->name());
        $this->assertArrayHasKey('cars', $response->getData());
    }

    public function test_it_returns_json_when_request_wants_json()
    {
        $cars = new LengthAwarePaginator([], 0, 15);

        $serviceMock = Mockery::mock(CarService::class);
        $serviceMock->shouldReceive('getAllCarsPaginated')
            ->with(15)
            ->andReturn($cars);

        $requestMock = Mockery::mock(ListCarRequests::class);
        $requestMock->shouldReceive('input')->with('perPage')->andReturn(null);
        $requestMock->shouldReceive('wantsJson')->andReturn(true);

        $action = new ListCarAction($serviceMock);
        $response = $action($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($cars->toArray(), $response->getData(true));
    }

    public function test_it_returns_json_error_when_exception_is_thrown()
    {
        $serviceMock = Mockery::mock(CarService::class);
        $serviceMock->shouldReceive('getAllCarsPaginated')
            ->andThrow(new Exception('Erro', 123));

        $requestMock = Mockery::mock(ListCarRequests::class);
        $requestMock->shouldReceive('input')->andReturn(null);
        $requestMock->shouldReceive('wantsJson')->andReturn(true);

        $action = new ListCarAction($serviceMock);
        $response = $action($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['Erro', 123], $response->getData(true));
    }
}
