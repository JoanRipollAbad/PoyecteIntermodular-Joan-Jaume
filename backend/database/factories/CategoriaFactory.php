<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Categoria>
 */
class CategoriaFactory extends Factory
{

    protected static $index = 0;
    protected static $nombres = ['Cámaras', 'Cerraduras', 'Sensores', 'Alarmas', 'Servicios'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombre = self::$nombres[self::$index % count(self::$nombres)];
        self::$index++;

        return [
            'nom' => $nombre,
        ];
    }
}
