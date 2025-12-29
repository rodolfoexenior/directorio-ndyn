<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use Illuminate\Http\Request;

class AdminNegocioController extends Controller
{
public function index()
    {
        // 1. Filtramos: Excluimos los que tienen estado_id = 3 (eliminados)
        // 2. Ordenamos por estado: 1 (pendiente) primero, luego 2 (aprobado)
        // 3. Ordenamos por fecha: El más reciente arriba (latest)
        // 4. Cargamos relaciones: 'estado' y 'user' para mostrar nombres en la vista
        $negocios = Negocio::with(['estado', 'user'])
            ->where('estado_id', '!=', 3)
            ->orderByRaw("FIELD(estado_id, 1, 2)") 
            ->latest()
            ->get();

        // Retornamos la vista que crearemos en el siguiente paso
        return view('admin.negocios.index', compact('negocios'));
    }


    public function show(Negocio $negocio)
    {
        // Forzamos la carga de relaciones. 
        // Asegúrate de que 'imagenes' sea exactamente el nombre de la función en tu modelo Negocio.
        $negocio->load([
            'estado', 
            'user', 
            'contacto', 
            'horarios', 
            'caracteristicas',             
            'productos', 
            'servicios'
        ]);

        return view('admin.negocios.show', compact('negocio'));
    }

    public function updateEstado(Request $request, Negocio $negocio)
    {
        // Validamos que el estado enviado sea uno de los permitidos (1 o 2)
        // No permitimos cambiar a 3 (eliminado) desde aquí por seguridad
        $request->validate([
            'estado_id' => 'required|in:1,2',
        ]);

        $negocio->update([
            'estado_id' => $request->estado_id
        ]);

        return back()->with('status', 'Estado del negocio actualizado correctamente.');
    }


    


}