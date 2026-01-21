<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function buscar(Request $request)
    {
        // 1. Cargamos solo las relaciones que SÍ existen en tu modelo Negocio.php
        $query = Negocio::with(['contacto', 'imagenes', 'estado']);

        // 2. Filtro: ¿Qué buscas?
        if ($request->filled('buscar')) {
            $termino = $request->input('buscar');
            $query->where(function($q) use ($termino) {
                $q->where('nombre', 'LIKE', "%{$termino}%")
                  ->orWhere('categoria', 'LIKE', "%{$termino}%")
                  ->orWhere('palabras_clave', 'LIKE', "%{$termino}%");
            });
        }

        // 3. Filtro: ¿En qué ciudad?
        if ($request->filled('ciudad')) {
            $ciudad = $request->input('ciudad');
            $query->whereHas('contacto', function($q) use ($ciudad) {
                $q->where('direccion_manual', 'LIKE', "%{$ciudad}%");
            });
        }

        // 4. Seguridad: Solo negocios con estado_id = 2 (Aprobado)
        $query->where('estado_id', 2);

        // 5. Ejecutar y paginar
        $negocios = $query->orderBy('es_destacado', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(12);

        // 6. Retornar vista
        return view('front.resultados', compact('negocios'));
    }
}