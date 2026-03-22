<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\IncidenciaController;
use App\Http\Controllers\Auth\GoogleController;

/* |-------------------------------------------------------------------------- | Rutas API Públicas |-------------------------------------------------------------------------- */
Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login']);

Route::get('/oauth/google/redirect', [GoogleController::class, 'redirect']);
Route::get('/oauth/google/callback', [GoogleController::class, 'callback']);

// Productos (públicos)
Route::get('/products/top-rated', [ProductController::class , 'topRated']);
Route::get('/products', [ProductController::class , 'index']);
Route::get('/products/{id}', [ProductController::class , 'show']);

// Categorías (públicas)
Route::get('/categorias', [App\Http\Controllers\Api\CategoriaController::class , 'index']);

// Comentarios (públicos: solo lectura)
Route::get('/products/{product}/comments', [CommentController::class , 'index']);

// Pedidos (público para checkout)
Route::post('/pedidos', [PedidoController::class , 'store']);

// Incidencias (público)
Route::post('/incidencias', [IncidenciaController::class , 'store']);

/* |-------------------------------------------------------------------------- | Rutas API Protegidas (requieren token) |-------------------------------------------------------------------------- */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class , 'logout']);
    Route::get('/me', [AuthController::class , 'me']);
    Route::put('/profile', [AuthController::class , 'updateProfile']);
    Route::get('/my-pedidos', [PedidoController::class , 'myOrders']);
    Route::post('/pedidos/auth', [PedidoController::class , 'store']);

    // Comentarios (solo creación y borrado)
    Route::post('/products/{product}/comments', [CommentController::class , 'store']);
    Route::put('/comments/{id}', [CommentController::class , 'update']);
    Route::delete('/comments/{id}', [CommentController::class , 'destroy']);

    // Rutas de Administrador
    Route::middleware('admin')->group(function () {
            Route::get('/admin/products/{id}', [\App\Http\Controllers\Api\AdminProductController::class , 'show']);
            Route::post('/admin/products', [\App\Http\Controllers\Api\AdminProductController::class , 'store']);
            Route::put('/admin/products/{id}', [\App\Http\Controllers\Api\AdminProductController::class , 'update']);
            Route::post('/admin/products/import', [\App\Http\Controllers\Api\AdminProductController::class , 'import']);

            // Gestión de Pedidos
            Route::get('/admin/pedidos', [PedidoController::class , 'index']);
        }
        );
    });

// Rutas de utilidad (contacto, subidas, etc)
Route::post('/contacto', [App\Http\Controllers\ContactoController::class , 'enviar']);
Route::post('/upload', [App\Http\Controllers\UploadController::class , 'store']);
Route::post('/checkout/procesar', function () {
    return response()->json(['message' => 'Pago procesado con éxito']);
});