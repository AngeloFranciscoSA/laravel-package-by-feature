<?php

namespace Tests\Modules\Auth\Unit\Repositories;

use App\Models\User;
use App\Modules\Auth\Repositories\AuthRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_find_by_email_returns_user_when_found(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $repository = new AuthRepository();
        $result = $repository->findByEmail('test@example.com');

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('test@example.com', $result->email);
    }

    public function test_find_by_email_returns_null_when_not_found(): void
    {
        $repository = new AuthRepository();
        $result = $repository->findByEmail('nonexistent@example.com');

        $this->assertNull($result);
    }

    public function test_create_persists_user_with_hashed_password(): void
    {
        $repository = new AuthRepository();
        $result = $repository->create([
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('john@example.com', $result->email);
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        $this->assertTrue(Hash::check('password123', $result->password));
    }
}