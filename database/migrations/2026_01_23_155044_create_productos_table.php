<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('productos', function (Blueprint $table) {
        $table->id(); // ID autoincremental
        $table->string('nombre'); // Nombre del producto
        $table->text('descripcion'); // Descripción larga
        $table->decimal('precio', 8, 2); // Precio con 2 decimales
        $table->string('imagen')->nullable(); // Ruta de la imagen
        $table->timestamps(); // Crea las columnas created_at y updated_at automáticamente
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
