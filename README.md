# Study Project — Laravel Modular (Package by Feature)

A study project focused on the **Package by Feature** architecture (modular monolith) using Laravel 12. The main domain is a car catalog with CRUD, pagination, and HTML or JSON responses via content negotiation.

> **Status:** Actively in development.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Vue 3, Tailwind CSS 4 |
| Build | Vite 6 |
| Database | SQLite (default) |
| Testing | PHPUnit 11, Mockery, ParaTest |
| JS | Vue Router, Pinia, VueUse, Axios, SweetAlert2 |

---

## Architecture — Package by Feature

Each feature lives in its own module under `app/Modules/`, containing everything it needs to work independently.

```
app/
├── Modules/
│   ├── Car/                         # Main module
│   │   ├── Interfaces/
│   │   │   ├── Http/
│   │   │   │   ├── Action/          # Invokable controllers (ListCar, ShowCar, UpdateCar, DestroyCar)
│   │   │   │   └── Requests/        # Form Requests with validation
│   │   │   └── Routes/
│   │   │       ├── api.php          # JSON routes (/api/cars)
│   │   │       └── web.php          # HTML routes (/cars)
│   │   ├── Models/
│   │   │   └── Car.php
│   │   ├── Providers/
│   │   │   └── CarServicesProvider.php
│   │   ├── Repositories/
│   │   │   ├── Contracts/
│   │   │   │   └── CarRepositoryInterface.php
│   │   │   └── CarRepository.php
│   │   ├── Resources/
│   │   │   └── views/               # Module views (index, show)
│   │   └── Services/
│   │       └── CarService.php
│   └── Comms/
│       └── Providers/
│           └── PaginationServiceProvider.php  # Pagination styling
└── Console/Commands/
    ├── MakeModulesCommand.php        # Artisan: scaffold a new module
    └── MakeTestModule.php            # Artisan: scaffold tests for a module
```

### Request flow

```
HTTP Request
    └── Action (invokable controller)
            └── Service (business logic)
                    └── Repository (data access)
                            └── Eloquent Model
```

---

## Features

- Car listing with pagination
- View and edit a single car
- Delete with confirmation dialog (SweetAlert2)
- REST API with content negotiation (HTML or JSON from the same route)
- SPA frontend with Vue 3 + Vue Router
- Global state management with Pinia

---

## Installation

```bash
# 1. Clone and install dependencies
git clone <repo>
cd laravel-package-by-feature
composer install
npm install

# 2. Set up environment
cp .env.example .env
php artisan key:generate

# 3. Create the database and run migrations
php artisan migrate

# 4. Start all servers in parallel
composer run dev
```

The `composer run dev` command starts concurrently: Laravel server, Vite, queue worker, and log viewer (Pail).

### Frontend structure

```
resources/js/
├── App.vue          # Root component
├── app.js           # Entry point (Vue + Pinia + Router)
├── components/      # Reusable components
├── pages/           # Route-level page components
├── router/          # Vue Router configuration
└── stores/          # Pinia stores
```

---

## Testing

```bash
# All tests
php artisan test

# Unit only
php artisan test --testsuite=Unit

# In parallel
php artisan test --parallel
```

**Current coverage:**

| Layer | File | Tests |
|---|---|---|
| Unit | `CarRepositoryTest` | list, show, insert, update, delete |
| Unit | `CarServiceTest` | paginated list, show, edit, delete |
| Feature | `ListCarActionTest` | returns view, returns JSON, handles exception |
| Feature | `ShowCarActionTest` | returns one car |
| Feature | `EditCarActionTest` | in progress |

The test database uses an in-memory SQLite (`:memory:`) configured in `phpunit.xml`.

---

## Custom Artisan Commands

```bash
# Scaffold a new module
php artisan make:module ModuleName

# Scaffold tests for a module
php artisan make:test-module ModuleName
```

---

## Available Routes

| Method | URI | Action |
|---|---|---|
| GET | `/cars` | List all cars (view or JSON) |
| GET | `/cars/{id}` | Show a car |
| PUT | `/cars/{id}` | Update a car |
| DELETE | `/cars/{id}` | Delete a car |
| GET | `/api/cars` | List (JSON) |
| GET | `/api/cars/{id}` | Detail (JSON) |

---

## Test Structure

```
tests/
└── Modules/
    └── Car/
        ├── Feature/
        │   └── Actions/
        │       ├── ListCarActionTest.php
        │       ├── ShowCarActionTest.php
        │       └── EditCarActionTest.php
        └── Unit/
            ├── Repositories/
            │   └── CarRepositoryTest.php
            └── Services/
                └── CarServiceTest.php
```

---

## Learning Goals

- Structure a Laravel project with **Package by Feature** instead of flat MVC
- Apply the **Repository Pattern** with contracts (interfaces)
- Separate concerns with **Service Layer** and **Action Classes**
- Write unit and feature tests with mocks (Mockery)
- Use **content negotiation** to serve HTML and JSON from the same action
