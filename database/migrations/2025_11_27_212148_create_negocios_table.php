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
    Schema::create('negocios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Dueño o Reclutador
        $table->string('nombre');
        $table->text('descripcion');
        $table->string('categoria');
        $table->boolean('es_emprendimiento')->default(false);
        $table->string('estado')->default('pendiente'); // Moderación
        $table->boolean('es_destacado')->default(false); // Monetización
        $table->text('palabras_clave')->nullable(); // Búsqueda centralizada
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negocios');
    }
};
