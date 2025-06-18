<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModulesCommand extends Command
{
    protected $signature = 'make:modules {name}';
    protected $description = 'Cria a estrutura de módulos com base no nome fornecido';

    public function handle(): void
    {
        $name = $this->argument('name');
        $basePath = app_path("Modules/{$name}");

        // Estrutura de diretórios e arquivos
        $structure = [
            "Interfaces/Http/Action/{$name}Action.php" => "<?php\n\nnamespace App\\Modules\\$name\\Interfaces\\Http\\Action;\n\nclass {$name}Action\n{\n    // ...\n}",
            "Interfaces/Http/Requests/{$name}Requests.php" => "<?php\n\nnamespace App\\Modules\\$name\\Interfaces\\Http\\Requests;\n\nclass {$name}Requests\n{\n    // ...\n}",
            "Interfaces/Routes/api.php" => "<?php\n\n// Rotas da API do módulo {$name}\n",
            "Providers/{$name}ServicesProvider.php" => "<?php\n\nnamespace App\\Modules\\$name\\Providers;\n\nuse Illuminate\Support\ServiceProvider;\n\nclass {$name}ServicesProvider extends ServiceProvider\n{\n    public function register()\n    {\n        // ...\n    }\n}",
            "Repositories/Contracts/{$name}RepositoryInterface.php" => "<?php\n\nnamespace App\\Modules\\$name\\Repositories\\Contracts;\n\ninterface {$name}RepositoryInterface\n{\n    // ...\n}",
            "Repositories/{$name}Repository.php" => "<?php\n\nnamespace App\\Modules\\$name\\Repositories;\n\nclass {$name}Repository\n{\n    // ...\n}",
            "Services/{$name}Service.php" => "<?php\n\nnamespace App\\Modules\\$name\\Services;\n\nclass {$name}Service\n{\n    // ...\n}",
        ];

        foreach ($structure as $relativePath => $content) {
            $fullPath = "{$basePath}/{$relativePath}";
            $directory = dirname($fullPath);

            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
                $this->info("Criado diretório: {$directory}");
            }

            if (!File::exists($fullPath)) {
                File::put($fullPath, $content);
                $this->info("Criado arquivo: {$fullPath}");
            } else {
                $this->warn("Arquivo já existe: {$fullPath}");
            }
        }

        $this->info("Módulo {$name} criado com sucesso!");
    }
}
