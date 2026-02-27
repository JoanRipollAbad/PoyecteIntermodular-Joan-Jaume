<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Incidencia;
use Illuminate\Support\Facades\Validator;

class IncidenciaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $incidencia = Incidencia::create([
            'user_id' => auth('sanctum')->id(), // Opcional si el usuario está logeado
            'nombre' => $request->nombre,
            'email' => $request->email,
            'asunto' => $request->asunto,
            'mensaje' => $request->mensaje,
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Incidencia enviada correctamente. Gracias por contactarnos.',
            'data' => $incidencia
        ], 201);
    }
}
