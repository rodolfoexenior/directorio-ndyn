<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Caracteristica;
use Illuminate\Support\Facades\Storage;

class CaracteristicaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
    {
        // 1. Obtiene todas las características ordenadas por ID descendente
        $caracteristicas = Caracteristica::orderBy('id', 'desc')->get();
        
        // 2. Devuelve la vista, pasando la colección de características
        return view('admin.caracteristicas.index', compact('caracteristicas'));
    }

    /**
     * Show the form for creating a new resource.
     */
public function create()
    {
        // Simplemente devuelve la vista de creación.
        return view('admin.caracteristicas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
    {
        // 1. Validación de los datos
        $request->validate([
            'nombre' => 'required|string|max:255|unique:caracteristicas,nombre',
            'descripcion' => 'nullable|string|max:500',
            'icono_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validación de archivo: 2MB máx, solo imágenes
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe una característica con este nombre.',
            'icono_file.image' => 'El archivo debe ser una imagen.',
            'icono_file.max' => 'El tamaño máximo del archivo es 2MB.',
        ]);

        $icono_path = null;

        // 2. Manejo de la subida del archivo (Icono)
        if ($request->hasFile('icono_file')) {
            // Guarda el archivo en storage/app/public/iconos
            $icono_path = $request->file('icono_file')->store('iconos', 'public');
        }

        // 3. Creación del recurso en la base de datos
        try {
            Caracteristica::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'icono' => $icono_path, // Guarda la ruta
            ]);

            // 4. Redirección con mensaje de éxito
            return redirect()->route('caracteristicas.index')
                             ->with('success', '¡La característica se ha creado exitosamente!');
                             
        } catch (\Exception $e) {
            // Manejo de error
            // Si la creación falla, eliminamos el archivo subido para no dejar residuos
            if ($icono_path) {
                Storage::disk('public')->delete($icono_path);
            }
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Ocurrió un error al intentar guardar la característica. Intenta de nuevo.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit(Caracteristica $caracteristica)
    {
        return view('admin.caracteristicas.edit', compact('caracteristica'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Caracteristica $caracteristica)
    {
        // 1. Validación: Añadir la validación del archivo.
        $request->validate([
            'nombre' => 'required|string|max:255|unique:caracteristicas,nombre,' . $caracteristica->id,
            'descripcion' => 'nullable|string|max:500',
            'icono_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe otra característica con este nombre.',
            'icono_file.image' => 'El archivo debe ser una imagen.',
            'icono_file.max' => 'El tamaño máximo del archivo es 2MB.',
        ]);

        $data = $request->only('nombre', 'descripcion');
        $old_icono = $caracteristica->icono;

        // 2. Manejo de la subida del nuevo archivo
        if ($request->hasFile('icono_file')) {
            // Guarda el nuevo archivo
            $data['icono'] = $request->file('icono_file')->store('iconos', 'public');
            
            // Elimina el icono anterior (si existe)
            if ($old_icono) {
                Storage::disk('public')->delete($old_icono);
            }
        } else {
            // Si no se sube un nuevo archivo, mantenemos el icono existente
            $data['icono'] = $old_icono;
        }

        // 3. Actualización
        try {
            $caracteristica->update($data);

            // 4. Redirección con mensaje de éxito
            return redirect()->route('caracteristicas.index')
                             ->with('success', '¡La característica "' . $caracteristica->nombre . '" se ha actualizado correctamente!');
                             
        } catch (\Exception $e) {
            // Si falla la actualización, y se subió un archivo nuevo, lo eliminamos
            if (isset($data['icono']) && $data['icono'] != $old_icono) {
                Storage::disk('public')->delete($data['icono']);
            }
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Ocurrió un error al intentar actualizar la característica. Intenta de nuevo.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
public function destroy(Caracteristica $caracteristica)
    {
        $nombre = $caracteristica->nombre; // Guarda el nombre para el mensaje de éxito
        $old_icono = $caracteristica->icono; // Guarda la ruta del icono

        try {
            // 1. ELIMINAR EL ICONO ASOCIADO DEL DISCO (si existe)
            if ($old_icono) {
                // Asume que la imagen fue guardada en el disco 'public'
                Storage::disk('public')->delete($old_icono);
            }

            // 2. ELIMINAR EL REGISTRO DE LA BASE DE DATOS
            // Laravel también maneja la eliminación de relaciones muchos-a-muchos (si existen)
            $caracteristica->delete();

            // 3. Redirección con mensaje de éxito
            return redirect()->route('caracteristicas.index')
                             ->with('success', '¡La característica "' . $nombre . '" ha sido eliminada correctamente!');

        } catch (\Exception $e) {
            // Manejo de error si no se puede eliminar 
            // Esto sucede comúnmente si hay restricciones de clave foránea 
            // (p. ej., si un Negocio aún usa esta característica)
            return redirect()->back()
                             ->with('error', 'No se puede eliminar la característica "' . $nombre . '". Podría estar siendo utilizada por otro recurso. Detalles: ' . $e->getMessage());
        }
    }

}