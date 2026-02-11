<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders en orden
        $this->call([
            RoleSeeder::class,        // Primero crear roles y permisos
            AdminUserSeeder::class,   // Luego crear usuario admin
        ]);

        // Opcional: Crear usuarios de prueba
        // User::factory(10)->create();
    }
}
