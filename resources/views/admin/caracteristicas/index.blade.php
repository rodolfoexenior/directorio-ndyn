@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">

            {{-- Título y Botón de Creación --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{ __('Administración de Características') }}</h1>
                <a href="{{ route('caracteristicas.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle"></i> {{ __('Crear Nueva Característica') }}
                </a>
            </div>

            {{-- Mensajes de Estado (Éxito o Error) --}}
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- Tarjeta Principal (Tabla de Datos) --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('Lista de Características') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="caracteristicasTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Icono') }}</th> {{-- ¡COLUMNA DE ICONO AÑADIDA! --}}
                                    <th>{{ __('Nombre') }}</th>
                                    <th>{{ __('Descripción') }}</th>
                                    <th>{{ __('Creado el') }}</th>
                                    <th class="text-center">{{ __('Acciones') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Itera sobre la colección de características --}}
                                @forelse ($caracteristicas as $caracteristica)
                                <tr>
                                    <td>{{ $caracteristica->id }}</td>
                                    {{-- ¡RENDERIZADO DEL ICONO! --}}
                                    <td>
                                        @if ($caracteristica->icono)
                                        <img src="{{ Storage::url($caracteristica->icono) }}" alt="Icono"
                                            style="width: 30px; height: 30px; object-fit: contain;">
                                        @else
                                        <span class="text-muted">{{ __('Sin icono') }}</span>
                                        @endif
                                    </td>
                                    {{-- FIN RENDERIZADO DEL ICONO --}}
                                    <td>{{ $caracteristica->nombre }}</td>
                                    <td>{{ $caracteristica->descripcion }}</td>
                                    <td>{{ $caracteristica->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        {{-- Botón Editar --}}
                                        <a href="{{ route('caracteristicas.edit', $caracteristica) }}"
                                            class="btn btn-sm btn-info me-2" title="{{ __('Editar') }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        {{-- Botón Eliminar (Formulario) --}}
                                        <form action="{{ route('caracteristicas.destroy', $caracteristica) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                title="{{ __('Eliminar') }}"
                                                onclick="return confirm('{{ __('¿Estás seguro de que deseas eliminar esta característica?') }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        {{ __('No se encontraron características registradas.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection