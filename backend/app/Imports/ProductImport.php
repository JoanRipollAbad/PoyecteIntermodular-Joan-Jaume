<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
     * Mapear cada fila del Excel a un modelo de Producto
     */
    public function model(array $row)
    {
        \Illuminate\Support\Facades\Log::info("Procesando fila de Excel: " . json_encode($row));

        // El producto necesita como mínimo un nombre y un precio
        if (!isset($row['nom']) || !isset($row['preu'])) {
            \Illuminate\Support\Facades\Log::warning("Saltando fila de Excel: Faltan campos obligatorios (nom o preu)");
            return null;
        }

        // Resolver la categoría (por ID o por nombre)
        $categoryId = null;
        if (isset($row['categoria_id'])) {
            $categoryId = $row['categoria_id'];
        } elseif (isset($row['categoria'])) {
            $categoryName = $row['categoria'];
            $category = \App\Models\Categoria::firstOrCreate(['nom' => $categoryName]);
            $categoryId = $category->id;
        }

        if (!$categoryId) {
            \Illuminate\Support\Facades\Log::warning("Saltando fila de Excel: No se ha podido encontrar o crear la categoría.");
            return null;
        }

        $sku = $row['sku'] ?? strtoupper(substr($row['nom'], 0, 3)) . '-' . rand(1000, 9999);
        $preu = (float) str_replace(',', '.', $row['preu']);
        $estoc = (int) ($row['estoc'] ?? 0);

        // Validación de datos: El precio y el stock no pueden ser negativos
        if ($preu < 0 || $estoc < 0) {
            \Illuminate\Support\Facades\Log::warning("Saltando fila de Excel: Datos inválidos (preu o estoc negativos). SKU: " . ($row['sku'] ?? 'N/A'));
            return null;
        }

        // Usamos updateOrCreate para no duplicar productos con el mismo SKU
        $product = Product::updateOrCreate(
            ['sku' => $sku],
            [
                'nom' => $row['nom'],
                'descripcio' => $row['descripcio'] ?? '',
                'preu' => $preu,
                'estoc' => $estoc,
                'categoria_id' => $categoryId,
                'img' => $row['img'] ?? null
            ]
        );

        if ($product) {
            \Illuminate\Support\Facades\Log::info("Producto guardado/actualizado desde Excel: " . $product->sku);
        }

        return $product;
    }
}
