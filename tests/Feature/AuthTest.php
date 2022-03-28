<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'test',
            'last_name' => 'user',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, auth()->user()->tokens->count());
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200);
        $this->assertEquals(0, auth()->user()->tokens->count());
    }
}
