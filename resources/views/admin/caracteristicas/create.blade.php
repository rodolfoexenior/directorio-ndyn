@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">

            {{-- Título --}}
            <h1 class="h3 mb-4 text-gray-800">{{ __('Crear Nueva Característica') }}</h1>

            {{-- Tarjeta del Formulario --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Formulario de Creación') }}</h6>
                </div>
                <div class="card-body">

                    {{-- Formulario para almacenar la característica --}}
                    <form action="{{ route('caracteristicas.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Campo Nombre --}}
                        <div class="mb-3">
                            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre"
                                name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo Descripción --}}
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">{{ __('Descripción') }}</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                                name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ************************************************* --}}
                        {{-- NUEVO CAMPO: Subida de Icono --}}
                        {{-- ************************************************* --}}
                        <div class="mb-3">
                            <label for="icono_file" class="form-label">{{ __('Icono (Archivo de Imagen)') }}</label>
                            <input type="file" class="form-control @error('icono_file') is-invalid @enderror"
                                id="icono_file" name="icono_file" accept="image/*">
                            @error('icono_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Archivos permitidos: JPG, PNG, GIF, SVG. Máx.
                                2MB.</small>
                        </div>
                        {{-- ************************************************* --}}

                        {{-- Botones de Acción --}}
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('caracteristicas.index') }}" class="btn btn-secondary me-2">
                                {{ __('Cancelar') }}
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> {{ __('Guardar Característica') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection