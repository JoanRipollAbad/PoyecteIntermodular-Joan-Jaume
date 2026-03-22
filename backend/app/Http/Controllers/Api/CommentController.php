<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Llistat públic de comentaris d'un producte
     */
    public function index(Product $product)
    {
        return response()->json(
            $product->comments()->with('user:id,name')->get()
        );
    }

    /**
     * Crear comentari (requereix autenticació)
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:1000',
            'puntuacio' => 'nullable|integer|min:1|max:5',
        ]);

        $comment = $product->comments()->create([
            'user_id' => $request->user()->id, // Sanctum proporciona l'usuari autenticat
            'text' => $validated['text'],
            'puntuacio' => $validated['puntuacio'] ?? null,
        ]);

        return response()->json($comment->load('user:id,name'), 201);
    }

    /**
     * Actualitzar comentari (només propietari)
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['error' => 'No autoritzat'], 403);
        }

        $validated = $request->validate([
            'text' => 'required|string|max:1000',
            'puntuacio' => 'nullable|integer|min:1|max:5',
        ]);

        $comment->update($validated);

        return response()->json($comment->load('user:id,name'));
    }

    /**
     * Esborrar comentari (propietari o administrador)
     */
    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        // Permetre si és l'autor O si és administrador
        $isAuthor = $comment->user_id === $request->user()->id;
        $isAdmin = $request->user()->rol === 'admin';

        if (!$isAuthor && !$isAdmin) {
            return response()->json(['error' => 'No autoritzat'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Comentari eliminat'], 200);
    }
}