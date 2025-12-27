<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('negocios', function (Blueprint $table) {
        // 1. Añadimos la nueva columna (permitimos null por ahora)
        $table->foreignId('estado_id')->nullable()->after('nombre')->constrained('estado_negocios');
    });

    // 2. Opcional: Si quieres pasar los datos de 'estado' a 'estado_id' antes de borrar
    // DB::statement("UPDATE negocios SET estado_id = 1 WHERE estado = 'pendiente'");

    Schema::table('negocios', function (Blueprint $table) {
        // 3. Borramos el campo de texto viejo
        $table->dropColumn('estado');
    });
}

public function down()
{
    Schema::table('negocios', function (Blueprint $table) {
        $table->string('estado')->default('pendiente');
        $table->dropForeign(['estado_id']);
        $table->dropColumn('estado_id');
    });
}

 
};