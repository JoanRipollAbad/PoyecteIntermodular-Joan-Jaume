<?php

use App\Http\Controllers\ContactoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});

Route::get('/login', function () {
    return view('login'); // se rompe la vista (footer)
});

Route::get('/registro', function () {
    return view('registro'); // se rompe la vista
});

// Ruta para ver la página
Route::get('/contacto', [ContactoController::class, 'index']); // se rompe la vista

// Ruta para recibir el formulario (POST)
Route::post('/contacto', [ContactoController::class, 'enviar']); // se rompe la vista

Route::get('/product', function () {
    return view('product'); // se rompe la vista (footer)
});

Route::get('/upload', function () { 
    return view('upload'); // no tiene vista
 });

Route::post('/upload', [App\Http\Controllers\UploadController::class, 'store']);