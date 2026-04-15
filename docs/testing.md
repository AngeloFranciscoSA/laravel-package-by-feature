# Testing Guide

This project uses PHPUnit with Mockery for unit/feature tests. For Inertia responses, tests go via HTTP using `assertInertia()`.

---

## Two patterns in use

### Pattern 1 — Direct Action instantiation (unit-style)

Used when the response is a `JsonResponse` or `RedirectResponse`. The Action is instantiated directly with mocked dependencies.

```php
$serviceMock = Mockery::mock(CarService::class);
$serviceMock->shouldReceive('someMethod')->andReturn($value);

$requestMock = Mockery::mock(SomeFormRequest::class);
$requestMock->shouldReceive('input')->with('id')->andReturn(1);

$action = new SomeAction($serviceMock);
$response = $action($requestMock);

$this->assertInstanceOf(JsonResponse::class, $response);
```

**When to use:** Actions that return `response()->json()` or `redirect()`.

---

### Pattern 2 — HTTP request with `assertInertia()` (feature-style)

Used when the response is an Inertia page. Goes through the full HTTP stack so Inertia can serialize the response correctly.

```php
Car::factory()->count(3)->create();

$this->get('/cars')
    ->assertOk()
    ->assertInertia(fn (AssertableInertia $page) => $page
        ->component('Car/Index', false) // false = skip .vue file existence check
        ->has('cars')
        ->has('cars.data', 3)
    );
```

**When to use:** Actions that return `Inertia::render()`.

**Why `false` in `->component()`?**
By default, Inertia checks if the `.vue` file exists in `resources/js/Pages/`. Since this project keeps pages inside modules (`app/Modules/Car/Resources/Pages/`), the check would fail. Passing `false` skips the file lookup while still asserting the component name.

---

## `assertInertia()` — available assertions

```php
->assertInertia(fn (AssertableInertia $page) => $page

    // Assert the component name sent by Inertia::render()
    ->component('Car/Index', false)

    // Assert a prop key exists
    ->has('cars')

    // Assert a prop key exists with an exact count
    ->has('cars.data', 3)

    // Assert a prop key has a specific value
    ->where('cars.current_page', 1)

    // Assert a nested prop using a callback
    ->has('cars.data.0', fn (AssertableInertia $car) => $car
        ->has('id')
        ->has('brand')
        ->has('model')
        ->where('brand', 'Toyota')
        ->etc() // ignores any other keys not explicitly checked
    )
)
```

---

## Testing redirects (UpdateCarAction, DestroyCarAction)

These actions return `redirect()->route(...)->with([flash])`. Test them with Pattern 1:

```php
$serviceMock = Mockery::mock(CarService::class);
$serviceMock->shouldReceive('editCar')->with($data)->andReturn(true);

$requestMock = Mockery::mock(EditCarRequests::class);
$requestMock->shouldReceive('validated')->once()->andReturn($data);

$action = new UpdateCarAction($serviceMock);
$response = $action($requestMock);

// Assert redirect
$this->assertEquals(302, $response->getStatusCode());

// Assert flash message in session
$this->assertEquals('Car updated successfully!', $response->getSession()->get('msg'));
$this->assertEquals('success', $response->getSession()->get('type'));
```

---

## Testing JSON responses (API routes)

```php
$requestMock->shouldReceive('wantsJson')->andReturn(true);

$response = $action($requestMock);

$this->assertInstanceOf(JsonResponse::class, $response);
$this->assertEquals(200, $response->getStatusCode());
$this->assertEquals($expectedData->toArray(), $response->getData(true));
```

---

## Full example — ListCarAction

```php
use Inertia\Testing\AssertableInertia;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListCarActionTest extends TestCase
{
    use RefreshDatabase;

    // Inertia page — use HTTP + assertInertia()
    public function test_it_returns_inertia_when_request_does_not_want_json()
    {
        Car::factory()->count(3)->create();

        $this->get('/cars')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Car/Index', false)
                ->has('cars')
                ->has('cars.data', 3)
            );
    }

    // JSON — use direct Action instantiation
    public function test_it_returns_json_when_request_wants_json()
    {
        $cars = new LengthAwarePaginator([], 0, 15);

        $serviceMock = Mockery::mock(CarService::class);
        $serviceMock->shouldReceive('getAllCarsPaginated')->with(15)->andReturn($cars);

        $requestMock = Mockery::mock(ListCarRequests::class);
        $requestMock->shouldReceive('input')->with('perPage')->andReturn(null);
        $requestMock->shouldReceive('wantsJson')->andReturn(true);

        $action = new ListCarAction($serviceMock);
        $response = $action($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
```

---

## Checklist per Action

| Action | Response type | Pattern to use |
| --- | --- | --- |
| `ListCarAction` (web) | `Inertia::render()` | HTTP + `assertInertia()` |
| `ListCarAction` (JSON) | `JsonResponse` | Direct instantiation |
| `ShowCarAction` (web) | `Inertia::render()` | HTTP + `assertInertia()` |
| `ShowCarAction` (JSON) | `JsonResponse` | Direct instantiation |
| `UpdateCarAction` | `RedirectResponse` | Direct instantiation |
| `DestroyCarAction` | `RedirectResponse` | Direct instantiation |
