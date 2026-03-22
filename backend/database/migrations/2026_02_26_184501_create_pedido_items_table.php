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
        Schema::create('pedido_items', function (Blueprint $col) {
            $col->id();
            $col->foreignId('pedido_id')->constrained()->onDelete('cascade');
            $col->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $col->integer('quantitat');
            $col->decimal('preu', 10, 2);
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
    }
};
