# Creating a New Module

## Using the Artisan Command

The project includes a custom command that scaffolds the base structure of a new module:

```bash
php artisan make:modules Invoice
```

This creates:
```
app/Modules/Invoice/
├── Interfaces/
│   ├── Http/
│   │   ├── Action/InvoiceAction.php
│   │   └── Requests/InvoiceRequests.php
│   └── Routes/
│       └── api.php
├── Providers/
│   └── InvoiceServicesProvider.php
├── Repositories/
│   ├── Contracts/InvoiceRepositoryInterface.php
│   └── InvoiceRepository.php
└── Services/
    └── InvoiceService.php
```

---

## What to do after scaffolding

### 1. Register the Service Provider

Add the new provider to `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Modules\Car\Providers\CarServicesProvider::class,
    App\Modules\Invoice\Providers\InvoiceServicesProvider::class, // add this
];
```

### 2. Complete the Service Provider

Update the generated provider to register routes and bind the repository:

```php
class InvoiceServicesProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            InvoiceRepositoryInterface::class,
            InvoiceRepository::class
        );
    }

    public function boot(): void
    {
        Route::prefix('')->group(__DIR__ . '/../Interfaces/Routes/web.php');
        Route::prefix('api')->group(__DIR__ . '/../Interfaces/Routes/api.php');
    }
}
```

### 3. Create the Model and Migration

```bash
php artisan make:model Invoice -m
```

Move the model to `app/Modules/Invoice/Models/Invoice.php` and update its namespace:

```php
namespace App\Modules\Invoice\Models;
```

### 4. Add the Actions

Rename or create invokable Action classes. One class per HTTP operation:

```
Action/
├── ListInvoiceAction.php
├── ShowInvoiceAction.php
├── CreateInvoiceAction.php
├── UpdateInvoiceAction.php
└── DestroyInvoiceAction.php
```

### 5. Add Routes

```php
// Interfaces/Routes/web.php
Route::get('', ListInvoiceAction::class)->name('invoices.index');
Route::get('/{id}', ShowInvoiceAction::class)->name('invoices.show');
Route::post('/', CreateInvoiceAction::class)->name('invoices.store');
Route::put('/{id}', UpdateInvoiceAction::class)->name('invoices.update');
Route::delete('/{id}', DestroyInvoiceAction::class)->name('invoices.destroy');
```

### 6. Create the Vue Pages

```
Resources/Pages/
├── Index.vue
├── Show.vue
└── Create.vue
```

Inertia will resolve `Invoice/Index` to `app/Modules/Invoice/Resources/Pages/Index.vue` automatically via the custom resolver in `resources/js/app.js`.

---

## Module Structure Reference

```
app/Modules/{Name}/
├── Interfaces/
│   ├── Http/
│   │   ├── Action/            # One invokable class per operation
│   │   └── Requests/          # One FormRequest per operation
│   └── Routes/
│       ├── web.php            # Inertia routes
│       └── api.php            # JSON-only routes
├── Models/
│   └── {Name}.php
├── Providers/
│   └── {Name}ServicesProvider.php
├── Repositories/
│   ├── Contracts/
│   │   └── {Name}RepositoryInterface.php
│   └── {Name}Repository.php
├── Resources/
│   └── Pages/                 # Vue pages (owned by this module)
│       └── Index.vue
└── Services/
    └── {Name}Service.php
```
