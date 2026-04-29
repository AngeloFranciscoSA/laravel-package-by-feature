<?php

namespace Database\Seeders;

use App\Modules\Car\Models\Car;
use App\Modules\Car\Models\Seller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AutoViaSeeder extends Seeder
{
    public function run(): void
    {
        // Clear tables in order to avoid FK issues
        DB::statement('DELETE FROM sellers');
        DB::statement('DELETE FROM cars');

        // Reset auto-increment for SQLite
        DB::statement("DELETE FROM sqlite_sequence WHERE name='sellers'");
        DB::statement("DELETE FROM sqlite_sequence WHERE name='cars'");

        // Insert sellers
        Seller::create([
            'id' => 1,
            'name' => 'Carlos Mendes',
            'city' => 'São Paulo',
            'state' => 'SP',
            'rating' => 4.8,
            'reviews' => 34,
            'since' => '2021',
            'phone' => '(11) 99234-5678',
        ]);

        Seller::create([
            'id' => 2,
            'name' => 'Ana Lima',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'rating' => 4.6,
            'reviews' => 21,
            'since' => '2022',
            'phone' => '(21) 98876-1234',
        ]);

        Seller::create([
            'id' => 3,
            'name' => 'Pedro Souza',
            'city' => 'Curitiba',
            'state' => 'PR',
            'rating' => 4.9,
            'reviews' => 47,
            'since' => '2020',
            'phone' => '(41) 99543-8765',
        ]);

        Seller::create([
            'id' => 4,
            'name' => 'Mariana Ferreira',
            'city' => 'Fortaleza',
            'state' => 'CE',
            'rating' => 4.7,
            'reviews' => 15,
            'since' => '2023',
            'phone' => '(85) 98765-4321',
        ]);

        Seller::create([
            'id' => 5,
            'name' => 'Lucas Costa',
            'city' => 'Porto Alegre',
            'state' => 'RS',
            'rating' => 4.5,
            'reviews' => 28,
            'since' => '2021',
            'phone' => '(51) 99321-6789',
        ]);

        Seller::create([
            'id' => 6,
            'name' => 'Fernanda Oliveira',
            'city' => 'São Paulo',
            'state' => 'SP',
            'rating' => 5.0,
            'reviews' => 9,
            'since' => '2023',
            'phone' => '(11) 97654-3210',
        ]);

        // Insert cars
        Car::create([
            'id' => 1,
            'brand' => 'Toyota',
            'model' => 'Corolla Cross',
            'year' => 2023,
            'price' => 148900,
            'km' => 12000,
            'fuel' => 'Hybrid',
            'transmission' => 'Automatic',
            'color' => 'White',
            'city' => 'São Paulo',
            'state' => 'SP',
            'views' => 3421,
            'featured' => true,
            'badge' => 'Featured',
            'seller_id' => 1,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 2,
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => 2022,
            'price' => 132500,
            'km' => 28000,
            'fuel' => 'Flex Fuel',
            'transmission' => 'Automatic',
            'color' => 'Black',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'views' => 2891,
            'featured' => true,
            'badge' => 'Top Viewed',
            'seller_id' => 2,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 3,
            'brand' => 'Jeep',
            'model' => 'Compass',
            'year' => 2023,
            'price' => 189000,
            'km' => 8500,
            'fuel' => 'Flex Fuel',
            'transmission' => 'Automatic',
            'color' => 'Gray',
            'city' => 'Curitiba',
            'state' => 'PR',
            'views' => 2644,
            'featured' => true,
            'badge' => 'Featured',
            'seller_id' => 3,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 4,
            'brand' => 'Volkswagen',
            'model' => 'T-Cross',
            'year' => 2022,
            'price' => 115000,
            'km' => 34000,
            'fuel' => 'Flex Fuel',
            'transmission' => 'Automatic',
            'color' => 'Blue',
            'city' => 'Belo Horizonte',
            'state' => 'MG',
            'views' => 2210,
            'featured' => false,
            'badge' => null,
            'seller_id' => 1,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 5,
            'brand' => 'Hyundai',
            'model' => 'HB20',
            'year' => 2023,
            'price' => 82000,
            'km' => 5000,
            'fuel' => 'Flex Fuel',
            'transmission' => 'Manual',
            'color' => 'Red',
            'city' => 'Fortaleza',
            'state' => 'CE',
            'views' => 1998,
            'featured' => false,
            'badge' => 'New',
            'seller_id' => 4,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 6,
            'brand' => 'Fiat',
            'model' => 'Pulse',
            'year' => 2022,
            'price' => 98500,
            'km' => 41000,
            'fuel' => 'Flex Fuel',
            'transmission' => 'Automatic',
            'color' => 'White',
            'city' => 'Brasília',
            'state' => 'DF',
            'views' => 1875,
            'featured' => false,
            'badge' => null,
            'seller_id' => 2,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 7,
            'brand' => 'Chevrolet',
            'model' => 'Onix Plus',
            'year' => 2023,
            'price' => 88000,
            'km' => 18000,
            'fuel' => 'Flex Fuel',
            'transmission' => 'Automatic',
            'color' => 'Silver',
            'city' => 'Porto Alegre',
            'state' => 'RS',
            'views' => 1654,
            'featured' => false,
            'badge' => null,
            'seller_id' => 5,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 8,
            'brand' => 'Renault',
            'model' => 'Kwid',
            'year' => 2022,
            'price' => 62000,
            'km' => 29000,
            'fuel' => 'Flex Fuel',
            'transmission' => 'Manual',
            'color' => 'Orange',
            'city' => 'Recife',
            'state' => 'PE',
            'views' => 1432,
            'featured' => false,
            'badge' => null,
            'seller_id' => 3,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 9,
            'brand' => 'Toyota',
            'model' => 'Hilux',
            'year' => 2021,
            'price' => 225000,
            'km' => 55000,
            'fuel' => 'Diesel',
            'transmission' => 'Automatic',
            'color' => 'Black',
            'city' => 'São Paulo',
            'state' => 'SP',
            'views' => 1310,
            'featured' => false,
            'badge' => null,
            'seller_id' => 1,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 10,
            'brand' => 'BMW',
            'model' => '320i',
            'year' => 2022,
            'price' => 310000,
            'km' => 22000,
            'fuel' => 'Gasoline',
            'transmission' => 'Automatic',
            'color' => 'White',
            'city' => 'São Paulo',
            'state' => 'SP',
            'views' => 1200,
            'featured' => false,
            'badge' => 'Premium',
            'seller_id' => 6,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 11,
            'brand' => 'Ford',
            'model' => 'Bronco Sport',
            'year' => 2023,
            'price' => 198000,
            'km' => 11000,
            'fuel' => 'Gasoline',
            'transmission' => 'Automatic',
            'color' => 'Green',
            'city' => 'Campinas',
            'state' => 'SP',
            'views' => 980,
            'featured' => false,
            'badge' => null,
            'seller_id' => 4,
            'image_url' => null,
        ]);

        Car::create([
            'id' => 12,
            'brand' => 'Volkswagen',
            'model' => 'Golf GTI',
            'year' => 2022,
            'price' => 245000,
            'km' => 19000,
            'fuel' => 'Gasoline',
            'transmission' => 'Automatic',
            'color' => 'Red',
            'city' => 'São Paulo',
            'state' => 'SP',
            'views' => 892,
            'featured' => false,
            'badge' => 'Premium',
            'seller_id' => 5,
            'image_url' => null,
        ]);
    }
}