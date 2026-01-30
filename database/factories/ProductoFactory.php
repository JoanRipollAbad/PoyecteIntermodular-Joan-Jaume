<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->words(3, true),
            'descripcion' => fake()->paragraph(),
            'precio' => fake()->randomFloat(2, 10, 500),
            'imagen' => 'img/marcaAgua/camaras/camaras.jpg',
            
            'categoria_id' => Categoria::factory(), 
        ];
    }
}