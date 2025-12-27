@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">

            {{-- Título --}}
            <h1 class="h3 mb-4 text-gray-800">{{ __('Editar Característica') }}: {{ $caracteristica->nombre }}</h1>

            {{-- Tarjeta del Formulario --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Formulario de Edición') }}</h6>
                </div>
                <div class="card-body">

                    {{-- Formulario para actualizar la característica --}}
                    <form action="{{ route('caracteristicas.update', $caracteristica) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- ¡Importante! Usa el método PUT para actualizar --}}

                        {{-- Campo Nombre --}}
                        <div class="mb-3">
                            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                                name="nombre" value="{{ old('nombre', $caracteristica->nombre) }}"
                                {{-- Muestra el valor existente o el valor anterior si hubo error --}} required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo Descripción --}}
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">{{ __('Descripción') }}</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                                name="descripcion"
                                rows="3">{{ old('descripcion', $caracteristica->descripcion) }}</textarea>
                            {{-- Muestra el valor existente o el valor anterior si hubo error --}}
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- ************************************************* --}}
                        {{-- NUEVO CAMPO: Subida de Icono --}}
                        {{-- ************************************************* --}}
                        <div class="mb-3">
                            <label for="icono_file" class="form-label">{{ __('Icono (Archivo de Imagen)') }}</label>

                            {{-- Muestra el icono actual si existe --}}
                            @if ($caracteristica->icono)
                            <div class="mb-2">
                                <p class="mb-1">{{ __('Icono Actual:') }}</p>
                                <img src="{{ Storage::url($caracteristica->icono) }}" alt="Icono Actual"
                                    style="width: 50px; height: 50px; object-fit: contain; border: 1px solid #ddd; padding: 5px;">
                            </div>
                            @endif

                            <input type="file" class="form-control @error('icono_file') is-invalid @enderror"
                                id="icono_file" name="icono_file" accept="image/*">
                            @error('icono_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Sube una nueva imagen para reemplazar la actual. Máx.
                                2MB.</small>
                        </div>
                        {{-- ************************************************* --}}
                        {{-- Botones de Acción --}}
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('caracteristicas.index') }}" class="btn btn-secondary me-2">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-arrow-repeat"></i> {{ __('Actualizar Característica') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection