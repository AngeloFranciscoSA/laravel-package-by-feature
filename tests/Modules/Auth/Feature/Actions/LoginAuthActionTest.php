<?php

namespace Tests\Modules\Auth\Feature\Actions;

use App\Modules\Auth\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Mockery;
use Tests\TestCase;

class LoginAuthActionTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_login_page_returns_login_component(): void
    {
        $this->get('/auth')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Auth/Login', false)
            );
    }

    public function test_login_redirects_to_cars_index_on_valid_credentials(): void
    {
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('login')->once()->with('user@example.com', 'password123')->andReturn('fake-token');
        $this->instance(AuthService::class, $mock);

        $this->post('/auth/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('cars.index'));
    }

    public function test_login_redirects_back_with_errors_on_invalid_credentials(): void
    {
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('login')->once()->andReturn(null);
        $this->instance(AuthService::class, $mock);

        $this->post('/auth/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ])
            ->assertRedirect()
            ->assertSessionHasErrors(['email']);
    }

    public function test_login_returns_json_token_on_valid_credentials_when_wants_json(): void
    {
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('login')->once()->andReturn('fake-token');
        $this->instance(AuthService::class, $mock);

        $this->postJson('/auth/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ])->assertOk()->assertJson(['token' => 'fake-token']);
    }

    public function test_login_returns_401_json_on_invalid_credentials_when_wants_json(): void
    {
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('login')->once()->andReturn(null);
        $this->instance(AuthService::class, $mock);

        $this->postJson('/auth/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ])->assertStatus(401)->assertJson(['message' => 'Invalid credentials.']);
    }

    public function test_login_fails_validation_when_email_is_missing(): void
    {
        $this->postJson('/auth/login', ['password' => 'password123'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
