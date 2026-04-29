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

## Artisan commands for modules

Two custom commands live in `app/Console/Commands/` and are the **primary entry points** for working with modules:

| Command | File | Purpose |
|---|---|---|
| `php artisan make:module <ModuleName>` | `MakeModulesCommand.php` | Scaffold the full directory tree for a new module |
| `php artisan make:test-module <ModuleName>` | `MakeTestModule.php` | Scaffold the test suite skeleton for an existing module |

Always use these commands — never create module directories or test skeletons by hand.

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

All new module code must be written via the `deepseek-coder` MCP. Never write
module implementation code inline.

### Session startup (required)

At the start of every coding session, call `init_project_context` with the key
reference files so DeepSeek has project context without it being repeated in
every instruction:

```
init_project_context([
  "CLAUDE.md",
  "app/Modules/Car/Services/CarService.php",
  "app/Modules/Car/Repositories/CarRepository.php"
])
```

Then register recurring patterns once:

```
register_pattern("laravel-invokable-action", "
  Invokable controller in namespace App\Modules\{Module}\Interfaces\Http\Action.
  Constructor injects {Module}Service.
  __invoke(Request $request): Response|JsonResponse
  Checks wantsJson() for content negotiation.
  Delegates all logic to the service — never touches the repository directly.
")

register_pattern("laravel-repository", "
  Implements {Module}RepositoryInterface.
  Constructor type-hints the Eloquent Model.
  All queries via Eloquent — no raw SQL.
  Returns typed values (Model, Collection, LengthAwarePaginator).
")
```

### Tool selection rules

| Situation | Tool to use |
|---|---|
| 2+ related files (same layer) | `generate_and_write_multiple` |
| 1 file, new or full rewrite | `generate_and_write` |
| Small change to existing file | `patch_file` |
| Need to inspect code before saving | `generate_code` then `write_file` |
| Never | `generate_code` + `write_file` as separate steps for the same file |

### Grouping strategy — always group by architectural layer

Never generate one file per API call. Group files by layer to minimise calls:

```
Call 1: Migration(s)
Call 2: Model + RepositoryInterface + Repository
Call 3: Service + ServiceProvider
Call 4: All Actions for the module
Call 5: FormRequests + route files
```

Target: 5 calls or fewer per module, regardless of how many files.

### Context rules

- Call `read_file` for a reference file **at most once per session**.
- After reading, pass the content via the `context` parameter in subsequent
  calls — never call `read_file` again for the same file.
- Use `shared_context` in `generate_and_write_multiple` for content that
  applies to all files in the batch (e.g. an interface all repositories must
  implement).

### Model selection

| Task | Model |
|---|---|
| CRUD, boilerplate, standard patterns | `deepseek-v4-flash` (default) |
| Complex business logic, algorithms | `deepseek-v4-pro` |

### Context management

Before any task that will generate 5+ files:
- Run `/compact` if the conversation already has accumulated history (> 20k tokens).
- Run `/clear` if the new task is completely independent of the current conversation.

### End of session

Always call `usage_summary` at the end of a session to log token consumption
and confirm estimated cost.

### Example — implementing a new Brand module

```
# 1. Already done at session start: init_project_context + register_pattern

# 2. Scaffold directory tree
php artisan make:module Brand

# 3. Generate all backend files in 3 calls

generate_and_write_multiple(files=[
  { "file_path": "app/Modules/Brand/Models/Brand.php",
    "instructions": "Eloquent model. fillable: name, slug. HasMany Car." },
  { "file_path": "app/Modules/Brand/Repositories/Contracts/BrandRepositoryInterface.php",
    "instructions": "Interface: all(Collection), find(int): ?Brand, paginate(int): LengthAwarePaginator" },
  { "file_path": "app/Modules/Brand/Repositories/BrandRepository.php",
    "instructions": "Implements BrandRepositoryInterface. Follow laravel-repository pattern." },
], shared_context="<Brand module, same conventions as Car module>", pattern="laravel-repository")

generate_and_write(
  file_path="app/Modules/Brand/Services/BrandService.php",
  instructions="Inject BrandRepositoryInterface. Methods: all(), paginate(15), find(int).",
)

generate_and_write_multiple(files=[
  { "file_path": "app/Modules/Brand/Interfaces/Http/Action/ListBrandAction.php",
    "instructions": "paginate(15), returns brand.index view or JsonResource. Follow laravel-invokable-action pattern." },
  { "file_path": "app/Modules/Brand/Interfaces/Http/Action/ShowBrandAction.php",
    "instructions": "find(id), 404 if null, returns brand.show or JsonResource." },
  { "file_path": "app/Modules/Brand/Interfaces/Routes/web.php",
    "instructions": "GET /brands -> ListBrandAction, GET /brands/{id} -> ShowBrandAction." },
])
```
