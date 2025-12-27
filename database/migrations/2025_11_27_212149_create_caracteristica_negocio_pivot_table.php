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
    Schema::create('caracteristica_negocio', function (Blueprint $table) {
        $table->foreignId('caracteristica_id')->constrained()->onDelete('cascade');
        $table->foreignId('negocio_id')->constrained('negocios')->onDelete('cascade');

        $table->primary(['caracteristica_id', 'negocio_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caracteristica_negocio_pivot');
    }
};
