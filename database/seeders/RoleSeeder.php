<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Administrador Principal
        DB::table('roles')->insert([
            'nombre' => 'admin_principal',
            'descripcion' => 'Acceso total y control de moderación.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2. Reclutador
        DB::table('roles')->insert([
            'nombre' => 'reclutador',
            'descripcion' => 'Registrador de nuevos negocios para el directorio.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 3. Dueño de Negocio
        DB::table('roles')->insert([
            'nombre' => 'dueño_negocio',
            'descripcion' => 'Usuario con derecho a gestionar la información de un negocio.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 4. Colaborador
        DB::table('roles')->insert([
            'nombre' => 'colaborador',
            'descripcion' => 'Puede modificar datos de negocios, pero no borrar.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 5. Cliente Estándar (Rol por Defecto)
        DB::table('roles')->insert([
            'nombre' => 'cliente_estandar',
            'descripcion' => 'Usuario registrado para dejar reseñas y comentarios.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}