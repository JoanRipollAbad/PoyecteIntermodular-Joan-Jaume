<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
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
    // Crear 5 categorías
    Categoria::factory(5)->create();

    // Crear 20 productos aleatorios repartidos en esas categorías
    Producto::factory(20)->create();

    // Crear 10 usuarios
    User::factory(10)->create();

    // Crear un usuario admin
    User::factory()->create([
        'name' => 'Admin',
        'apellido' => 'Batoi',
        'email' => 'admin@admin.com',
        'rol' => 'admin',
        'password' => bcrypt('1234')
    ]);
}
}
