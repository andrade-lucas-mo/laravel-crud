<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function realizar_login_credenciais_validas()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt($password = 'password123'),
        ]);

        $response = $this->postJson('api/login', [
            'email' => 'test@example.com',
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'token',
                ],
            ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function nao_realizar_login_credenciais_invalidas()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('api/login', [
            'email' => 'test@example.com',
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(403)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'autorizacao',
                ],
            ]);

        $this->assertGuest();
    }

    /** @test */
    public function poder_fazer_logout()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Token Revogado',
                'status' => 200,
            ]);
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }
}
