@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.negocios.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                <i class="bi bi-arrow-left"></i> Volver al Listado Maestro
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

            {{-- Productos y Servicios --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Productos y Servicios</div>
                <div class="card-body">
                    <h6>Productos ({{ $negocio->productos->count() }})</h6>
                    <ul class="list-group mb-3">
                        @foreach($negocio->productos as $producto)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $producto->nombre }}
                            <span
                                class="badge bg-primary rounded-pill">${{ number_format($producto->precio, 2) }}</span>
                        </li>
                        @endforeach
                    </ul>

                    <h6>Servicios ({{ $negocio->servicios->count() }})</h6>
                    <ul class="list-group">
                        @foreach($negocio->servicios as $servicio)
                        <li class="list-group-item">{{ $servicio->nombre }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Datos del Dueño y Contacto --}}
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 border-info">
                <div class="card-header bg-info text-white fw-bold">Datos del Propietario</div>
                <div class="card-body">
                    <p><strong>Nombre:</strong> {{ $negocio->user->name }}</p>
                    <p><strong>Email:</strong> {{ $negocio->user->email }}</p>
                    <p><strong>ID de Usuario:</strong> {{ $negocio->user->id }}</p>
                </div>
            </div>

            <div class="card shadow-sm border-success">
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
        </div>
    </div>
</div>
@endsection