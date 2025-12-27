<?php

namespace App\Http\Controllers;

use App\Models\Negocio;  // Importar el modelo Negocio para inyección
use App\Models\Producto; // Importar el modelo Producto, aunque no se use directamente aquí
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     * Muestra el listado de productos de un negocio específico.
     *
     * @param  \App\Models\Negocio  $negocio
     * @return \Illuminate\Http\Response
     */
    public function index(Negocio $negocio)
    {
        // Obtener solo los productos asociados a este negocio
        $productos = $negocio->productos;
        
        // Devolver una vista con los datos
        return view('productos.index', compact('negocio', 'productos'));
    }

    /**
     * Show the form for creating a new resource.
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create(Negocio $negocio)
    {
        return view('productos.create', compact('negocio'));
    }

    /**
     * Store a newly created resource in storage.
     * Almacena un nuevo producto en la base de datos.
     */
public function store(Request $request, Negocio $negocio)
    {
        // 1. VALIDACIÓN
        $request->validate([
            'nombre_producto' => ['required', 'string', 'max:255'],
            'descripcion_producto' => ['required', 'string'],
            'precio' => ['required', 'numeric', 'min:0', 'max:999999.99'], // Ajustar el max si es necesario
            'imagen' => [
                'nullable', 
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120' // 5MB
            ], 
        ], [
            'descripcion_producto.required' => 'La descripción del producto es obligatoria.',
            'precio.required' => 'El precio del producto es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'imagen.max' => 'La imagen no debe exceder los 5MB de tamaño.',
        ]);

        $data = $request->except('imagen'); // Obtener todos los datos excepto la imagen

        // 2. MANEJO DE IMAGEN
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            
            // Definimos la carpeta de destino: 'productos/{negocio_id}'
            $path = $file->store('productos/' . $negocio->id, 'public');
            
            // Guardamos la ruta relativa para la base de datos
            $data['imagen_ruta'] = $path; 
        }

        // 3. ASIGNACIÓN Y GUARDADO
        $producto = $negocio->productos()->create($data);
        
        // El campo 'negocio_id' se asigna automáticamente por la relación
        // $producto = $negocio->productos()->create([
        //     'nombre_producto' => $request->nombre_producto,
        //     // ... otros campos
        // ]);


        // 4. REDIRECCIÓN
        return redirect()->route('negocios.productos.index', $negocio)
                         ->with('status', '¡El producto "' . $producto->nombre_producto . '" ha sido creado con éxito!');
    }

    /**
     * Display the specified resource.
     * Muestra los detalles de un producto específico.
     */
    public function show(Negocio $negocio, Producto $producto)
    {
        // Lógica para mostrar detalles del producto
    }

    /**
     * Show the form for editing the specified resource.
     * Muestra el formulario de edición de un producto específico.
     */
public function edit(Negocio $negocio, Producto $producto)
    {
        // Nota: Laravel ya se asegura que $producto pertenezca a $negocio
        // si se utiliza el Route Model Binding con rutas anidadas correctamente.

        return view('productos.edit', compact('negocio', 'producto'));
    }

    /**
     * Update the specified resource in storage.
     * Actualiza un producto específico.
     */
 public function update(Request $request, Negocio $negocio, Producto $producto)
    {
        // 1. VALIDACIÓN (Misma que 'store', asegurando que los campos sean requeridos)
        $validatedData = $request->validate([
            'nombre_producto' => ['required', 'string', 'max:255'],
            'descripcion_producto' => ['required', 'string'],
            'precio' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'imagen' => [
                'nullable', 
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120' // 5MB
            ], 
        ], [
            'descripcion_producto.required' => 'La descripción del producto es obligatoria.',
            'precio.required' => 'El precio del producto es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'imagen.max' => 'La imagen no debe exceder los 5MB de tamaño.',
        ]);

        $ruta_antigua = $producto->imagen_ruta; // Guardar la ruta antigua

        // 2. MANEJO DE IMAGEN (Si se sube una nueva)
        if ($request->hasFile('imagen')) {
            // Subir la nueva imagen
            $path = $request->file('imagen')->store('productos/' . $negocio->id, 'public');
            
            // Asignar la nueva ruta a los datos validados para la actualización
            $validatedData['imagen_ruta'] = $path; 

            // Eliminar la imagen antigua si existía
            if ($ruta_antigua) {
                Storage::disk('public')->delete($ruta_antigua);
            }
        }
        
        // 3. ACTUALIZACIÓN DEL REGISTRO
        // Actualizamos todos los datos validados ($validatedData)
        $producto->update($validatedData);

        // 4. REDIRECCIÓN
        return redirect()->route('negocios.productos.index', $negocio)
                         ->with('status', '¡El producto "' . $producto->nombre_producto . '" ha sido actualizado con éxito!');
    }

    /**
     * Remove the specified resource from storage.
     * Elimina un producto específico.
     */
public function destroy(Negocio $negocio, Producto $producto)
    {
        // 1. ELIMINAR LA IMAGEN ASOCIADA (si existe)
        if ($producto->imagen_ruta) {
            // Utilizamos el disco 'public'
            Storage::disk('public')->delete($producto->imagen_ruta);
        }

        // 2. ELIMINAR EL REGISTRO DEL PRODUCTO DE LA BASE DE DATOS
        $producto->delete();

        // 3. REDIRECCIÓN
        return redirect()->route('negocios.productos.index', $negocio)
                         ->with('status', '¡El producto ha sido eliminado correctamente!');
    }
}