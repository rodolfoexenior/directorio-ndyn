@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Editar Negocio: ') . $negocio->nombre }}</span>
                    <a href="{{ route('negocios.index') }}" class="btn btn-sm btn-secondary">{{ __('Volver') }}</a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('negocios.update', $negocio->id) }}">
                        @csrf
                        @method('PUT')

                        <h5 class="mb-3 mt-3 text-primary">1. Datos Principales del Negocio</h5>

                        <div class="row mb-3">
                            <label for="nombre"
                                class="col-md-4 col-form-label text-md-end">{{ __('Nombre del Negocio') }}</label>
                            <div class="col-md-6">
                                <input id="nombre" type="text"
                                    class="form-control @error('nombre') is-invalid @enderror" name="nombre"
                                    value="{{ old('nombre', $negocio->nombre) }}" required autofocus>
                                @error('nombre')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="descripcion"
                                class="col-md-4 col-form-label text-md-end">{{ __('Descripción Corta') }}</label>
                            <div class="col-md-6">
                                <textarea id="descripcion"
                                    class="form-control @error('descripcion') is-invalid @enderror" name="descripcion"
                                    required>{{ old('descripcion', $negocio->descripcion) }}</textarea>
                                @error('descripcion')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="categoria"
                                class="col-md-4 col-form-label text-md-end">{{ __('Categoría Principal') }}</label>
                            <div class="col-md-6">
                                <select id="categoria" class="form-select @error('categoria') is-invalid @enderror"
                                    name="categoria" required>
                                    @foreach($categorias as $cat)
                                    <option value="{{ $cat->nombre }}"
                                        {{ old('categoria', $negocio->categoria) == $cat->nombre ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="es_emprendimiento"
                                        id="es_emprendimiento"
                                        {{ old('es_emprendimiento', $negocio->es_emprendimiento) ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="es_emprendimiento">{{ __('¿Es un emprendimiento local?') }}</label>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4 text-primary">2. Información de Contacto y Ubicación</h5>

                        <div class="row mb-3">
                            <label for="telefono"
                                class="col-md-4 col-form-label text-md-end">{{ __('Teléfono') }}</label>
                            <div class="col-md-6">
                                <input id="telefono" type="text"
                                    class="form-control @error('telefono') is-invalid @enderror" name="telefono"
                                    value="{{ old('telefono', $negocio->contacto->telefono) }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email_contacto"
                                class="col-md-4 col-form-label text-md-end">{{ __('Email de Contacto') }}</label>
                            <div class="col-md-6">
                                <input id="email_contacto" type="email"
                                    class="form-control @error('email_contacto') is-invalid @enderror"
                                    name="email_contacto"
                                    value="{{ old('email_contacto', $negocio->contacto->email_contacto) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="web"
                                class="col-md-4 col-form-label text-md-end">{{ __('Página Web/Red Social') }}</label>
                            <div class="col-md-6">
                                <input id="web" type="url" class="form-control @error('web') is-invalid @enderror"
                                    name="web" value="{{ old('web', $negocio->contacto->web) }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="direccion_manual"
                                class="col-md-4 col-form-label text-md-end">{{ __('Dirección Completa') }}</label>
                            <div class="col-md-6">
                                <textarea id="direccion_manual"
                                    class="form-control @error('direccion_manual') is-invalid @enderror"
                                    name="direccion_manual"
                                    required>{{ old('direccion_manual', $negocio->contacto->direccion_manual) }}</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <input type="hidden" name="latitud" id="latitud"
                                    value="{{ old('latitud', $negocio->contacto->latitud) }}">
                                <input type="hidden" name="longitud" id="longitud"
                                    value="{{ old('longitud', $negocio->contacto->longitud) }}">

                                <button type="button" id="btn-gps" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                    <i class="bi bi-geo-alt-fill"></i> Usar mi ubicación actual (GPS)
                                </button>

                                <div id="mapa-registro"
                                    style="height: 300px; width: 100%; border: 1px solid #dee2e6; border-radius: 0.375rem;">
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle"></i> Arrastra el
                                    marcador si deseas cambiar la ubicación.</small>
                            </div>
                        </div>

                        {{-- Usamos la relación roles para verificar el nombre del primero --}}
                        @if(Auth::user()->roles->isNotEmpty() && Auth::user()->roles->first()->nombre ===
                        'admin_principal')
                        <div class="row mb-4">
                            <div class="col-md-6 offset-md-4">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark py-1">
                                        <strong>{{ __('Moderación de Estado') }}</strong>
                                    </div>
                                    <div class="card-body bg-light">
                                        <label for="estado_id"
                                            class="form-label small text-muted">{{ __('Cambiar estado del negocio:') }}</label>
                                        <select id="estado_id"
                                            class="form-select @error('estado_id') is-invalid @enderror"
                                            name="estado_id">
                                            @foreach($estados as $estado)
                                            <option value="{{ $estado->id }}"
                                                {{ old('estado_id', $negocio->estado_id) == $estado->id ? 'selected' : '' }}>
                                                {{ ucfirst($estado->nombre) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success w-100">
                                    {{ __('Guardar Cambios') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div> {{-- Cierre card-body --}}
            </div> {{-- Cierre card --}}
        </div> {{-- Cierre col --}}
    </div> {{-- Cierre row --}}
</div> {{-- Cierre container --}}
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var latGuardada = Number(document.getElementById('latitud').value) || -17.7833;
    var lngGuardada = Number(document.getElementById('longitud').value) || -63.1821;

    var map = L.map('mapa-registro').setView([latGuardada, lngGuardada], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    var marker = L.marker([latGuardada, lngGuardada], {
        draggable: true
    }).addTo(map);

    function actualizarCampos(lat, lng) {
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    document.getElementById('direccion_manual').value = data.display_name;
                }
            });
    }

    marker.on('dragend', function() {
        var pos = marker.getLatLng();
        actualizarCampos(pos.lat, pos.lng);
    });

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        actualizarCampos(e.latlng.lat, e.latlng.lng);
    });

    document.getElementById('btn-gps').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                map.setView([lat, lng], 17);
                marker.setLatLng([lat, lng]);
                actualizarCampos(lat, lng);
            });
        }
    });

    setTimeout(() => {
        map.invalidateSize();
    }, 400);
});
</script>
@endpush