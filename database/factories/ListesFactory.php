<?php

namespace Database\Factories;

use App\Models\Listes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Listes>
 */
class ListesFactory extends Factory
{
    protected $model = Listes::class;

    public function definition(): array
    {
        return [
            'titre' => fake()->words(2, true), // par ex. "Maison", "Bureau perso"
        ];
    }
}
