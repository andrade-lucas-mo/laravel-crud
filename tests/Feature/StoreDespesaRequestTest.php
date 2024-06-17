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
    public function despesa_descricao_longa()
    {
        $data = [
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
            'descricao' => 'Teste de Despesa',
            'valor' => 100.50,
            'data' => Carbon::now()->subDay()->format('Y-m-d'),
        ];

        $response = $this->postJson('/api/despesa', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data']);
    }
}
