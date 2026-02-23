<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| Rutas API Públicas
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Productos (públicos)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Categorías (públicas)
Route::get('/categorias', [App\Http\Controllers\Api\CategoriaController::class, 'index']);

// Comentarios (públicos: solo lectura)
Route::get('/products/{product}/comments', [CommentController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Rutas API Protegidas (requieren token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    
    // Comentarios (solo creación y borrado)
    Route::post('/products/{product}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    
    // Rutas de Administrador
    Route::middleware('admin')->group(function () {
        Route::get('/admin/products/{id}', [\App\Http\Controllers\Api\AdminProductController::class, 'show']);
        Route::post('/admin/products', [\App\Http\Controllers\Api\AdminProductController::class, 'store']);
        Route::put('/admin/products/{id}', [\App\Http\Controllers\Api\AdminProductController::class, 'update']);
        Route::post('/admin/products/import', [\App\Http\Controllers\Api\AdminProductController::class, 'import']);
    });
});

// Rutas de utilidad (contacto, subidas, etc)
Route::post('/contacto', [App\Http\Controllers\ContactoController::class, 'enviar']);
Route::post('/upload', [App\Http\Controllers\UploadController::class, 'store']);
Route::post('/checkout/procesar', function () {
    return response()->json(['message' => 'Pago procesado con éxito']);
});