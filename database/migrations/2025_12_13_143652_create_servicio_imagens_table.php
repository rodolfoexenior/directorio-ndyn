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
        Schema::create('servicio_imagens', function (Blueprint $table) {
            $table->id();
            
            // La ruta única del archivo en el disco (storage/app/public/servicios/...)
            $table->string('ruta_archivo'); 
            
            // Descripción o metadato opcional de la imagen
            $table->string('alt_texto')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_imagens');
    }
};