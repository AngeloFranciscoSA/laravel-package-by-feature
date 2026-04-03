# Projeto Estudo — Laravel Modular (Package by Feature)

Projeto de estudo com foco na arquitetura **Package by Feature** (modular monolítico) usando Laravel 12. O domínio principal é um catálogo de carros com CRUD, paginação e respostas em HTML ou JSON via content negotiation.

> **Status:** Incompleto — em desenvolvimento para fins de aprendizado.

---

## Tecnologias

| Camada | Tecnologia |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Blade, Tailwind CSS 4, Bootstrap 5 |
| Build | Vite 6 |
| Banco de dados | SQLite (default) |
| Testes | PHPUnit 11, Mockery, ParaTest |
| JS | Axios, Swiper, SweetAlert2 |

---

## Arquitetura — Package by Feature

Cada funcionalidade vive em seu próprio módulo dentro de `app/Modules/`, contendo tudo que precisa para funcionar de forma independente.

```
app/
├── Modules/
│   ├── Car/                         # Módulo principal
│   │   ├── Interfaces/
│   │   │   ├── Http/
│   │   │   │   ├── Action/          # Controllers invocáveis (ListCar, ShowCar, UpdateCar, DestroyCar)
│   │   │   │   └── Requests/        # Form Requests com validação
│   │   │   └── Routes/
│   │   │       ├── api.php          # Rotas JSON (/api/cars)
│   │   │       └── web.php          # Rotas HTML (/cars)
│   │   ├── Models/
│   │   │   └── Car.php
│   │   ├── Providers/
│   │   │   └── CarServicesProvider.php
│   │   ├── Repositories/
│   │   │   ├── Contracts/
│   │   │   │   └── CarRepositoryInterface.php
│   │   │   └── CarRepository.php
│   │   ├── Resources/
│   │   │   └── views/               # Views do módulo (index, show)
│   │   └── Services/
│   │       └── CarService.php
│   └── Comms/
│       └── Providers/
│           └── PaginationServiceProvider.php  # Estiliza paginação com Bootstrap
└── Console/Commands/
    ├── MakeModulesCommand.php        # Artisan: scaffold de novo módulo
    └── MakeTestModule.php            # Artisan: scaffold de testes do módulo
```

### Fluxo de uma requisição

```
HTTP Request
    └── Action (Controller invocável)
            └── Service (regra de negócio)
                    └── Repository (acesso a dados)
                            └── Eloquent Model
```

---

## Funcionalidades

- Listagem de carros com paginação (carrossel Swiper)
- Visualização e edição de um carro
- Exclusão com confirmação (SweetAlert2)
- API REST com content negotiation (HTML ou JSON na mesma rota)

---

## Instalação

```bash
# 1. Clone e instale dependências
git clone <repo>
cd projeto-estudo
composer install
npm install

# 2. Configure o ambiente
cp .env.example .env
php artisan key:generate

# 3. Crie o banco e rode as migrations
php artisan migrate

# 4. Inicie os servidores (todos em paralelo)
composer run dev
```

O comando `composer run dev` sobe em paralelo: servidor Laravel, Vite, queue worker e log viewer (Pail).

---

## Testes

```bash
# Todos os testes
php artisan test

# Apenas unit
php artisan test --testsuite=Unit

# Em paralelo
php artisan test --parallel
```

**Cobertura atual:**

| Camada | Arquivo | Testes |
|---|---|---|
| Unit | `CarRepositoryTest` | list, show, insert, update, delete |
| Unit | `CarServiceTest` | list paginado, show, edit, delete |
| Feature | `ListCarActionTest` | retorna view, retorna JSON, trata exceção |
| Feature | `ShowCarActionTest` | retorna um carro |
| Feature | `EditCarActionTest` | em construção |

O banco de testes usa SQLite em memória (`:memory:`) configurado no `phpunit.xml`.

---

## Comandos Artisan customizados

```bash
# Gera scaffold de um novo módulo
php artisan make:module NomeDoModulo

# Gera scaffold de testes para um módulo
php artisan make:test-module NomeDoModulo
```

---

## Rotas disponíveis

| Método | URI | Ação |
|---|---|---|
| GET | `/cars` | Lista todos os carros (view ou JSON) |
| GET | `/cars/{id}` | Exibe um carro |
| PUT | `/cars/{id}` | Atualiza um carro |
| DELETE | `/cars/{id}` | Remove um carro |
| GET | `/api/cars` | Lista (JSON) |
| GET | `/api/cars/{id}` | Detalhe (JSON) |

---

## Estrutura de testes

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

## Objetivo de aprendizado

- Estruturar um projeto Laravel com **Package by Feature** ao invés do padrão MVC plano
- Aplicar **Repository Pattern** com contratos (interfaces)
- Separar responsabilidades com **Service Layer** e **Action Classes**
- Escrever testes unitários e de feature com mocks (Mockery)
- Usar **content negotiation** para servir HTML e JSON na mesma action
