@extends('layouts.app')
@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Servicios de {{ $negocio->nombre }}</h2>
        <a href="{{ route('negocios.servicios.create', $negocio) }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Crear Nuevo Servicio
        </a>
    </div>

    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($servicios->isEmpty())
    <div class="alert alert-info">
        Aún no has agregado ningún servicio. ¡Comienza a crear uno!
    </div>
    @else
    @if (!$imagenes_disponibles->isEmpty())
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-light">
            <h4 class="mb-0">Galería de Imágenes del Negocio ({{ $imagenes_disponibles->count() }})</h4>
            <p class="text-muted small">Haz clic en 'X' para **eliminar permanentemente** la imagen y el archivo de
                disco.</p>
        </div>
        <div class="card-body p-3">
            <div class="d-flex flex-row overflow-auto pb-2" style="max-height: 200px;">
                @foreach ($imagenes_disponibles as $imagen)
                <div class="p-1 flex-shrink-0 position-relative" style="width: 150px;">
                    <div class="card border-0 h-100">
                        <img src="{{ asset('storage/' . $imagen->ruta_archivo) }}" class="rounded shadow-sm"
                            alt="{{ $imagen->alt_texto ?? 'Imagen de Galería' }}"
                            style="height: 140px; width: 100%; object-fit: cover;">
                    </div>

                    {{-- NUEVO BOTÓN DE ELIMINACIÓN PERMANENTE --}}
                    <form action="{{ route('negocios.galeria.destroy', [$negocio, $imagen]) }}" method="POST"
                        onsubmit="return confirm('¡ADVERTENCIA! ¿Estás seguro de ELIMINAR PERMANENTEMENTE esta imagen? Se desvinculará de todos los servicios y se borrará el archivo de disco.');"
                        class="position-absolute" style="top: 8px; right: 8px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm p-0 rounded-circle"
                            style="width: 24px; height: 24px; line-height: 1;">
                            <i class="bi bi-x-circle-fill" style="font-size: 14px;"></i>
                        </button>
                    </form>
                    {{-- FIN BOTÓN --}}

                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Imágenes</th>
                    <th scope="col" style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servicios as $servicio)
                <tr>
                    <th scope="row">{{ $servicio->id }}</th>
                    <td>{{ $servicio->nombre_servicio }}</td>
                    <td>{{ Str::limit($servicio->descripcion_servicio, 50) }}</td>
                    <td>$ {{ number_format($servicio->precio, 2) }}</td>
                    <td>
                        {{ $servicio->imagenes->count() }} imágenes
                    </td>
                    <td>
                        <a href="{{ route('negocios.servicios.edit', [$negocio, $servicio]) }}"
                            class="btn btn-sm btn-primary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('negocios.servicios.destroy', [$negocio, $servicio]) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"
                                onclick="return confirm('¿Estás seguro de que quieres eliminar este servicio? Esto también limpiará sus imágenes huérfanas.');">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="mt-3">
        <a href="{{ route('negocios.index') }}" class="btn btn-secondary">Volver a Negocios</a>
    </div>
</div>

@endsection