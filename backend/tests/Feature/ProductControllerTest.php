<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Listado de productos
    |--------------------------------------------------------------------------
    */

    #[Test]
    public function se_pueden_listar_todos_los_productos(): void
    {
        $categoria = Categoria::factory()->create(['nom' => 'Cámaras']);
        Product::factory(3)->create(['categoria_id' => $categoria->id]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'sku', 'nom', 'descripcio', 'img', 'preu', 'estoc', 'categoria'],
            ]);

        // Verificar que categoria_id está oculto
        $response->assertJsonMissing(['categoria_id']);
    }

    #[Test]
    public function los_productos_incluyen_la_categoria(): void
    {
        $categoria = Categoria::factory()->create(['nom' => 'Alarmas']);
        Product::factory()->create(['categoria_id' => $categoria->id]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonFragment(['nom' => 'Alarmas']);
    }

    #[Test]
    public function la_lista_devuelve_vacia_sin_productos(): void
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    /*
    |--------------------------------------------------------------------------
    | Detalle de producto
    |--------------------------------------------------------------------------
    */

    #[Test]
    public function se_puede_ver_el_detalle_de_un_producto(): void
    {
        $categoria = Categoria::factory()->create();
        $product = Product::factory()->create(['categoria_id' => $categoria->id]);
        $user = User::factory()->create();

        Comment::factory(2)->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id', 'sku', 'nom', 'descripcio', 'preu', 'estoc',
                'categoria' => ['id', 'nom'],
                'comments' => [
                    '*' => ['id', 'text', 'puntuacio', 'user'],
                ],
            ]);
    }

    #[Test]
    public function devuelve_404_para_producto_inexistente(): void
    {
        $response = $this->getJson('/api/products/9999');

        $response->assertStatus(404);
    }
}
