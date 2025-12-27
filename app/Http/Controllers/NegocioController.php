<?php

namespace App\Http\Controllers;
use App\Models\Negocio;
use App\Models\NegocioContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categoria; 
use App\Models\Caracteristica;
use Illuminate\Support\Facades\DB; // <-- ¡AGREGADO PARA TRANSACCIONES!
use Illuminate\Support\Facades\Storage; // <-- ¡AGREGADO PARA MANEJO DE ARCHIVOS (si lo necesitas más adelante)!


class NegocioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    // Usamos with('estado') para traer el nombre del estado de una sola vez
    $negocios = Negocio::where('user_id', Auth::id())
                        ->with('estado') // <-- AGREGADO
                        ->orderBy('created_at', 'desc')
                        ->get();

    return view('negocios.index', compact('negocios'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::where('activa', true)
                              ->orderBy('nombre')
                              ->get();

        return view('negocios.create', compact('categorias')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓN
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string', 'max:1000'],
            'categoria' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:50'],
            'email_contacto' => ['nullable', 'email', 'max:255'],
            'web' => ['nullable', 'url', 'max:255'],
            'direccion_manual' => ['required', 'string', 'max:255'],
            // 'latitud' y 'longitud' se validarán como números
            'latitud' => ['required', 'numeric'],
            'longitud' => ['required', 'numeric'],
        ]);

        // 2. CREAR EL NEGOCIO (usando una transacción para asegurar que ambos se creen o ninguno)
        try {
            DB::beginTransaction();

            $negocio = Negocio::create([
                'user_id' => Auth::id(), 
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'categoria' => $request->categoria,
                'es_emprendimiento' => $request->has('es_emprendimiento'), 
                'estado_id' => 1, // <-- CAMBIADO: Antes era 'estado' => 'pendiente'
                'es_destacado' => false, 
                'palabras_clave' => null, 
            ]);

            // 3. CREAR EL REGISTRO DE CONTACTO
            NegocioContacto::create([
                'negocio_id' => $negocio->id,
                'direccion_manual' => $request->direccion_manual,
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'telefono' => $request->telefono,
                'email_contacto' => $request->email_contacto,
                'web' => $request->web,
            ]);

            DB::commit();

            // 4. REDIRECCIÓN
            return redirect()->route('dashboard')
                ->with('status', '¡Negocio registrado con éxito! Completa los detalles ahora.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al registrar el negocio: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Negocio $negocio)
    {
        // 1. Verificar si el usuario autenticado es dueño de este negocio.
        if ($negocio->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este negocio.');
        }

        // 2. Cargar las relaciones de contacto y devolver la vista.
        $negocio->load('contacto'); 

        return view('negocios.show', compact('negocio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Negocio $negocio)
    {
        if ($negocio->user_id !== Auth::id() && Auth::user()->role !== 'admin_principal') {
            abort(403);
        }

        $categorias = Categoria::where('activa', true)->orderBy('nombre')->get();
        
        // Traemos los estados para el selector del Admin
        $estados = \App\Models\EstadoNegocio::all(); 

        return view('negocios.edit', compact('negocio', 'categorias', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Negocio $negocio)
{
    // 1. Validación (Asegúrate de incluir estado_id si es admin)
    $rules = [
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required',
        'categoria' => 'required',
        // ... tus otras validaciones ...
    ];

    if (Auth::user()->roles->first()->nombre === 'admin_principal') {
        $rules['estado_id'] = 'required|exists:estado_negocios,id';
    }

    $validated = $request->validate($rules);

    // 2. Actualizar datos básicos
    $negocio->update([
        'nombre' => $validated['nombre'],
        'descripcion' => $validated['descripcion'],
        'categoria' => $validated['categoria'],
        'es_emprendimiento' => $request->has('es_emprendimiento'),
    ]);

    // 3. ACTUALIZACIÓN DEL ESTADO (La pieza que falta)
    if (Auth::user()->roles->first()->nombre === 'admin_principal' && $request->has('estado_id')) {
        $negocio->estado_id = $request->estado_id;
        $negocio->save();
    }

    // 4. Actualizar contacto (Como ya lo tenías)
    $negocio->contacto->update([
        'telefono' => $request->telefono,
        'email_contacto' => $request->email_contacto,
        'web' => $request->web,
        'direccion_manual' => $request->direccion_manual,
        'latitud' => $request->latitud,
        'longitud' => $request->longitud,
    ]);

    return redirect()->route('negocios.index')->with('success', 'Negocio actualizado correctamente.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // ======================================================================
    // MÉTODOS DE LA ADENDA 3: COMPLETAR Y GUARDAR
    // ======================================================================
    
    /**
     * Show the form for completing business details.
     */
public function completar(Negocio $negocio)
    {
        // 1. Capa de seguridad: Asegurar que el usuario es dueño.
        if ($negocio->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para completar los datos de este negocio.');
        }

        // 2. Cargar todas las características disponibles (para los checkboxes)
        $caracteristicas = Caracteristica::all();

        // 3. Obtener los IDs de las características actualmente asignadas al negocio
        $caracteristicasActuales = $negocio->caracteristicas->pluck('id')->toArray();

        // 4. ¡CLAVE!: Precargar la relación 'imagenes' para que la vista pueda contar las fotos
        // Si no existe la relación 'imagenes', la vista fallará al intentar llamarla.
        //$negocio->load('imagenes'); 

        // 5. Devolver la vista con los datos.
        return view('negocios.completar', compact('negocio', 'caracteristicas', 'caracteristicasActuales'));
    }

    /**
     * Store the additional details (keywords, features, photos) for a business.
     * * @param \Illuminate\Http\Request $request
     * @param \App\Models\Negocio $negocio
     * @return \Illuminate\Http\Response
     */
public function guardarCompletar(Request $request, Negocio $negocio)
    {
        // 1. Capa de seguridad: Asegurar que el usuario es dueño.
        if ($negocio->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Operación no autorizada.');
        }

        // 2. VALIDACIÓN (AJUSTADA PARA SUBIDA DE UNA SOLA FOTO: 'foto')
        $request->validate([
            'palabras_clave' => ['nullable', 'string', 'max:500'],
            'caracteristicas_ids' => ['nullable', 'array'],
            'caracteristicas_ids.*' => ['exists:caracteristicas,id'],
            
            // Reglas para un ÚNICO ARCHIVO
            'foto' => [
                'nullable', 
                'file', // Debe ser un archivo subido
                'mimetypes:image/jpeg,image/png,image/gif,image/webp', // Tipos MIME flexibles
                'mimes:jpeg,png,jpg,gif,webp', // Extensiones permitidas
                'max:5120' // 5MB
            ], 
        ], [
            'caracteristicas_ids.exists' => 'Una o más características seleccionadas no son válidas.',
            'foto.mimetypes' => 'El archivo no es un tipo de imagen válido (JPG, PNG, GIF, WEBP).',
            'foto.mimes' => 'El archivo no tiene una extensión de imagen permitida (jpeg, png, jpg, gif, webp).',
            'foto.max' => 'La foto no debe exceder los 2MB de tamaño.',
        ]);

        try {
            DB::beginTransaction();
            
            // 3. ACTUALIZAR LAS PALABRAS CLAVE Y SINCRONIZAR CARACTERÍSTICAS
            $negocio->update([
                'palabras_clave' => $request->palabras_clave,
            ]);

            $negocio->caracteristicas()->sync($request->caracteristicas_ids ?? []);
            
            // 4. MANEJO DE FOTOS (LÓGICA UNO A UNO)
            if ($request->hasFile('foto')) {
                
                // 4.1. VERIFICACIÓN DE LÍMITE (Doble Check de seguridad)
                $fotosActuales = $negocio->imagenes()->count();
                $limite = $negocio->limite_fotos ?? 5;

                if ($fotosActuales >= $limite) {
                    // Si intenta subir una foto y ya alcanzó el límite, hacer rollback y mostrar error.
                    DB::rollBack();
                    return redirect()->back()
                                     ->with('error', 'Error: Ya has alcanzado el límite de ' . $limite . ' fotos para este negocio.');
                }

                // 4.2. Obtener el archivo único
                $foto = $request->file('foto');
                
                // Determinar si será la foto principal (si no hay fotos existentes)
                $es_principal = ($fotosActuales === 0);
                
                // Determinar el orden (será la última en la lista)
                $orden = $fotosActuales; 

                // 4.3. Almacenar el archivo
                $ruta = $foto->store('negocios/' . $negocio->id, 'public'); 

                // 4.4. Crear el registro en la base de datos (registro único)
                $negocio->imagenes()->create([
                    'ruta_archivo' => $ruta, 
                    'es_principal' => $es_principal, 
                    'orden' => $orden, 
                ]);
            }
            
            DB::commit();

            // 5. REDIRECCIÓN DE ÉXITO (Redireccionamos de vuelta a completar para que pueda seguir subiendo fotos)
            // Ya que el guardado es incremental, volvemos a la misma vista.
            return redirect()->route('negocios.completar', $negocio)
                             ->with('success', '¡Detalles (y foto si se subió) guardados con éxito! Puedes añadir más fotos.');
            
        } catch (\Exception $e) {
            DB::rollBack();

            // 6. REDIRECCIÓN DE FALLO DE BASE DE DATOS
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Error al guardar los detalles adicionales: ' . $e->getMessage());
        }
    }

    // ... (El resto del controlador)
}