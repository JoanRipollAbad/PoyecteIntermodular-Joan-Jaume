<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::table('users', function (Blueprint $table) {
        $table->string('apellido')->after('name')->nullable();
        $table->date('fecha_nacimiento')->after('apellido')->nullable();
        $table->string('rol')->default('usuario')->after('email'); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
