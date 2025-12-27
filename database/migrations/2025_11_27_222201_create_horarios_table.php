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
    Schema::create('horarios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('negocio_id')->constrained('negocios')->onDelete('cascade');
        $table->string('dia_semana');
        $table->time('hora_apertura')->nullable();
        $table->time('hora_cierre')->nullable();
        $table->boolean('esta_cerrado')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
