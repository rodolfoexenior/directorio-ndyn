@extends('layouts.app')
@section('content')

<div class="container mt-5">
    <div class="mb-4">
        <h2>Crear Nuevo Servicio para {{ $negocio->nombre_negocio }}</h2>
    </div>

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

    <form action="{{ route('negocios.servicios.store', $negocio) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre_servicio" class="form-label">Nombre del Servicio:</label>
                <input type="text" name="nombre_servicio" class="form-control"
                    placeholder="Ej: Corte de Cabello Clásico" value="{{ old('nombre_servicio') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="precio" class="form-label">Precio ($):</label>
                <input type="number" step="0.01" name="precio" class="form-control" placeholder="Ej: 25.00"
                    value="{{ old('precio') }}" required>
            </div>
            <div class="col-12 mb-3">
                <label for="descripcion_servicio" class="form-label">Descripción del Servicio:</label>
                <textarea name="descripcion_servicio" class="form-control" style="height:150px"
                    required>{{ old('descripcion_servicio') }}</textarea>
            </div>

            <hr class="my-4">

            <div class="col-12 mb-4">
                <h4>Galería de Imágenes (Opcional)</h4>
                <p class="text-muted">Puedes subir hasta 10 imágenes para este servicio.</p>
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
                <a href="{{ route('negocios.servicios.index', $negocio) }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Crear Servicio</button>
            </div>
        </div>
    </form>
</div>

@endsection