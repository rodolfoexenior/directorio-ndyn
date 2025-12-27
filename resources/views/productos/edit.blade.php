@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">

                {{-- Cabecera con navegación --}}
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        {{-- Volver al listado de productos --}}
                        <a href="{{ route('negocios.productos.index', $negocio) }}"
                            class="btn btn-sm btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('Volver al Listado') }}
                        </a>
                        {{-- Título --}}
                        <h4 class="d-inline-block mb-0">{{ __('Editar Producto: ') . $producto->nombre_producto }}</h4>
                    </div>
                </div>

                <div class="card-body">

                    {{-- Información del Negocio --}}
                    <div class="alert alert-primary p-2 mb-4 small" role="alert">
                        {{ __('Editando producto para el negocio:') }} <strong>{{ $negocio->nombre }}</strong>
                    </div>

                    {{-- Formulario de Edición --}}
                    {{-- IMPORTANTE: Usar PUT/PATCH y enctype="multipart/form-data" --}}
                    <form method="POST" action="{{ route('negocios.productos.update', [$negocio, $producto]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Spoofing del método para HTTP PUT --}}

                        {{-- Nombre del Producto --}}
                        <div class="mb-3">
                            <label for="nombre_producto" class="form-label">{{ __('Nombre del Producto') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre_producto') is-invalid @enderror"
                                id="nombre_producto" name="nombre_producto"
                                {{-- CLAVE: Pre-llenar con el valor actual --}}
                                value="{{ old('nombre_producto', $producto->nombre_producto) }}" required>
                            @error('nombre_producto')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Descripción del Producto --}}
                        <div class="mb-3">
                            <label for="descripcion_producto" class="form-label">{{ __('Descripción') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('descripcion_producto') is-invalid @enderror"
                                id="descripcion_producto" name="descripcion_producto" rows="3"
                                required>{{ old('descripcion_producto', $producto->descripcion_producto) }}</textarea>

                            @error('descripcion_producto')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Precio --}}
                        <div class="mb-3">
                            <label for="precio" class="form-label">{{ __('Precio') }} <span
                                    class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0"
                                class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio"
                                {{-- CLAVE: Pre-llenar con el valor actual --}}
                                value="{{ old('precio', $producto->precio) }}" required>
                            @error('precio')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Imagen del Producto (Opcional, con vista previa y opción de reemplazar) --}}
                        <div class="mb-4">
                            <label for="imagen" class="form-label d-block">{{ __('Imagen del Producto') }}</label>

                            {{-- Vista Previa de Imagen Actual --}}
                            @if ($producto->imagen_ruta)
                            <div class="mb-3">
                                <p class="small text-muted mb-1">{{ __('Imagen actual:') }}</p>
                                {{-- Usamos la ruta standard de Laravel para archivos públicos --}}
                                <img src="{{ asset('storage/' . $producto->imagen_ruta) }}"
                                    alt="{{ $producto->nombre_producto }}"
                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                            </div>
                            @endif

                            <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen"
                                name="imagen" accept="image/*">
                            <small class="form-text text-muted">Formatos: JPG, PNG, WEBP. Máximo 5MB. Subir una nueva
                                imagen reemplazará la actual.</small>
                            @error('imagen')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Botón de Guardar --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-sync-alt me-1"></i> {{ __('Actualizar Producto') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection