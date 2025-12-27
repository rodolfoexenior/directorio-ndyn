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
    Schema::table('caracteristicas', function (Blueprint $table) {
        // Agregamos la columna 'descripcion', puede ser NULL (nullable) ya que no es requerida en el formulario, 
        // pero la definimos como string con un límite razonable.
        $table->string('descripcion', 500)->nullable()->after('nombre');
    });
}

    /**
     * Reverse the migrations.
     */
public function down(): void
{
    Schema::table('caracteristicas', function (Blueprint $table) {
        // Para revertir la migración, eliminamos la columna.
        $table->dropColumn('descripcion');
    });
}
};