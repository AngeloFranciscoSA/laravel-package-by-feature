<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeTestModule extends Command
{
    protected $signature = 'make:test-module {name}';
    protected $description = 'Gera estrutura de testes para um módulo específico';

    public function handle(): void
    {
        $name = Str::studly($this->argument('name'));
        $basePath = base_path("tests/Modules/{$name}");

        $structure = [
            "Unit/Services/{$name}ServiceTest.php" => $this->serviceTestStub($name),
            "Unit/Repositories/{$name}RepositoryTest.php" => $this->repositoryTestStub($name),
            "Feature/Actions/List{$name}ActionTest.php" => $this->listActionTestStub($name),
        ];

        foreach ($structure as $path => $content) {
            $fullPath = "{$basePath}/{$path}";

            File::ensureDirectoryExists(dirname($fullPath));
            File::put($fullPath, $content);
            $this->info("Criado: tests/Modules/{$name}/{$path}");
        }

        $this->info("Estrutura de testes criada com sucesso para o módulo {$name}.");
    }
    private function serviceTestStub($name): string
    {
        return <<<PHP
            <?php

            namespace Tests\Modules\\{$name}\Unit\Services;

            use Tests\TestCase;
            use App\Modules\\{$name}\Services\\{$name}Service;
            use Illuminate\Foundation\Testing\RefreshDatabase;

            class {$name}ServiceTest extends TestCase
            {
                use RefreshDatabase;

                public function test_exemplo_de_servico()
                {
                    \$service = new {$name}Service();

                    \$this->assertTrue(true);
                }
            }
            PHP;
    }

    private function repositoryTestStub($name): string
    {
        return <<<PHP
            <?php

            namespace Tests\Modules\\{$name}\Unit\Repositories;

            use Tests\TestCase;
            use App\Modules\\{$name}\Models\\{$name};
            use App\Modules\\{$name}\Repositories\\{$name}Repository;
            use Illuminate\Foundation\Testing\RefreshDatabase;

            class {$name}RepositoryTest extends TestCase
            {
                use RefreshDatabase;

                public function test_exemplo_de_repositorio()
                {
                    \$repository = new {$name}Repository();

                    \$this->assertTrue(true);
                }
            }
            PHP;
    }

    private function listActionTestStub($name): string
    {
        $name_lower = strtolower($name);

        return <<<PHP
        <?php

        namespace Tests\Modules\\{$name}\Feature\Actions;

        use Tests\TestCase;
        use Illuminate\Foundation\Testing\RefreshDatabase;

        class List{$name}ActionTest extends TestCase
        {
            use RefreshDatabase;

            public function test_lista_{$name_lower}_retorna_sucesso()
            {
                \$response = \$this->getJson('/{$this->kebab($name)}');

                \$response->assertStatus(200);
            }
        }
        PHP;
    }

    private function kebab(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $string));
    }

}
