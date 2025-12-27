<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
/**
 * Run the database seeds.
 */
public function run(): void
{
    $categorias = [
        ['nombre' => 'Restaurante', 'activa' => true],
        ['nombre' => 'Cafetería', 'activa' => true],
        ['nombre' => 'Salón de Belleza', 'activa' => true],
        ['nombre' => 'Servicios Profesionales', 'activa' => true],
        ['nombre' => 'Tienda Minorista', 'activa' => true],
        ['nombre' => 'Hotel/Alojamiento', 'activa' => true],
    ];

    foreach ($categorias as $categoria) {
        Categoria::create($categoria);
    }
}
}
