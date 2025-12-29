@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.negocios.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="bi bi-arrow-left"></i> Volver al Listado Maestro
            </a>

            <a href="{{ route('negocios.show', $negocio->id) }}" target="_blank"
                class="btn btn-outline-primary btn-sm mb-2">
                <i class="bi bi-box-arrow-up-right"></i> Ver Vista Pública
            </a>
            <h2 class="text-secondary">Detalle del Negocio: <span class="text-primary">{{ $negocio->nombre }}</span>
            </h2>
        </div>
        <div>
            <form action="{{ route('admin.negocios.updateEstado', $negocio->id) }}" method="POST"
                class="d-flex align-items-center">
                @csrf
                @method('PATCH')
                <label class="me-2 fw-bold text-secondary">Cambiar Estado:</label>
                <select name="estado_id" class="form-select form-select-sm me-2" onchange="this.form.submit()"
                    style="width: auto;">
                    <option value="1" {{ $negocio->estado_id == 1 ? 'selected' : '' }}>Pendiente</option>
                    <option value="2" {{ $negocio->estado_id == 2 ? 'selected' : '' }}>Aprobado</option>
                </select>
            </form>
        </div>
    </div>

    <div class="row">
        {{-- Columna Izquierda: Información General --}}
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Información General</div>
                <div class="card-body">
                    <p><strong>Descripción:</strong><br>{{ $negocio->descripcion ?? 'Sin descripción' }}</p>
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Categoría:</strong> {{ $negocio->categoria }}</p>
                            <p><strong>¿Es Emprendimiento?:</strong> {{ $negocio->es_emprendimiento ? 'Sí' : 'No' }}</p>
                        </div>
                        <div class="col-6">
                            <p><strong>Palabras Clave:</strong> {{ $negocio->palabras_clave ?? 'Ninguna' }}</p>
                            <p><strong>Fecha de Registro:</strong> {{ $negocio->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Productos y Servicios ajustados a la BD real --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Contenido del Negocio</div>
                <div class="card-body">

                    <h6 class="text-muted mb-3">Productos registrados ({{ $negocio->productos->count() }})</h6>
                    <div class="list-group mb-4">
                        @forelse($negocio->productos as $producto)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div style="flex: 1;">
                                    <span class="fw-bold text-dark">{{ $producto->nombre_producto }}</span>
                                    @if($producto->descripcion_producto)
                                    <br>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 400px;">
                                        {{ $producto->descripcion_producto }}
                                    </small>
                                    @endif
                                </div>
                                <span class="badge bg-primary">${{ number_format($producto->precio, 2) }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted small ps-3">No hay productos registrados.</p>
                        @endforelse
                    </div>

                    <h6 class="text-muted mb-3">Servicios registrados ({{ $negocio->servicios->count() }})</h6>
                    <div class="list-group">
                        @forelse($negocio->servicios as $servicio)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div style="flex: 1;">
                                    {{-- Ajustado asumiendo que servicios sigue la misma lógica: nombre_servicio --}}
                                    <span class="fw-bold">{{ $servicio->nombre_servicio ?? $servicio->nombre }}</span>
                                    @if(isset($servicio->descripcion_servicio))
                                    <br>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 400px;">
                                        {{ $servicio->descripcion_servicio }}
                                    </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted small ps-3">No hay servicios registrados.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Datos del Dueño y Contacto --}}
        <div class="col-md-4">
            {{-- Datos del Propietario --}}
            <div class="card shadow-sm mb-4 border-info">
                <div class="card-header bg-info text-white fw-bold">Datos del Propietario</div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> {{ $negocio->user->name }}</p>
                    <p><strong>Email:</strong> {{ $negocio->user->email }}</p>
                    <p><strong>ID de Usuario:</strong> {{ $negocio->user->id }}</p>
                </div>
            </div>

            {{-- Datos de Contacto (AQUÍ AÑADIMOS mb-4) --}}
            <div class="card shadow-sm mb-4 border-success">
                <div class="card-header bg-success text-white fw-bold">Datos de Contacto</div>
                <div class="card-body">
                    @if($negocio->contacto)
                    <p><strong>Teléfono:</strong> {{ $negocio->contacto->telefono }}</p>
                    <p><strong>Dirección:</strong> {{ $negocio->contacto->direccion }}</p>
                    <p><strong>WhatsApp:</strong> {{ $negocio->contacto->whatsapp ?? 'No proveído' }}</p>
                    @else
                    <p class="text-muted">No hay datos de contacto registrados.</p>
                    @endif
                </div>
            </div>

            {{-- Bloque de Características --}}
            <div class="card shadow-sm mb-4 border-warning">
                <div class="card-header bg-warning text-dark fw-bold">Características / Amenidades</div>
                <div class="card-body">
                    @forelse($negocio->caracteristicas as $caracteristica)
                    <span class="badge rounded-pill bg-light text-dark border mb-2 p-2">
                        <i class="bi bi-check2-circle text-success me-1"></i> {{ $caracteristica->nombre }}
                    </span>
                    @empty
                    <p class="text-muted small">No se han marcado características.</p>
                    @endforelse
                </div>
            </div>

            {{-- Bloque de Horarios --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white fw-bold">Horarios de Atención</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Día</th>
                                <th>Apertura</th>
                                <th>Cierre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($negocio->horarios as $horario)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $horario->dia }}</td>
                                <td>{{ \Carbon\Carbon::parse($horario->hora_apertura)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($horario->hora_cierre)->format('H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted small">No hay horarios registrados.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection