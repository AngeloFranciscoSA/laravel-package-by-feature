<?php

namespace Database\Seeders;

use App\Modules\Car\Models\Car;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CarSeeder extends Seeder
{
    // Quantas imagens únicas baixar do loremflickr
    private const IMAGE_COUNT = 20;

    public function run(): void
    {
        $imageUrls = $this->cacheCarImages();

        // sequence() distribui os valores ciclicamente entre os registros criados.
        // Ex: 50 carros com 20 imagens → cada imagem é usada ~2-3 vezes.
        Car::factory()
            ->count(50)
            ->sequence(fn ($seq) => [
                'image_url' => $imageUrls[$seq->index % count($imageUrls)],
            ])
            ->create();
    }

    /**
     * Baixa imagens de carros do loremflickr e armazena localmente.
     * Se a imagem já existe no storage, reutiliza sem re-baixar.
     *
     * @return array<string> lista de URLs locais para usar nos registros
     */
    private function cacheCarImages(): array
    {
        Storage::disk('public')->makeDirectory('cars');

        $urls = [];

        for ($i = 1; $i <= self::IMAGE_COUNT; $i++) {
            $filename = "cars/car-{$i}.jpg";

            // Só baixa se ainda não estiver em cache
            if (!Storage::disk('public')->exists($filename)) {
                $this->command->getOutput()->write("  Baixando imagem {$i}/" . self::IMAGE_COUNT . "...\r");

                $response = Http::timeout(15)->get(
                    "https://loremflickr.com/640/360/car,automobile?lock={$i}"
                );

                if ($response->successful()) {
                    Storage::disk('public')->put($filename, $response->body());
                }
            }

            $urls[] = Storage::url($filename);
        }

        $this->command->info('  Imagens prontas (' . self::IMAGE_COUNT . ' em cache).');

        return $urls;
    }
}
