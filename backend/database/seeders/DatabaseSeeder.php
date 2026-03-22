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
            for ($i = 1; $i <= 5; $i++) {
                $factory = Product::factory();

                switch ($categoria->nom) {
                    case 'Cámaras':
                        $factory = $factory->camara()->state(['img' => 'img/camaras/camara' . $i . '.jpg']);
                        break;
                    case 'Cerraduras':
                        $factory = $factory->cerradura()->state(['img' => 'img/cerraduras/cerradura' . $i . '.jpg']);
                        break;
                    case 'Sensores':
                        $factory = $factory->sensor()->state(['img' => 'img/sensores/sensor' . $i . '.jpg']);
                        break;
                    case 'Alarmas':
                        $factory = $factory->alarma()->state(['img' => 'img/alarmas/alarma' . $i . '.jpg']);
                        break;
                    case 'Servicios':
                        $factory = $factory->servicio()->state(['img' => 'img/servicios/servicio' . $i . '.jpg']);
                        break;
                }

                $factory->create([
                    'categoria_id' => $categoria->id
                ]);
            }
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
