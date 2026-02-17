<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Product;
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
    Product::factory(20)->create();

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

    User::factory()->create([
        'name' => 'Bot n8n',
        'apellido' => 'Bot',
        'email' => 'bot@jjsecurity.com',
        'rol' => 'bot',
        'password' => bcrypt('123456')
    ]);
}
}
