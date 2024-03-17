<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test_login_api_that_returns_token_after_validation
     */
    public function test_login_api_that_returns_token_after_validation(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    /**
     * test_login_api_that_returns_error_when_user_does_not_exists
     */
    public function test_login_api_that_returns_error_when_user_does_not_exists(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'jhondoe@test.it',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
    }
}
