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
        Schema::create('pedidos', function (Blueprint $col) {
            $col->id();
            $col->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $col->decimal('total', 10, 2);
            $col->string('status')->default('pendiente'); // pendiente, pagado, enviado, completado, cancelado
            $col->string('nombre');
            $col->string('email');
            $col->string('direccion');
            $col->string('ciudad');
            $col->string('cp');
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
