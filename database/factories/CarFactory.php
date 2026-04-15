<?php

namespace Database\Factories;

use App\Modules\Car\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;

    // Marcas com seus modelos reais
    private array $catalog = [
        'Toyota'     => ['Corolla', 'Camry', 'Hilux', 'RAV4', 'Yaris', 'SW4'],
        'Ford'       => ['Ka', 'Fiesta', 'Focus', 'EcoSport', 'Ranger', 'Bronco'],
        'Chevrolet'  => ['Onix', 'Cruze', 'Tracker', 'S10', 'Spin', 'Equinox'],
        'Honda'      => ['Civic', 'Fit', 'HR-V', 'City', 'WR-V', 'CR-V'],
        'BMW'        => ['320i', '520i', 'X1', 'X3', 'X5', 'M3'],
        'Volkswagen' => ['Gol', 'Polo', 'T-Cross', 'Virtus', 'Amarok', 'Tiguan'],
        'Hyundai'    => ['HB20', 'Creta', 'Tucson', 'ix35', 'Azera', 'Santa Fe'],
        'Jeep'       => ['Renegade', 'Compass', 'Commander', 'Wrangler', 'Gladiator'],
        'Nissan'     => ['Kicks', 'Versa', 'Frontier', 'Sentra', 'Altima'],
        'Fiat'       => ['Argo', 'Cronos', 'Pulse', 'Strada', 'Toro', 'Mobi'],
    ];

    private array $colors = [
        'Branco', 'Preto', 'Prata', 'Cinza', 'Vermelho',
        'Azul', 'Branco Pérola', 'Grafite', 'Bege', 'Verde',
    ];

    public function definition(): array
    {
        $brand  = $this->faker->randomKey($this->catalog);
        $model  = $this->faker->randomElement($this->catalog[$brand]);

        return [
            'brand'     => $brand,
            'model'     => $model,
            'year'      => $this->faker->numberBetween(2015, 2025),
            'color'     => $this->faker->randomElement($this->colors),
            'price'     => $this->faker->randomFloat(2, 30000, 350000),
            // image_url é definido pelo CarSeeder via sequence() com imagens em cache.
            // Em testes, fica null — o Vue usa o fallback do placehold.co.
            'image_url' => null,
        ];
    }
}
