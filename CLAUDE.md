# CLAUDE.md

## Project overview

Study project implementing **Package by Feature** (modular monolith) with Laravel 12.
Domain: car catalog with CRUD, pagination, and content negotiation (HTML or JSON from the same route).

**Stack:** PHP 8.2+, Laravel 12, Blade, Tailwind CSS 4, Bootstrap 5, Vite 6, SQLite, PHPUnit 11, Mockery, Axios.

---

## Architecture

### Module structure

Every feature lives under `app/Modules/<ModuleName>/` and is self-contained.
Never place feature-specific code outside its module.

```
app/Modules/Car/
├── Interfaces/
│   ├── Http/
│   │   ├── Action/        # Invokable controllers — one action per file
│   │   └── Requests/      # Form Requests with validation rules
│   └── Routes/
│       ├── api.php        # JSON routes  (/api/cars)
│       └── web.php        # Web routes   (/cars)
├── Models/
├── Providers/             # Module service provider — registers bindings
├── Repositories/
│   ├── Contracts/         # Interface that the repository implements
│   └── CarRepository.php
├── Resources/views/       # Views scoped to this module
└── Services/              # Business logic layer
```

### Request flow

```
HTTP Request -> Action (invokable controller) -> Service -> Repository -> Eloquent Model
```

### Key rules

- Controllers are **invokable** (`__invoke`) — one class per HTTP action (List, Show, Update, Destroy).
- **Services** hold business logic. They never touch the database directly.
- **Repositories** own all Eloquent queries. They implement a contract interface.
- Bind the repository interface to its implementation in the module's `ServiceProvider`, never inline.
- Content negotiation: check `$request->wantsJson()` inside the Action to decide whether to return a view or a JsonResource.

---

## Conventions

### Naming

| Artifact | Convention | Example |
|---|---|---|
| Action class | `<Verb><Module>Action` | `ListCarAction`, `ShowCarAction` |
| Service | `<Module>Service` | `CarService` |
| Repository | `<Module>Repository` | `CarRepository` |
| Repository contract | `<Module>RepositoryInterface` | `CarRepositoryInterface` |
| Service provider | `<Module>ServicesProvider` | `CarServicesProvider` |
| Form Request | `<Verb><Module>Request` | `UpdateCarRequest` |
| Route file | `web.php` / `api.php` inside `Interfaces/Routes/` | |
| Test class (Unit) | mirrors source path under `tests/Modules/` | `tests/Modules/Car/Unit/Services/CarServiceTest.php` |
| Test class (Feature) | same, under `Feature/Actions/` | `tests/Modules/Car/Feature/Actions/ListCarActionTest.php` |

### PHP / Laravel style

- PSR-12 code style.
- Type-hint all method parameters and return types.
- Use `readonly` properties in DTOs when applicable.
- Inject dependencies via constructor — never use `app()` or `resolve()` inside business logic.
- Form Requests handle all input validation — never validate in Actions or Services.
- Migrations use `snake_case` column names.

### Frontend

- Blade templates live in `app/Modules/<Module>/Resources/views/`.
- Tailwind CSS 4 utility classes — no custom CSS unless strictly needed.
- JS interactions (confirmation dialogs, carousels) use Axios, Swiper, SweetAlert2 already bundled via `package.json`.
- Assets compiled by Vite 6 (`npm run dev` / `npm run build`).

---

## Running locally

```bash
# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate

# Start all services (Laravel + Vite + queue + Pail log viewer)
composer run dev
```

Default database: SQLite at `database/database.sqlite`.

---

## Testing

```bash
# All tests
php artisan test

# Unit suite only
php artisan test --testsuite=Unit

# Parallel (faster)
php artisan test --parallel
```

Test database: in-memory SQLite (`:memory:`) — configured in `phpunit.xml`, no setup needed.

### What to test

- **Unit (Service):** mock the repository, assert service methods return correct data and call repo methods with correct args.
- **Unit (Repository):** use `RefreshDatabase`, assert queries against the real SQLite `:memory:`.
- **Feature (Action):** use `$this->get()`/`$this->put()` etc., assert HTTP status, response structure, and that the view or JSON key exists.
- No test should hit an external service or the filesystem.

### Scaffolding tests for a new module

```bash
php artisan make:test-module <ModuleName>
```

---

## Adding a new module

```bash
php artisan make:module <ModuleName>
```

This scaffolds the full directory tree. After running it:
1. Implement the repository contract in `Repositories/Contracts/`.
2. Bind the interface in the generated `ServicesProvider`.
3. Register the provider in `config/app.php` (or via `bootstrap/providers.php` in Laravel 12).
4. Add routes to `Interfaces/Routes/web.php` and/or `api.php`.

---

## Routes overview

| Method | URI | Action |
|---|---|---|
| GET | `/cars` | `ListCarAction` — view or JSON |
| GET | `/cars/{id}` | `ShowCarAction` |
| PUT | `/cars/{id}` | `UpdateCarAction` |
| DELETE | `/cars/{id}` | `DestroyCarAction` |
| GET | `/api/cars` | same Action, forced JSON |
| GET | `/api/cars/{id}` | same Action, forced JSON |

---

## Code generation with deepseek-coder MCP

When implementing a new Action, Service, Repository, or test class, delegate the
code writing to the `deepseek-coder` MCP tool (`generate_and_write`).

**Workflow:**
1. Read an existing analogous file with `read_file` to use as context.
2. Call `generate_and_write` with precise instructions that include:
    - Class name, namespace, and file path relative to the project root.
    - Constructor dependencies and their types.
    - Method signatures with parameter and return types.
    - Business rules or edge cases to handle.
    - Which patterns from this project to follow (e.g., "follow the same structure as `CarService`").
3. For complex business logic or non-trivial algorithms, pass `model: "deepseek-reasoner"`.
   For straightforward CRUD, `model: "deepseek-coder"` (default) is sufficient.

**Example instruction to deepseek-coder:**
> "Create a Laravel 12 invokable controller at `app/Modules/Brand/Interfaces/Http/Action/ListBrandAction.php`
> in namespace `App\Modules\Brand\Interfaces\Http\Action`.
> Inject `BrandService $service` via constructor.
> The `__invoke(Request $request)` method calls `$this->service->paginate(15)`,
> then returns a view `brand.index` with the result, or a JsonResource collection
> if `$request->wantsJson()`. Follow the same structure as `ListCarAction`."

Do not write new module code inline — always delegate to the MCP tool.
