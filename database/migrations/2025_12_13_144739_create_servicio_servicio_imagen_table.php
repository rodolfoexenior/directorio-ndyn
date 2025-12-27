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
        Schema::create('servicio_servicio_imagen', function (Blueprint $table) {
            // Clave Foránea 1: Servicio
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            
            // Clave Foránea 2: Imagen
            // Nota: El nombre de la tabla de imágenes es servicio_imagens
            $table->foreignId('servicio_imagen_id')->constrained('servicio_imagens')->onDelete('cascade');
            
            // Definir la clave primaria compuesta (para evitar duplicados)
            $table->primary(['servicio_id', 'servicio_imagen_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_servicio_imagen');
    }
};