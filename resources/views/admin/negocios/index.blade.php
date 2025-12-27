@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-secondary">{{ __('Panel Maestro: Todos los Negocios') }}</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Nombre del Negocio</th>
                        <th>Propietario</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($negocios as $negocio)
                    <tr @if($negocio->estado_id == 1) class="table-warning" @endif>
                        <td class="ps-3 text-muted fw-bold">{{ $negocio->id }}</td>
                        <td>
                            <span class="fw-bold text-primary">{{ $negocio->nombre }}</span>
                        </td>
                        <td>
                            <small>{{ $negocio->user->name ?? 'Sin dueño' }}</small>
                        </td>
                        <td>{{ $negocio->categoria }}</td>
                        <td>
                            @if($negocio->estado_id == 1)
                            <span class="badge bg-warning text-dark">Pendiente</span>
                            @elseif($negocio->estado_id == 2)
                            <span class="badge bg-success">Aprobado</span>
                            @endif
                        </td>
                        <td>{{ $negocio->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.negocios.show', $negocio->id) }}"
                                class="btn btn-sm btn-info text-white" title="Ver Detalles">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            {{-- Aquí más adelante agregaremos botones para Aprobar/Rechazar directamente --}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            No hay negocios registrados actualmente.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection