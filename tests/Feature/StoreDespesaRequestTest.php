<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreDespesaRequestTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);
    }

    /** @test */
    public function despesa_obrigatorio_user_id()
    {
        $data = [
            'descricao' => 'Teste de Despesa',
            'valor' => 100.50,
            'data' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/despesa', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function despesa_user_id_invalido()
    {
        $data = [
            'user_id' => 999,
            'descricao' => 'Teste de Despesa',
            'valor' => 100.50,
            'data' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/despesa', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function despesa_descricao_longa()
    {
        $data = [
            'user_id' => $this->user->id,
            'descricao' => 'descricao longa descricao longa descricao longa descricao longadescricao longa descricao longa descricao longadescricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longadescricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longa',
            'valor' => 100.50,
            'data' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/despesa', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);
    }

    /** @test */
    public function despesa_valor_negativo()
    {
        $data = [
            'user_id' => $this->user->id,
            'descricao' => 'Teste de Despesa',
            'valor' => -100,
            'data' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/despesa', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['valor']);
    }

    /** @test */
    public function despesa_data_antiga()
    {
        $data = [
            'user_id' => $this->user->id,
            'descricao' => 'Teste de Despesa',
            'valor' => 100.50,
            'data' => Carbon::now()->subDay()->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/despesa', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data']);
    }
}
