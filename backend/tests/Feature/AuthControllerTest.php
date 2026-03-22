<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Registro
    |--------------------------------------------------------------------------
    */

    #[Test]
    public function un_usuario_puede_registrarse_correctamente(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Joan',
            'apellido' => 'Ripoll',
            'email' => 'joan@example.com',
            'password' => '123456',
            'fecha_nacimiento' => '2000-01-15',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token',
                'user' => ['id', 'name', 'email', 'rol'],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'joan@example.com',
            'name' => 'Joan',
            'rol' => 'usuario',
        ]);
    }

    #[Test]
    public function el_registro_falla_sin_campos_obligatorios(): void
    {
        $response = $this->postJson('/api/register', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    #[Test]
    public function el_registro_falla_con_email_duplicado(): void
    {
        User::factory()->create(['email' => 'duplicado@test.com']);

        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'duplicado@test.com',
            'password' => '123456',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function el_registro_falla_con_password_corta(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => '123', // mín. 6 caracteres
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    #[Test]
    public function un_usuario_puede_hacer_login(): void
    {
        $user = User::factory()->create([
            'email' => 'login@test.com',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@test.com',
            'password' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email', 'rol'],
            ]);
    }

    #[Test]
    public function el_login_falla_con_credenciales_incorrectas(): void
    {
        User::factory()->create([
            'email' => 'login@test.com',
            'password' => bcrypt('123456'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'login@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Credenciales incorrectas']);
    }

    #[Test]
    public function el_login_falla_sin_campos_obligatorios(): void
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    #[Test]
    public function un_usuario_autenticado_puede_hacer_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Sesión cerrada']);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    #[Test]
    public function el_logout_falla_sin_token(): void
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
