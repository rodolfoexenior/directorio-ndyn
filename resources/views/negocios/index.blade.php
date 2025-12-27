@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>{{ __('Mis Negocios Registrados') }}</div>
                    <a href="{{ route('negocios.create') }}" class="btn btn-sm btn-success">
                        {{ __('+ Nuevo Negocio') }}
                    </a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    @if($negocios->isEmpty())
                    <p class="text-center">Aún no has registrado ningún negocio. ¡Comienza ahora!</p>
                    @else

                    {{-- BLOQUE 1: TABLA TRADICIONAL (PC y Tablet) --}}
                    <div class="table-responsive d-none d-sm-block">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th scope="col"># ID</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Estado</th>
                                    <th>Fecha de Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($negocios as $negocio)
                                <tr>
                                    <th scope="row" class="text-muted">{{ $negocio->id }}</th>
                                    <td>{{ $negocio->nombre }}</td>
                                    <td>{{ $negocio->categoria }}</td>
                                    <td>
                                        @if($negocio->estado) {{-- Verificamos que la relación no sea nula --}}
                                        @if($negocio->estado->nombre == 'aprobado')
                                        <span class="badge bg-success">Aprobado</span>
                                        @elseif($negocio->estado->nombre == 'pendiente')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                        @else
                                        <span class="badge bg-danger">Eliminado</span>
                                        @endif
                                        @else
                                        <span class="badge bg-secondary">Sin Estado</span>
                                        @endif
                                    </td>
                                    <td>{{ $negocio->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('negocios.completar', $negocio->id) }}"
                                            class="btn btn-sm btn-warning me-2">{{ __('Completar') }}</a>
                                        <a href="{{ route('negocios.edit', $negocio->id) }}"
                                            class="btn btn-sm btn-primary">Editar</a>
                                        <a href="{{ route('negocios.show', $negocio->id) }}"
                                            class="btn btn-sm btn-info text-white me-2">Ver Detalle</a>
                                        <a href="{{ route('negocios.productos.index', $negocio) }}"
                                            class="btn btn-sm btn-success me-2" title="Gestionar Productos">
                                            <i class="fas fa-boxes"></i> Productos
                                        </a>
                                        <a href="{{ route('negocios.servicios.index', $negocio) }}"
                                            class="btn btn-sm btn-dark me-2" title="Gestionar Servicios">
                                            <i class="fas fa-concierge-bell"></i> Servicios
                                        </a>

                                        @if (Auth::user()->role === 'admin')
                                        <form action="{{ route('negocios.destroy', $negocio->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Estás seguro de eliminar el negocio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- BLOQUE 2: ACORDEÓN EXCLUSIVO (SOLO CELULAR) --}}
                    <div class="d-sm-none" id="negociosAcordeon">
                        @foreach($negocios as $negocio)
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center"
                                data-bs-toggle="collapse" data-bs-target="#collapseNegocio_{{ $negocio->id }}"
                                aria-expanded="false" aria-controls="collapseNegocio_{{ $negocio->id }}"
                                style="cursor: pointer;">

                                <div>
                                    <h5 class="card-title mb-0">{{ $negocio->nombre }}</h5>
                                    <p class="card-text text-muted small mb-0">{{ $negocio->categoria }}</p>
                                </div>

                                {{-- CAMBIO AQUÍ: Usamos la relación también para el móvil --}}
                                @if($negocio->estado->nombre == 'aprobado')
                                <span class="badge bg-success">Aprobado</span>
                                @elseif($negocio->estado->nombre == 'pendiente')
                                <span class="badge bg-warning text-dark">Pendiente</span>
                                @else
                                <span class="badge bg-danger">Eliminado</span>
                                @endif
                            </div>

                            <div class="collapse" id="collapseNegocio_{{ $negocio->id }}"
                                data-bs-parent="#negociosAcordeon">
                                <div class="card-body">
                                    <h6 class="text-primary">{{ __('Detalles y Acciones') }}</h6>
                                    <hr class="my-2">
                                    <div class="mb-3">
                                        <strong>Fecha de Registro:</strong>
                                        <span class="text-muted">{{ $negocio->created_at->format('d/m/Y') }}</span>
                                    </div>

                                    <div class="d-grid gap-2 d-md-block">
                                        <a href="{{ route('negocios.completar', $negocio->id) }}"
                                            class="btn btn-warning btn-sm w-100 mb-1">{{ __('Completar') }}</a>
                                        <a href="{{ route('negocios.edit', $negocio->id) }}"
                                            class="btn btn-primary btn-sm w-100 mb-1">Editar</a>
                                        <a href="{{ route('negocios.show', $negocio->id) }}"
                                            class="btn btn-info btn-sm w-100 text-white mb-1">Ver Detalle</a>
                                        <a href="{{ route('negocios.productos.index', $negocio) }}"
                                            class="btn btn-success btn-sm w-100 mb-1"><i class="fas fa-boxes"></i>
                                            {{ __('Productos') }}</a>
                                        <a href="{{ route('negocios.servicios.index', $negocio) }}"
                                            class="btn btn-dark btn-sm w-100 mb-1"><i class="fas fa-concierge-bell"></i>
                                            {{ __('Servicios') }}</a>

                                        @if (Auth::user()->role === 'admin')
                                        <form action="{{ route('negocios.destroy', $negocio->id) }}" method="POST"
                                            class="d-inline w-100"
                                            onsubmit="return confirm('¿Estás seguro de eliminar el negocio?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger btn-sm w-100 mt-1">Eliminar</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection