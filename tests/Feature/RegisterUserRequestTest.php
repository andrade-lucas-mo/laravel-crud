<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterUserRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function usuario_obrigatorio_name()
    {
        $data = [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function usuario_email_valido()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('api/register', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function usuario_email_obrigatorio()
    {

        $data = [
            'name' => 'Test User',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function usuario_obrigatorio_email_unico()
    {
        User::factory()->create(['email' => 'test@example.com']);

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function usuario_senha_curta()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ];

        $response = $this->postJson('api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function usuario_senha_obrigatoria()
    {

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ];

        $response = $this->postJson('api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function usuario_obrigatorio_email_confirmando()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different-password',
        ];

        $response = $this->postJson('api/register', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
