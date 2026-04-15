# Architecture — Package by Feature

## What is Package by Feature?

Package by Feature is a way of organizing code around **business domains** instead of technical layers.

In a traditional Laravel project (flat MVC), you'd have:
```
app/
├── Http/Controllers/CarController.php
├── Models/Car.php
├── Services/CarService.php
└── Repositories/CarRepository.php
```

Every feature shares the same folders. As the project grows, each folder becomes harder to navigate and changes to one feature can accidentally affect another.

In Package by Feature, each domain owns its entire vertical slice:
```
app/Modules/Car/
├── Interfaces/Http/Action/    # Controllers
├── Interfaces/Http/Requests/  # Validation
├── Interfaces/Routes/         # Routes
├── Models/                    # Eloquent model
├── Providers/                 # Service provider
├── Repositories/              # Data access
├── Resources/Pages/           # Vue pages
└── Services/                  # Business logic
```

The Car module has everything it needs to work. You can understand, test, or delete it without touching anything else.

---

## Layer Responsibilities

### Action (Controller)
Invokable class — one action per file. Receives the request, delegates to the Service, returns an Inertia response or JSON.

```php
// app/Modules/Car/Interfaces/Http/Action/ListCarAction.php
public function __invoke(ListCarRequests $request): Response|JsonResponse
{
    $cars = $this->service->getAllCarsPaginated(perPage: 15);
    return Inertia::render('Car/Index', ['cars' => $cars]);
}
```

**Rule:** Actions know nothing about the database. They only talk to the Service.

### FormRequest
Handles validation and merges route parameters before the Action runs.

```php
protected function prepareForValidation(): void
{
    $this->merge($this->route()->parameters());
}
```

### Service
Contains business logic. Calls the Repository. Does not know about HTTP or views.

```php
public function getAllCarsPaginated(int $perPage): LengthAwarePaginator
{
    return $this->repository->getPaginatedCars($perPage);
}
```

**Rule:** Services never touch `Request`, `response()`, or `Inertia::render()`.

### Repository
The only layer that talks to Eloquent. Implements a contract (interface).

```php
// Contract
interface CarRepositoryInterface
{
    public function getPaginatedCars(int $perPage): LengthAwarePaginator;
}

// Implementation
class CarRepository implements CarRepositoryInterface
{
    public function getPaginatedCars(int $perPage): LengthAwarePaginator
    {
        return Car::query()->paginate($perPage);
    }
}
```

**Why an interface?** It allows you to swap the implementation (e.g., switch from Eloquent to an external API) without changing the Service or tests.

### Service Provider
Registers the module's routes and bindings when the application boots.

```php
public function boot(): void
{
    Route::prefix('')->group(__DIR__ . '/../Interfaces/Routes/web.php');
    Route::prefix('api')->group(__DIR__ . '/../Interfaces/Routes/api.php');
}
```

---

## Request Flow

```
Browser Request
    └── FormRequest (validate + merge route params)
            └── Action (receive, delegate, respond)
                    └── Service (business logic)
                            └── Repository (query)
                                    └── Eloquent Model
                                            └── SQLite
```

On the way back:
```
Eloquent result
    └── Repository returns typed object
            └── Service returns typed object
                    └── Action calls Inertia::render()
                            └── Inertia serializes props to JSON
                                    └── Vue receives props via defineProps()
                                            └── Browser renders the page
```

---

## Why Inertia.js instead of a separate API?

With a REST API + axios approach, Vue pages would live in `resources/js/pages/Car/` — separate from the module. The feature stops being self-contained.

With Inertia, the Action still controls what data the page receives. Vue pages live inside the module at `Resources/Pages/`. The module remains the single source of truth for its domain.

|                              | Inertia                  | REST API + axios                     |
|------------------------------|--------------------------|--------------------------------------|
| Pages co-located with module | Yes                      | No                                   |
| Module is self-contained     | Yes                      | No                                   |
| Boilerplate                  | Low                      | High (stores, fetch, error handling) |
| Best when                    | UI is part of the module | Separate frontend app / mobile       |
