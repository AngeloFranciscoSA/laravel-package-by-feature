<?php

namespace Tests\Modules\Auth\Feature\Actions;

use App\Models\User;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class LogoutAuthActionTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_logout_redirects_to_login_when_authenticated()
    {
        $user = User::factory()->create();
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('logout')->once();
        $this->instance(AuthService::class, $mock);
        $response = $this->actingAs($user)->post('/auth/logout');
        $response->assertRedirect(route('auth.login'));
    }

    public function test_logout_returns_json_message_when_wants_json()
    {
        $user = User::factory()->create();
        $mock = Mockery::mock(AuthService::class);
        $mock->shouldReceive('logout')->once();
        $this->instance(AuthService::class, $mock);
        $response = $this->actingAs($user)->postJson('/auth/logout');
        $response->assertOk()->assertJson(['message' => 'Logged out successfully.']);
    }

    public function test_logout_is_protected_by_auth_middleware_when_not_authenticated()
    {
        $response = $this->post('/auth/logout');
        $response->assertRedirect();
    }
}