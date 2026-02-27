<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    /**
     * List all orders (Admin only)
     */
    public function index()
    {
        return Pedido::with(['items.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Store a new order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:255',
            'cp' => 'required|string|max:20',
            'total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {
                // Get authenticated user ID if available
                $userId = auth('sanctum')->id();

                $pedido = Pedido::create([
                    'user_id' => $userId,
                    'total' => $validated['total'],
                    'nombre' => $validated['nombre'],
                    'email' => $validated['email'],
                    'direccion' => $validated['direccion'],
                    'ciudad' => $validated['ciudad'],
                    'cp' => $validated['cp'],
                    'status' => 'pagado',
                ]);

                foreach ($validated['items'] as $item) {
                    PedidoItem::create([
                        'pedido_id' => $pedido->id,
                        'product_id' => $item['product_id'],
                        'quantitat' => $item['quantity'],
                        'preu' => $item['price'],
                    ]);
                }

                return response()->json($pedido->load('items'), 201);
            });
        }
        catch (\Exception $e) {
            Log::error('Error al guardar el pedido: ' . $e->getMessage());
            return response()->json(['message' => 'Error al procesar el pedido'], 500);
        }
    }

    /**
     * Get orders for the authenticated user
     */
    public function myOrders()
    {
        $userId = auth('sanctum')->id();

        if (!$userId) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return Pedido::with(['items.product'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
