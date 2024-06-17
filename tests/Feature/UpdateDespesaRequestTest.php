<?php

namespace Tests\Feature;

use App\Models\Despesa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateDespesaRequestTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $despesa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);
        $this->despesa = Despesa::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function despesa_descricao_longa()
    {
        $data = [
            'valor' => 100.50,
            'descricao' => 'descricao longa descricao longa descricao longa descricao longadescricao longa descricao longa descricao longadescricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longadescricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longa descricao longa',
            'data' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        $response = $this->putJson("api/despesa/{$this->despesa->id}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['descricao']);
    }


    /** @test */
    public function despesa_valor_negativo()
    {
        $data = [
            'descricao' => 'Teste de Despesa Atualizada',
            'valor' => -100,
            'data' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        $response = $this->putJson("api/despesa/{$this->despesa->id}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['valor']);
    }

    /** @test */
    public function despesa_data_antiga()
    {
        $data = [
            'descricao' => 'Teste de Despesa Atualizada',
            'valor' => 100.50,
            'data' => Carbon::now()->subDay()->format('Y-m-d'),
        ];

        $response = $this->putJson("api/despesa/{$this->despesa->id}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['data']);
    }

}
