<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('categoria');

        if ($request->has('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $products = $query->get();
        return response()->json($products->makeHidden(['categoria_id']));
    }

    public function show($id)
    {
        $product = Product::with(['comments.user', 'categoria'])->findOrFail($id);
        return response()->json($product->makeHidden(['categoria_id']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:products',
            'nom' => 'required|string',
            'descripcio' => 'required|string',
            'preu' => 'required|numeric|min:0',
            'estoc' => 'required|integer|min:0',
            'img' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $product = Product::create($validated);

        return response()->json(
            $product->load('categoria')->makeHidden(['categoria_id']),
            201
        );
    }

    public function topRated()
    {
        $products = Product::with('categoria')
            ->withAvg('comments', 'puntuacio')
            ->orderByDesc('comments_avg_puntuacio')
            ->take(3)
            ->get();

        return response()->json($products->makeHidden(['categoria_id']));
    }
}