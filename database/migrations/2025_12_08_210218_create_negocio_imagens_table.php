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
        Schema::create('negocio_imagenes', function (Blueprint $table) {
            $table->id();

            // Clave foránea al negocio (relación Muchos a Uno)
            $table->foreignId('negocio_id')->constrained('negocios')->onDelete('cascade'); 
            // Usamos onDelete('cascade') para que si se elimina el negocio, sus fotos también se eliminen automáticamente.

            $table->string('ruta_archivo'); // La ruta física en el storage
            $table->boolean('es_principal')->default(false); // Bandera para la foto de portada
            $table->unsignedSmallInteger('orden')->default(0); // Para ordenar la galería

            $table->timestamps();

            // Aseguramos que la combinación de negocio_id y orden sea única
            $table->unique(['negocio_id', 'orden']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negocio_imagenes');
    }
};