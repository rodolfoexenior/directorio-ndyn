<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoNegocioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
{
    \App\Models\EstadoNegocio::create(['nombre' => 'pendiente']);
    \App\Models\EstadoNegocio::create(['nombre' => 'aprobado']);
    \App\Models\EstadoNegocio::create(['nombre' => 'eliminado']);
}
}