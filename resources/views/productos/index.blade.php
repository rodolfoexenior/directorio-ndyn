@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Título y enlace de navegación --}}
        <div>
            <a href="{{ route('negocios.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> {{ __('Volver a Mis Negocios') }}
            </a>
            <h1 class="h3 d-inline-block mb-0">{{ __('Gestión de Productos') }}</h1>
        </div>

        {{-- Botón para crear nuevo producto --}}
        <a href="{{ route('negocios.productos.create', $negocio) }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> {{ __('Añadir Nuevo Producto') }}
        </a>
    </div>

    {{-- Información del Negocio --}}
    <div class="alert alert-info" role="alert">
        <h5 class="alert-heading mb-0">{{ $negocio->nombre }}</h5>
        <p class="mb-0">{{ __('Categoría: ') . $negocio->categoria }}</p>
    </div>

    {{-- Tabla de Listado de Productos --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">{{ __('Listado de Productos (Total: ') . count($productos) . ')' }}</h5>
        </div>
        <div class="card-body p-0">

            @if ($productos->isEmpty())
            <div class="p-4 text-center text-muted">
                <p class="mb-0">{{ __('Aún no tienes productos registrados.') }}</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 5%">{{ __('ID') }}</th>
                            <th style="width: 15%">{{ __('Imagen') }}</th>
                            <th style="width: 35%">{{ __('Nombre') }}</th>
                            <th style="width: 25%">{{ __('Precio') }}</th>
                            <th style="width: 20%">{{ __('Acciones') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>
                                @if ($producto->imagen_ruta)
                                {{-- Usamos la ruta standard de Laravel para archivos públicos --}}
                                <img src="{{ asset('storage/' . $producto->imagen_ruta) }}"
                                    alt="{{ $producto->nombre_producto }}"
                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                @else
                                <span class="text-muted small">{{ __('Sin foto') }}</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $producto->nombre_producto }}</strong>
                                <p class="text-muted small mb-0">{{ Str::limit($producto->descripcion_producto, 50) }}
                                </p>
                            </td>
                            <td>
                                {{-- Formato de moneda, ajusta si tienes un helper específico --}}
                                <span
                                    class="fw-bold text-success">{{ '$' . number_format($producto->precio, 2) }}</span>
                            </td>
                            <td>
                                {{-- Botones de Acciones --}}
                                <a href="{{ route('negocios.productos.edit', [$negocio, $producto]) }}"
                                    class="btn btn-sm btn-warning me-1" title="{{ __('Editar') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                {{-- Botón de Eliminar (usa formulario) --}}
                                <form action="{{ route('negocios.productos.destroy', [$negocio, $producto]) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Estás seguro de querer eliminar este producto?')"
                                        title="{{ __('Eliminar') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection