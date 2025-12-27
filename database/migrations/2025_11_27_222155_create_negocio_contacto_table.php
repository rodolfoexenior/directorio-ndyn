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
    Schema::create('negocio_contacto', function (Blueprint $table) {
        $table->id();
        $table->foreignId('negocio_id')->constrained('negocios')->onDelete('cascade');
        $table->string('direccion_manual')->nullable();
        $table->decimal('latitud', 10, 8)->nullable(); // Geolocalización
        $table->decimal('longitud', 11, 8)->nullable(); // Geolocalización
        $table->string('telefono')->nullable();
        $table->string('email_contacto')->nullable();
        $table->string('web')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negocio_contacto');
    }
};
