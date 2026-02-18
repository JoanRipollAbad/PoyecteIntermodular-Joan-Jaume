<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes (Públiques)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Productes (públics)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Categories (públics)
Route::get('/categorias', [App\Http\Controllers\Api\CategoriaController::class, 'index']);

// Comentaris (públics: només lectura)
Route::get('/products/{product}/comments', [CommentController::class, 'index']);

/*
|--------------------------------------------------------------------------
| API Routes (Protegides - requereixen token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Comentaris (només creació/esborrat)
    Route::post('/products/{product}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});

// Rutas movidas desde web.php
Route::post('/contacto', [App\Http\Controllers\ContactoController::class, 'enviar']);
Route::post('/upload', [App\Http\Controllers\UploadController::class, 'store']);
Route::post('/checkout/procesar', function () {
    return response()->json(['message' => 'Pago procesado con éxito']);
});