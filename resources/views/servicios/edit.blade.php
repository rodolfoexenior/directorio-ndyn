@extends('layouts.app')
@section('content')

<div class="container mt-5">
    <div class="mb-4">
        <h2>Editar Servicio: {{ $servicio->nombre_servicio }}</h2>
        <p class="text-muted">Negocio: {{ $negocio->nombre_negocio }}</p>
    </div>

    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>¡Ups!</strong> Hubo algunos problemas con tu entrada.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-primary text-white">
            Datos Generales y Añadir Imágenes
        </div>
        <div class="card-body">
            <form action="{{ route('negocios.servicios.update', [$negocio, $servicio]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nombre_servicio" class="form-label">Nombre del Servicio:</label>
                        <input type="text" name="nombre_servicio" class="form-control"
                            value="{{ old('nombre_servicio', $servicio->nombre_servicio) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="precio" class="form-label">Precio ($):</label>
                        <input type="number" step="0.01" name="precio" class="form-control"
                            value="{{ old('precio', $servicio->precio) }}" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="descripcion_servicio" class="form-label">Descripción del Servicio:</label>
                        <textarea name="descripcion_servicio" class="form-control" style="height:150px"
                            required>{{ old('descripcion_servicio', $servicio->descripcion_servicio) }}</textarea>
                    </div>



                    <hr class="my-4">
                    {{-- ******************************************************************* --}}
                    {{-- INICIO: GALERÍA DE SELECCIÓN CON CHECKBOXES (SOLUCIÓN AL ERROR 1) --}}
                    {{-- ******************************************************************* --}}
                    @if (!$imagenes_disponibles->isEmpty())
                    <div class="col-12 mb-4">
                        <h4>Seleccionar Imágenes Existentes de la Galería Central</h4>
                        <p class="text-muted">Marca las imágenes que deseas vincular a este servicio. Desmarca para
                            desvincular.</p>

                        {{-- Contenedor con scroll horizontal para la galería --}}
                        <div class="d-flex flex-row overflow-auto pb-2"
                            style="max-height: 200px; border: 1px solid #dee2e6; border-radius: .25rem; padding: 5px;">

                            @foreach ($imagenes_disponibles as $imagen)

                            {{-- Envoltura de cada imagen --}}
                            <div class="p-2 flex-shrink-0 position-relative" style="width: 150px;">
                                <label
                                    class="d-block card shadow-sm {{ in_array($imagen->id, $imagenes_vinculadas) ? 'border-primary border-3' : 'border-light border-1' }}"
                                    style="cursor: pointer;">

                                    {{-- La imagen en sí --}}
                                    <img src="{{ asset('storage/' . $imagen->ruta_archivo) }}"
                                        class="card-img-top rounded"
                                        alt="{{ $imagen->alt_texto ?? 'Imagen de Galería' }}"
                                        style="height: 120px; width: 100%; object-fit: cover;">

                                    {{-- Checkbox (La clave de la vinculación/desvinculación) --}}
                                    <input type="checkbox" name="imagenes_seleccionadas[]" value="{{ $imagen->id }}"
                                        class="position-absolute top-0 start-0 m-2"
                                        {{ in_array($imagen->id, $imagenes_vinculadas) ? 'checked' : '' }}>

                                    {{-- Texto de referencia --}}
                                    <div class="text-center small text-muted p-1 text-truncate">ID: {{ $imagen->id }}
                                    </div>
                                </label>
                            </div>

                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="col-12 mb-4">
                        <div class="alert alert-info">Aún no hay imágenes en la galería de este negocio para
                            seleccionar.</div>
                    </div>
                    @endif

                    <hr class="my-4">
                    {{-- ******************************************************************* --}}
                    {{-- FIN: GALERÍA DE SELECCIÓN --}}
                    {{-- ******************************************************************* --}}

                    <div class="col-12 mb-4">
                        <h4>Añadir Nuevas Imágenes a la Galería</h4>
                        <p class="text-muted">Las imágenes se añadirán a las existentes.</p>
                        <label for="imagenes_galeria" class="form-label">Seleccionar Archivos (Múltiples):</label>
                        <input type="file" name="imagenes_galeria[]" class="form-control" multiple accept="image/*">
                        @error('imagenes_galeria')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('imagenes_galeria.*')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">Guardar Cambios y Añadir Imágenes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
            Imágenes Actuales de la Galería ({{ $servicio->imagenes->count() }})
        </div>
        <div class="card-body">
            @if ($servicio->imagenes->isEmpty())
            <div class="alert alert-warning">
                Este servicio aún no tiene imágenes en su galería.
            </div>
            @else
            <div class="row">
                @foreach ($servicio->imagenes as $imagen)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $imagen->ruta_archivo) }}" class="card-img-top"
                            alt="{{ $imagen->alt_texto ?? 'Imagen de Servicio' }}"
                            style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2">
                            <p class="card-text text-muted small text-truncate" title="{{ $imagen->ruta_archivo }}">
                                ID: {{ $imagen->id }}
                            </p>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('negocios.servicios.index', $negocio) }}" class="btn btn-secondary">Volver al Listado de
            Servicios</a>
    </div>

</div>

@endsection