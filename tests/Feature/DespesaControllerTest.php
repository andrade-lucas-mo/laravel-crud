<?php

namespace Tests\Feature;

use App\Models\Despesa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DespesaNotification;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Carbon\Carbon;

class DespesaControllerTest extends TestCase
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
    public function pode_buscar_despesas()
    {
        Despesa::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson('api/despesa');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'status',
                    'data' => [
                        [
                            'id',
                            'descricao',
                            'valor',
                            'data',
                            'user' => [
                                'id',
                                'name',
                                'email',
                            ],
                        ],
                    ],
                ]);
    }

    /** @test */
    public function pode_criar_despesa()
    {
        Notification::fake();

        $data = [
            'user_id' => $this->user->id,
            'descricao' => 'Teste de Despesa',
            'valor' => 100.50,
            'data' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        $response = $this->postJson('api/despesa', $data);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Despesa criada',
                    'status' => 201,
                    'data' => [
                        'descricao' => 'Teste de Despesa',
                        'valor' => 100.50,
                        'data' => Carbon::now()->addDay()->format('Y-m-d'),
                        'user' => [
                            'id' => $this->user->id,
                            'name' => $this->user->name,
                            'email' => $this->user->email,
                        ],
                    ],
                ]);

        $this->assertDatabaseHas('despesas', $data);
        Notification::assertSentTo([$this->user], DespesaNotification::class);
    }

    /** @test */
    public function pode_exibir_uma_despesa()
    {
        $despesa = Despesa::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("api/despesa/{$despesa->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'status',
                    'data' => [
                        'id',
                        'descricao',
                        'valor',
                        'data',
                        'user' => [
                            'id',
                            'name',
                            'email',
                        ],
                    ],
                ]);
    }

    /** @test */
    public function pode_atualizar_uma_despesa()
    {
        $despesa = Despesa::factory()->create(['user_id' => $this->user->id]);

        $data = [
            'descricao' => 'Despesa Atualizada',
            'valor' => 150,
            'data' => Carbon::now()->addDay()->format('Y-m-d'),
        ];

        $response = $this->putJson("api/despesa/{$despesa->id}", $data);

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Despesa atualizada',
                    'status' => 200,
                    'data' => [
                        'descricao' => 'Despesa Atualizada',
                        'valor' => 150,
                        'data' => Carbon::now()->addDay()->format('Y-m-d'),
                        'user' => [
                            'id' => $this->user->id,
                            'name' => $this->user->name,
                            'email' => $this->user->email,
                        ],
                    ],
                ]);

        $this->assertDatabaseHas('despesas', $data);
    }

    /** @test */
    public function pode_deletar_uma_despesa()
    {
        $despesa = Despesa::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("api/despesa/{$despesa->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('despesas', ['id' => $despesa->id]);
    }

}
