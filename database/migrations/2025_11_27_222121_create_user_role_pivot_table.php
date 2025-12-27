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
    Schema::create('role_user', function (Blueprint $table) {
        // Conexión a la tabla users
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // Conexión a la nueva tabla roles
        $table->foreignId('role_id')->constrained()->onDelete('cascade');

        // La combinación de las dos llaves es la clave primaria
        $table->primary(['user_id', 'role_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_role_pivot');
    }
};
