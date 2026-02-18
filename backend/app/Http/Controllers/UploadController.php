<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request) {
    if ($request->hasFile('fitxer')) {
        $path = $request->file('fitxer')->store('uploads'); // Se guarda en storage/app/uploads
        return "Archivo subido correctamente a: " . $path;
    }
    return "Error al subir.";
}
}
