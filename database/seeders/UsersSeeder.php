<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Exemple d'insertion d'un utilisateur
        DB::table('users')->updateOrInsert(
            ['email' => 'john.doe@example.com'],
            [
                'name' => 'John Doe',
                'email_verified_at' => now(),
                'password' => Hash::make('mdp'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Ajoutez d'autres utilisateurs au besoin
        DB::table('users')->updateOrInsert(
            ['email' => 'jane.smith@example.com'],
            [
                'name' => 'Jane Smith',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Vous pouvez continuer à ajouter d'autres utilisateurs
    }
}
