<?php

namespace Database\Factories;

use App\Models\Listes;
use App\Models\Todos;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Todos>
 */
class TodosFactory extends Factory
{
    protected $model = Todos::class;

    public function definition(): array
    {
        return [
            'texte' => fake()->sentence(3),
            'termine' => false,
            'important' => false,
            'date_fin' => null,
            'listes_id' => Listes::factory(), // crée une Liste associée
            'user_id' => User::factory(),  // crée un User associé
        ];
    }
}
