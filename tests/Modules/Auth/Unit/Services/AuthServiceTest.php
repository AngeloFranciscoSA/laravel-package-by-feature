<?php

namespace Tests\Modules\Auth\Unit\Services;

use App\Models\User;
use App\Modules\Auth\Repositories\Contracts\AuthRepositoryInterface;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;
use stdClass;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_login_returns_null_when_user_not_found()
    {
        $repo = Mockery::mock(AuthRepositoryInterface::class);
        $repo->shouldReceive('findByEmail')->with('a@b.com')->andReturn(null);
        $service = new AuthService($repo);
        $result = $service->login('a@b.com', 'pass');
        $this->assertNull($result);
    }

    public function test_login_returns_null_when_password_is_wrong()
    {
        $user = User::factory()->create(['password' => Hash::make('correct')]);
        $repo = Mockery::mock(AuthRepositoryInterface::class);
        $repo->shouldReceive('findByEmail')->andReturn($user);
        $service = new AuthService($repo);
        $result = $service->login($user->email, 'wrong_password');
        $this->assertNull($result);
    }

    public function test_login_calls_auth_login_and_returns_token_on_valid_credentials()
    {
        $user = User::factory()->create(['password' => Hash::make('secret123')]);
        $mockUser = Mockery::mock($user)->makePartial();
        $mockToken = new stdClass();
        $mockToken->plainTextToken = 'fake-token';
        $mockUser->shouldReceive('createToken')->with('auth_token')->andReturn($mockToken);
        $repo = Mockery::mock(AuthRepositoryInterface::class);
        $repo->shouldReceive('findByEmail')->andReturn($mockUser);
        $service = new AuthService($repo);
        $result = $service->login($user->email, 'secret123');
        $this->assertEquals('fake-token', $result);
        $this->assertTrue(Auth::check());
    }

    public function test_register_creates_user_and_returns_token()
    {
        $user = User::factory()->make();
        $mockToken = new stdClass();
        $mockToken->plainTextToken = 'reg-token';
        $mockUser = Mockery::mock($user)->makePartial();
        $mockUser->shouldReceive('createToken')->with('auth_token')->andReturn($mockToken);
        $repo = Mockery::mock(AuthRepositoryInterface::class);
        $repo->shouldReceive('create')->once()->andReturn($mockUser);
        $service = new AuthService($repo);
        $result = $service->register(['name' => 'Jane', 'email' => 'jane@example.com', 'password' => 'password123']);
        $this->assertEquals('reg-token', $result);
    }

    public function test_logout_logs_out_authenticated_user()
    {
        $user = User::factory()->create();
        Auth::login($user);
        $this->assertTrue(Auth::check());
        $repo = Mockery::mock(AuthRepositoryInterface::class);
        $service = new AuthService($repo);
        $service->logout();
        $this->assertFalse(Auth::check());
    }
}