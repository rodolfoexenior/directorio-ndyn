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
        Schema::table('negocios', function (Blueprint $table) {
            // Se agrega la columna con un valor por defecto de 5
            $table->unsignedSmallInteger('limite_fotos')
                  ->default(5)
                  ->after('palabras_clave') // Ubicación después de 'palabras_clave'
                  ->comment('Número máximo de fotos permitidas para el negocio.');
        });
    }

    /**
     * Reverse the migrations.
     */
public function down(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            // Esto asegura que la columna se elimine si se revierte la migración
            $table->dropColumn('limite_fotos');
        });
    }
};