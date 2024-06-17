<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_registrar_usuario()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'tesdte@teste.com.br',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('api/register', $data);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'message',
            'status',
            'data' => [
                'id',
                'name',
                'email'
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'tesdte@teste.com.br',
        ]);
    }
}
