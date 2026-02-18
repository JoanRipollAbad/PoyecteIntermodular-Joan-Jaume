<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MessageManager;
use Exception;

class ContactoController extends Controller
{
    protected $messageManager;

    /**
     * Inyectamos el servicio en el constructor.
     * Laravel se encarga de instanciar MessageManager automáticamente.
     */
    public function __construct(MessageManager $messageManager)
    {
        $this->messageManager = $messageManager;
    }

    public function index()
    {
        return view('contacto');
    }

    public function enviar(Request $request)
    {
        try {
          
            $result = $this->messageManager->saveMessage($request->all());

            // Devolvemos la respuesta JSON que genera el servicio
            return response()->json($result, $result['success'] ? 200 : 400);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }
}