<?php

namespace Tests\Modules\Auth\Feature\Actions;

use App\Models\User;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Mockery;
use Tests\TestCase;

class RegisterAuthActionTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_register_page_returns_register_component(): void
    {
        $this->get('/auth/register')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Auth/Register', false)
            );
    }

    public function test_register_redirects_to_cars_index_on_success(): void
    {
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('register')->once()->andReturn('fake-token');
        $this->instance(AuthService::class, $mock);

        $this->post('/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('cars.index'));
    }

    public function test_register_returns_json_token_on_success_when_wants_json(): void
    {
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('register')->once()->andReturn('reg-token');
        $this->instance(AuthService::class, $mock);

        $this->postJson('/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertStatus(201)->assertJson(['token' => 'reg-token']);
    }

    public function test_register_fails_validation_when_fields_are_missing(): void
    {
        $this->postJson('/auth/register', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_register_fails_validation_when_password_not_confirmed(): void
    {
        $this->postJson('/auth/register', [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ])->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    public function test_register_fails_validation_when_email_already_exists(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->postJson('/auth/register', [
            'name' => 'Jane',
            'email' => 'taken@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertStatus(422)->assertJsonValidationErrors(['email']);
    }
}
