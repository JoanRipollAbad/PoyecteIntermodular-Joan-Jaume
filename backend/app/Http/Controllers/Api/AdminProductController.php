<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Categoria;
use App\Imports\ProductImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class AdminProductController extends Controller
{
    /**
     * Guardar un producto nuevo que acabo de crear a mano
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'preu' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'img' => 'nullable|string'
        ]);

        $sku = strtoupper(substr($validated['nom'], 0, 3)) . '-' . rand(1000, 9999) . '-' . time();

        $product = Product::create([
            'sku' => $sku,
            'nom' => $validated['nom'],
            'descripcio' => $validated['descripcio'],
            'preu' => $validated['preu'],
            'estoc' => 0, // Default stock
            'categoria_id' => $validated['categoria_id'],
            'img' => $validated['img'] ?? null
        ]);

        return response()->json([
            'message' => 'Producto creado con éxito',
            'product' => $product
        ], 201);
    }

    /**
     * Subir productos desde un archivo JSON o Excel
     */
    public function import(Request $request)
    {
        Log::info("IMPORT REQUEST RECEIVED. Data: " . json_encode($request->all()));
        $request->validate([
            'file' => 'required|file|mimes:json,xlsx,csv,ods|max:10240', // 10MB máximo
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        try {
            Log::info("Iniciando importación. Extensión del archivo: $extension");
            if ($extension === 'json') {
                $count = $this->importJson($file);
                return response()->json(['message' => "Se han importado $count productos desde el JSON correctamente"], 200);
            } else {
                Excel::import(new ProductImport, $file);
                return response()->json(['message' => 'La importación desde Excel/CSV se ha procesado. Revisa los logs para más detalles.'], 200);
            }
        } catch (\Exception $e) {
            Log::error('La importación de productos ha fallado: ' . $e->getMessage());
            return response()->json(['message' => 'La importación ha fallado: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Buscar un producto por su ID para mostrarlo en el formulario de edición
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    /**
     * Modificar los datos de un producto que ya existe
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'preu' => 'required|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'img' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'estoc' => 'required|integer|min:0'
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Producto actualizado con éxito',
            'product' => $product
        ]);
    }

    /**
     * Función interna que lee un archivo JSON y guarda los productos
     */
    private function importJson($file)
    {
        $content = file_get_contents($file->getRealPath());
        $data = json_decode($content, true);
        $importedCount = 0;

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error("Error de decodificación JSON: " . json_last_error_msg());
            throw new \Exception('Formato JSON inválido');
        }

        if (!is_array($data)) {
            Log::error("Los datos JSON no son un array");
            throw new \Exception('El JSON debe contener un array de productos');
        }

        Log::info("Importación JSON: encontrados " . count($data) . " elementos");

        foreach ($data as $index => $item) {
            // Comprobar que cada producto tiene lo mínimo: nombre y precio. 
            // La categoría puede ser categoria_id (int) o categoria (string).
            if (!isset($item['nom']) || !isset($item['preu'])) {
                Log::warning("Importación JSON: Saltando elemento en el índice $index porque falta el nombre o el precio.");
                continue;
            }

            // Resolver la categoría:
            // Opción 1: viene como ID numérico > usarlo directamente
            // Opción 2: viene como nombre de texto > buscar o crear esa categoría
            // Si no hay categoría de ninguna forma > saltar este producto
            $categoryId = null;
            if (isset($item['categoria_id'])) {
                $categoryId = $item['categoria_id'];
            } elseif (isset($item['categoria'])) {
                $categoryName = $item['categoria'];
                $category = Categoria::firstOrCreate(['nom' => $categoryName]);
                $categoryId = $category->id;
            }

            if (!$categoryId) {
                Log::warning("Importación JSON: Saltando elemento en el índice $index por falta de categoría.");
                continue;
            }

            $sku = $item['sku'] ?? strtoupper(substr($item['nom'], 0, 3)) . '-' . rand(1000, 9999);
            
            $preu = (float) $item['preu'];
            $estoc = (int) ($item['estoc'] ?? 0);

            // Validación de datos: El precio y el stock no pueden ser negativos
            if ($preu < 0 || $estoc < 0) {
                Log::warning("Importación JSON: Saltando elemento en el índice $index por datos inválidos (preu o estoc negativos). SKU: $sku");
                continue;
            }

            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'nom' => $item['nom'],
                    'descripcio' => $item['descripcio'] ?? '',
                    'preu' => $preu,
                    'estoc' => $estoc,
                    'categoria_id' => $categoryId,
                    'img' => $item['img'] ?? null
                ]
            );

            if ($product) {
                $importedCount++;
            }
        }
        
        Log::info("Importación JSON finalizada. Total importados: $importedCount");
        return $importedCount;
    }
}
