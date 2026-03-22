<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    private function crearProductoConCategoria(): Product
    {
        $categoria = Categoria::factory()->create();
        return Product::factory()->create(['categoria_id' => $categoria->id]);
    }

    /*
    |--------------------------------------------------------------------------
    | Listado público de comentarios
    |--------------------------------------------------------------------------
    */

    #[Test]
    public function se_pueden_listar_comentarios_de_un_producto(): void
    {
        $product = $this->crearProductoConCategoria();
        $user = User::factory()->create();

        Comment::factory(3)->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/products/{$product->id}/comments");

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => ['id', 'text', 'puntuacio', 'user' => ['id', 'name']],
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Crear comentario (requiere autenticación)
    |--------------------------------------------------------------------------
    */

    #[Test]
    public function un_usuario_autenticado_puede_crear_un_comentario(): void
    {
        $product = $this->crearProductoConCategoria();
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson("/api/products/{$product->id}/comments", [
            'text' => 'Producto excelente, funciona muy bien.',
            'puntuacio' => 5,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'text' => 'Producto excelente, funciona muy bien.',
                'puntuacio' => 5,
            ]);

        $this->assertDatabaseHas('comments', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'text' => 'Producto excelente, funciona muy bien.',
        ]);
    }

    #[Test]
    public function no_se_puede_crear_comentario_sin_autenticacion(): void
    {
        $product = $this->crearProductoConCategoria();

        $response = $this->postJson("/api/products/{$product->id}/comments", [
            'text' => 'Comentario sin login',
            'puntuacio' => 3,
        ]);

        $response->assertStatus(401);
    }

    #[Test]
    public function la_creacion_falla_sin_texto(): void
    {
        $product = $this->crearProductoConCategoria();
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson("/api/products/{$product->id}/comments", [
            'puntuacio' => 4,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['text']);
    }

    #[Test]
    public function la_puntuacion_debe_estar_entre_1_y_5(): void
    {
        $product = $this->crearProductoConCategoria();
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        // Puntuación demasiado alta
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson("/api/products/{$product->id}/comments", [
            'text' => 'Test',
            'puntuacio' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['puntuacio']);

        // Puntuación demasiado baja
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson("/api/products/{$product->id}/comments", [
            'text' => 'Test',
            'puntuacio' => 0,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['puntuacio']);
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar comentario (solo propietario)
    |--------------------------------------------------------------------------
    */

    #[Test]
    public function el_propietario_puede_eliminar_su_comentario(): void
    {
        $product = $this->crearProductoConCategoria();
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $comment = Comment::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Comentari eliminat']);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    #[Test]
    public function otro_usuario_no_puede_eliminar_comentario_ajeno(): void
    {
        $product = $this->crearProductoConCategoria();
        $owner = User::factory()->create();
        $otroUsuario = User::factory()->create();
        $tokenOtro = $otroUsuario->createToken('api-token')->plainTextToken;

        $comment = Comment::factory()->create([
            'product_id' => $product->id,
            'user_id' => $owner->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $tokenOtro",
        ])->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(403)
            ->assertJson(['error' => 'No autoritzat']);

        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }

    #[Test]
    public function no_se_puede_eliminar_comentario_sin_autenticacion(): void
    {
        $product = $this->crearProductoConCategoria();
        $user = User::factory()->create();

        $comment = Comment::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $response = $this->deleteJson("/api/comments/{$comment->id}");

        $response->assertStatus(401);
    }
}
