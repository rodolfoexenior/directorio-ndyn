<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Para manejo de archivos si es necesario
use App\Models\ServicioImagen; // Modelo para las imágenes de servicio

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     * Muestra el listado de servicios de un negocio específico.
     *
     * @param  \App\Models\Negocio  $negocio
     * @return \Illuminate\Http\Response
     */
public function index(Negocio $negocio)
    {
        // Obtener solo los servicios asociados a este negocio, precargando las imágenes para optimizar
        $servicios = $negocio->servicios()->with('imagenes')->get(); 

        // NUEVA LÓGICA: Obtener todas las imágenes únicas que están vinculadas a CUALQUIERA de los servicios de este negocio.
        // Esto se logra iterando la relación N:N a través de los servicios.
        $imagenes_disponibles = $servicios->flatMap(function ($servicio) {
            return $servicio->imagenes;
        })->unique('id'); // Usar unique('id') es crucial para N:N, ya que evita duplicados si una imagen está en varios servicios.

        return view('servicios.index', compact('negocio', 'servicios', 'imagenes_disponibles'));
    }

    /**
    
     * Muestra el formulario para crear un nuevo servicio.
     */
    public function create(Negocio $negocio)
    {
        return view('servicios.create', compact('negocio'));
    }

  
public function store(Request $request, Negocio $negocio)
    {
        // VALIDACIÓN
        $validatedData = $request->validate([
            'nombre_servicio' => ['required', 'string', 'max:255'],
            'descripcion_servicio' => ['required', 'string'],
            'precio' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            // Regla para la subida de múltiples imágenes
            'imagenes_galeria' => ['nullable', 'array', 'max:10'], // Límite de 10 imágenes
            'imagenes_galeria.*' => [
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120' // 5MB
            ], 
        ]);
        
        // CREACIÓN DEL SERVICIO (Datos Textuales)
        $servicio = $negocio->servicios()->create($validatedData);

        // MANEJO DE IMÁGENES (Galería N:N)
        if ($request->hasFile('imagenes_galeria')) {
            $imagenesIds = [];

            foreach ($request->file('imagenes_galeria') as $file) {
                // a. Subir el archivo
                // Carpeta: 'servicios/{negocio_id}'
                $path = $file->store('servicios/' . $negocio->id, 'public');

                // b. Crear el registro en la tabla servicio_imagens
                $imagen = $servicio->imagenes()->create([
                    'ruta_archivo' => $path,
                    'alt_texto' => $servicio->nombre_servicio . ' - ' . $file->getClientOriginalName(), // Generar un texto alt simple
                ]);
                
                // c. Recolectar IDs
                $imagenesIds[] = $imagen->id;
            }

     
            // La relación BelongsToMany adjunta automáticamente la ID del servicio
            // $servicio->imagenes()->sync($imagenesIds); // Usaríamos sync si estuviéramos editando
            // En 'store', podemos usar la creación masiva de la pivote usando la relación:
            // Dado que ya usamos $servicio->imagenes()->create() en el loop, esto ya creó los registros en la pivote
        }

        // 5. REDIRECCIÓN
        return redirect()->route('negocios.servicios.index', $negocio)
                         ->with('status', '¡El servicio "' . $servicio->nombre_servicio . '" ha sido creado con éxito! (Imágenes guardadas)');
    }

 
    public function show(Negocio $negocio, Servicio $servicio)
    {
        // Se puede añadir lógica aquí si se requiere una vista de detalle separada
        return view('servicios.show', compact('negocio', 'servicio'));
    }

 /*
    public function edit(Negocio $negocio, Servicio $servicio)
    {
        // Laravel garantiza que $servicio pertenezca a $negocio (Route Model Binding con anidamiento)
        return view('servicios.edit', compact('negocio', 'servicio'));
    }
*/

public function edit(Negocio $negocio, Servicio $servicio)
    {
        // 1. Obtener TODOS los servicios del negocio, precargando sus imágenes.
        // Esto se hace para poder acceder a todas las imágenes subidas por el negocio.
        $todos_los_servicios = $negocio->servicios()->with('imagenes')->get(); 

        // 2. Obtener la colección de TODAS las imágenes disponibles del negocio.
        // * flatMap: Combina las colecciones de imágenes de todos los servicios en una sola lista plana.
        // * unique('id'): Asegura que si una imagen está asociada a 2 o 3 servicios, solo aparezca UNA vez.
        $imagenes_disponibles = $todos_los_servicios->flatMap(function ($s) {
            return $s->imagenes;
        })->unique('id'); 

        // 3. Obtener los IDs de las imágenes YA VINCULADAS al servicio actual.
        // * $servicio->imagenes: Accede a la relación BelongsToMany (las imágenes de ESTE servicio).
        // * pluck('id'): Extrae solo la columna 'id' de esos registros, creando una lista simple de IDs.
        // Esto será un array como [1, 5, 8].
        $imagenes_vinculadas = $servicio->imagenes->pluck('id')->toArray(); 

        // 4. Pasar todas las variables necesarias a la vista.
        return view('servicios.edit', compact('negocio', 'servicio', 'imagenes_disponibles', 'imagenes_vinculadas'));
    }
public function update(Request $request, Negocio $negocio, Servicio $servicio)
{
    // VALIDACIÓN (Mantenida)
    $validatedData = $request->validate([
        'nombre_servicio' => ['required', 'string', 'max:255'],
        'descripcion_servicio' => ['required', 'string'],
        'precio' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        // NOTA: No necesitamos validar 'imagenes_seleccionadas[]' aquí, 
        // ya que solo contiene IDs existentes.
        
        // Regla para la subida de múltiples imágenes (Mantenida)
        'imagenes_galeria' => ['nullable', 'array', 'max:10'], 
        'imagenes_galeria.*' => [
            'file',
            'mimetypes:image/jpeg,image/png,image/gif,image/webp',
            'max:5120' // 5MB
        ], 
    ]);
    
    // ACTUALIZACIÓN DE DATOS TEXTUALES (Mantenida)
    $servicio->update([
        'nombre_servicio' => $validatedData['nombre_servicio'],
        'descripcion_servicio' => $validatedData['descripcion_servicio'],
        'precio' => $validatedData['precio'],
    ]);

    // ======================================================================
    // INICIO: LÓGICA DE GALERÍA N:N (Sincronización y Nuevas Subidas)
    // ======================================================================

    // 1. Obtener los IDs de las imágenes seleccionadas vía checkbox.
    // Usamos un array vacío [] como valor por defecto si el usuario no marca nada (o no existe el campo).
    $imagenes_final_ids = $request->input('imagenes_seleccionadas', []);

    // 2. MANEJO DE NUEVAS IMÁGENES SUBIDAS
    if ($request->hasFile('imagenes_galeria')) {
        foreach ($request->file('imagenes_galeria') as $file) {
            // a. Subir el archivo (Mantenido)
            $path = $file->store('servicios/' . $negocio->id, 'public');

            // b. Crear el registro en la tabla servicio_imagens y adjuntar (attach)
            // El método create() aquí ya adjunta automáticamente la nueva imagen al servicio.
            $imagen = $servicio->imagenes()->create([
                'ruta_archivo' => $path,
                'alt_texto' => $servicio->nombre_servicio . ' - ' . $file->getClientOriginalName(),
            ]);

            // c. Añadir el ID de la imagen recién creada a la lista final.
            // Esto es CRUCIAL para que el siguiente sync() no la desvincule inmediatamente.
            $imagenes_final_ids[] = $imagen->id; 
        }
    }

    // 3. SINCRONIZACIÓN FINAL
    // Aquí es donde se rompen y establecen todas las relaciones N:N en la tabla pivote, 
    // basándose en la lista combinada ($imagenes_final_ids).
    $servicio->imagenes()->sync($imagenes_final_ids);
    
    // ======================================================================
    // FIN: LÓGICA DE GALERÍA N:N
    // ======================================================================

    // REDIRECCIÓN (Mantenida)
    return redirect()->route('negocios.servicios.index', $negocio)
                    ->with('status', '¡El servicio "' . $servicio->nombre_servicio . '" ha sido actualizado con éxito! (Galería actualizada)');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Negocio $negocio, Servicio $servicio)
    {
        // 1. Obtener las imágenes asociadas ANTES de eliminar el servicio
        $imagenes_a_revisar = $servicio->imagenes; 

        // 2. Desvincular/Eliminar el servicio de la BD
        // Esto elimina las entradas en la tabla pivote automáticamente por cascade,
        // o si no, podemos desvincularlas explícitamente:
        $servicio->imagenes()->detach(); 

        // 3. Eliminar el registro del servicio (que elimina las pivotes por cascade si no usamos detach)
        $servicio->delete();

        // 4. Limpiar las imágenes físicas que quedaron huérfanas
        foreach ($imagenes_a_revisar as $imagen) {
            // Contar cuántos servicios aún usan esta imagen
            // Usamos la relación inversa 'servicios()' definida en ServicioImagen
            if ($imagen->servicios()->count() === 0) {
                // Si el conteo es CERO, significa que ya nadie más la usa.
                Storage::disk('public')->delete($imagen->ruta_archivo);
                
                // Eliminar el registro de la imagen de la tabla servicio_imagens
                $imagen->delete(); 
            }
        }

        // 5. REDIRECCIÓN
        return redirect()->route('negocios.servicios.index', $negocio)
                         ->with('status', '¡El servicio y sus imágenes no utilizadas han sido eliminados correctamente!');
    }

    public function detachImage(Negocio $negocio, Servicio $servicio, ServicioImagen $imagen)
    {
        // 1. Desvincular la imagen del servicio (Eliminar de la tabla pivote)
        // Usamos detach() para romper la relación BelongsToMany solo para este servicio.
        $servicio->imagenes()->detach($imagen->id); 

        // 2. Verificar si la imagen ha quedado huérfana (ya no está vinculada a NINGÚN servicio)
        // Usamos el método count() de la relación inversa 'servicios()'
        if ($imagen->servicios()->count() === 0) {
            
            // Si nadie más la usa, eliminarla permanentemente:
            
            // a. Eliminar el archivo físico del disco
            Storage::disk('public')->delete($imagen->ruta_archivo);
            
            // b. Eliminar el registro de la imagen de la tabla servicio_imagens
            $imagen->delete();
            
            $message = 'La imagen ha sido eliminada permanentemente de la galería.';
        } else {
            $message = 'La imagen ha sido desvinculada del servicio, pero se mantiene en la galería porque está asociada a otros servicios.';
        }

        // 3. Redirección de vuelta a la edición del servicio
        return redirect()->route('negocios.servicios.edit', [$negocio, $servicio])
                         ->with('status', $message);
    }


public function destroyImage(Negocio $negocio, ServicioImagen $imagen)
    {
        // Esta acción es la ELIMINACIÓN PERMANENTE desde la Galería Central.
        // Debe desvincular la imagen de CUALQUIER servicio y borrarla del disco.

        $ruta = $imagen->ruta_archivo;
        $message = 'La imagen ha sido eliminada permanentemente de la galería.';

        try {
            // 1. Desvincular de todos los servicios (rompe todas las N:N)
            // Esto es crucial para que no queden registros en la tabla pivote.
            $imagen->servicios()->detach(); 

            // 2. Eliminar el registro del modelo de imagen
            $imagen->delete(); 
            
            // 3. Limpiar el archivo físico
            if (Storage::disk('public')->exists($ruta)) {
                Storage::disk('public')->delete($ruta);
            }
        } catch (\Exception $e) {
            // Manejo de error si la base de datos falla, aunque el archivo haya sido eliminado
            $message = 'Error al eliminar la imagen: ' . $e->getMessage();
            // Opcionalmente, lanzar el error o registrarlo.
        }

        // 4. REDIRECCIÓN (¡LA CORRECCIÓN CLAVE!)
        // Siempre debe haber un return al final del método.
        return redirect()->route('negocios.servicios.index', $negocio)
                         ->with('status', $message);
    }


}