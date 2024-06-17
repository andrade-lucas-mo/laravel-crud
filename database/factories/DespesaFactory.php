<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Despesa>
 */
class DespesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'descricao' => NULL,
            'valor' => $this->faker->numberBetween(100, 3000),
            'data' => $this->faker->randomElement([$this->faker->dateTimeBetween('now', '+5 months')])
        ];
    }
}
