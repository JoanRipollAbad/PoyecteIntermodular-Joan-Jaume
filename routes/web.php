<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes - JJ-Security
|--------------------------------------------------------------------------
*/

// 1. PÁGINA DE INICIO (Main)
Route::get('/', function () {
    return view('main');
})->name('home');

// 2. AUTENTICACIÓN (Login y Registro)
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/registro', function () {
    return view('registro');
})->name('registro');

// 3. PRODUCTOS Y COMPRA
Route::get('/product', function () {
    return view('product');
})->name('product.show');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

// Ruta opcional para procesar el pago del checkout
Route::post('/checkout/procesar', function () {
    return "Pago procesado con éxito";
})->name('checkout.process');

// 4. CONTACTO (Usa ContactoController)
// Muestra el formulario
Route::get('/contacto', [ContactoController::class, 'index'])->name('contacto.index');
// Recibe los datos por AJAX/POST
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');

// 5. GESTIÓN DE ARCHIVOS (Usa UploadController)
// Muestra el formulario de subida
Route::get('/upload', [UploadController::class, 'index'])->name('upload.index');
// Procesa el archivo Excel/CSV
Route::post('/upload', [UploadController::class, 'store'])->name('upload.store');

// 6. PÁGINA INDEX (La de bienvenida que editaste en tu rama personal)
Route::get('/index-test', function () {
    return view('index');
});