# Seeding & Factories

## Running the Seeder

On first setup, run migrations and seed together:

```bash
php artisan storage:link   # required for car images to be publicly accessible
php artisan migrate --seed
```

To reset and reseed:

```bash
php artisan migrate:fresh --seed
```

---

## CarFactory

Located at `database/factories/CarFactory.php`.

Generates realistic car data using a curated catalog of real brands and models:

| Field       | Source                                                  |
|-------------|---------------------------------------------------------|
| `brand`     | Random key from the `$catalog` array (10 brands)        |
| `model`     | Random model belonging to the selected brand            |
| `year`      | Random integer between 2015–2025                        |
| `color`     | Random from a list of PT-BR color names                 |
| `price`     | Random float between R$ 30,000 – R$ 350,000             |
| `image_url` | Set to `null` — assigned by the seeder via `sequence()` |

**Note:** `image_url` is intentionally `null` in the factory. This means tests and manual `Car::factory()->create()` calls get `null`, and the Vue page falls back to a placeholder image. The real local URLs are only assigned when running `CarSeeder`.

---

## CarSeeder

Located at `database/seeders/CarSeeder.php`.

### Image caching flow

Before creating any records, the seeder downloads car images from [loremflickr.com](https://loremflickr.com) and stores them locally:

```
CarSeeder::run()
    └── cacheCarImages()
            ├── Creates storage/app/public/cars/ if needed
            ├── For each lock (1 to IMAGE_COUNT):
            │       - Checks if car-{n}.jpg already exists in storage
            │       - Downloads from loremflickr only if missing
            │       - Stores at storage/app/public/cars/car-{n}.jpg
            └── Returns array of local URLs (/storage/cars/car-{n}.jpg)
    └── Car::factory()->count(50)->sequence(...)
            └── Assigns local URLs cyclically across all records
```

The `lock` parameter in the loremflickr URL (`?lock=N`) ensures the same number always returns the same image. This makes the seeded data consistent across machines.

### Re-running the seeder

Images are only downloaded if they don't already exist in storage. Running `migrate:fresh --seed` a second time reuses the cached images and skips all HTTP requests.

To force re-download, delete the cached files first:

```bash
rm -rf storage/app/public/cars/
php artisan migrate:fresh --seed
```

---

## Adding Images to Manually Created Records

If you create cars outside the seeder (e.g., via a form), `image_url` will be `null` unless you set it explicitly. The Vue page handles this with a fallback:

```vue
:src="car.image_url ?? 'https://placehold.co/640x360?text=No+Image'"
```

For a production setup, consider replacing the loremflickr approach with file uploads stored via `Storage::disk('public')->put()`.
