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
        // Limpiar tablas para asegurar un estado fresco
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Categoria::truncate();
        \App\Models\Product::truncate();
        \App\Models\User::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Crear exactamente 5 categorías
        $categorias = Categoria::factory(5)->create();

        // Crear 5 productos para cada una de esas categorías
        $categorias->each(function ($categoria) {
            $factory = Product::factory(5);

            switch ($categoria->nom) {
                case 'Cámaras':
                    $factory = $factory->camara();
                    break;
                case 'Cerraduras':
                    $factory = $factory->cerradura();
                    break;
                case 'Sensores':
                    $factory = $factory->sensor();
                    break;
                case 'Alarmas':
                    $factory = $factory->alarma();
                    break;
                case 'Servicios':
                    $factory = $factory->servicio();
                    break;
            }

            $factory->create([
                'categoria_id' => $categoria->id
            ]);
        });

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
