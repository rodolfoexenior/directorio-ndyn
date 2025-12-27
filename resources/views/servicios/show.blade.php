@extends('layouts.app')
@section('content')

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalles del Servicio: {{ $servicio->nombre_servicio }}</h2>
        <a href="{{ route('negocios.servicios.index', $negocio) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Listado
        </a>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-info text-white">
            Información Principal
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Negocio:</strong>
                    <p>{{ $negocio->nombre_negocio }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>ID del Servicio:</strong>
                    <p>{{ $servicio->id }}</p>
                </div>
                <div class="col-12 mb-3">
                    <strong>Precio:</strong>
                    <p class="h4 text-success">$ {{ number_format($servicio->precio, 2) }}</p>
                </div>
                <div class="col-12 mb-3">
                    <strong>Descripción:</strong>
                    <p>{{ $servicio->descripcion_servicio }}</p>
                </div>
                <div class="col-12 text-end">
                    <a href="{{ route('negocios.servicios.edit', [$negocio, $servicio]) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar Servicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            Galería de Imágenes ({{ $servicio->imagenes->count() }})
        </div>
        <div class="card-body">
            @if ($servicio->imagenes->isEmpty())
            <div class="alert alert-warning">
                Este servicio no tiene imágenes asociadas.
            </div>
            @else
            <div class="row">
                @foreach ($servicio->imagenes as $imagen)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $imagen->ruta_archivo) }}" class="card-img-top"
                            alt="{{ $imagen->alt_texto ?? 'Imagen de Servicio' }}"
                            style="height: 180px; object-fit: cover;">
                        <div class="card-body p-2">
                            <p class="card-text small text-truncate">
                                Ruta: {{ $imagen->ruta_archivo }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@endsection