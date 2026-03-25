<?php

namespace Tests\Feature;

use App\Models\Todos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// tests/Feature/TodoFilterTest.php
// Classe de test pour vérifier le bon fonctionnement du filtre de la liste des todos
// Issue#2 : filtre de la liste des todos (toutes, en cours, terminées)

class TodoFilterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Crée un utilisateur authentifié avec des todos de test.
     * 2 en cours, 2 terminées = 4 au total
     */
    private function setupUser(): User
    {
        $user = User::factory()->create();

        // 2 tâches en cours
        Todos::factory()->count(2)->create([
            'user_id' => $user->id,
            'termine' => 0,
        ]);

        // 2 tâches terminées
        Todos::factory()->count(2)->create([
            'user_id' => $user->id,
            'termine' => 1,
        ]);

        return $user;
    }

    public function test_filtre_en_cours()
    {
        $user = $this->setupUser();

        $response = $this->actingAs($user)
            ->get(route('todo.liste', 'en_cours'));

        $response->assertStatus(200);
        $response->assertViewHas(
            'todos',
            fn ($todos) => $todos->count() === 2
        );
    }

    public function test_filtre_terminees()
    {
        $user = $this->setupUser();

        $response = $this->actingAs($user)
            ->get(route('todo.liste', 'terminees'));

        $response->assertStatus(200);
        $response->assertViewHas(
            'todos',
            fn ($todos) => $todos->count() === 2
        );
    }

    public function test_filtre_toutes()
    {
        $user = $this->setupUser();

        $response = $this->actingAs($user)
            ->get(route('todo.liste', 'toutes'));

        $response->assertStatus(200);
        $response->assertViewHas(
            'todos',
            fn ($todos) => $todos->count() === 4
        );
    }

    public function test_filtre_par_defaut_affiche_toutes()
    {
        $user = $this->setupUser();

        // Sans paramètre de filtre
        $response = $this->actingAs($user)
            ->get(route('todo.liste'));

        $response->assertStatus(200);
        $response->assertViewHas(
            'todos',
            fn ($todos) => $todos->count() === 4
        );
    }

    public function test_isolation_utilisateurs()
    {
        $user1 = $this->setupUser(); // 4 todos
        $user2 = $this->setupUser(); // 4 todos (autres)

        // user1 ne doit voir QUE ses todos
        $response = $this->actingAs($user1)
            ->get(route('todo.liste', 'toutes'));

        $response->assertViewHas(
            'todos',
            fn ($todos) => $todos->count() === 4
        );
    }

    public function test_filtre_invalide_retourne_404()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/todos/invalide');

        $response->assertStatus(404);
    }

    public function test_non_authentifie_redirige()
    {
        $response = $this->get(route('todo.liste'));

        $response->assertRedirect('/login');
    }
}
