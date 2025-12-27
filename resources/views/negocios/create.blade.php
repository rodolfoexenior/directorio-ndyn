@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Registro de Nuevo Negocio') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('negocios.store') }}">
                        @csrf

                        {{-- =================================== --}}
                        {{-- SECCIÓN 1: INFORMACIÓN BÁSICA (NEGOCIO) --}}
                        {{-- =================================== --}}
                        <h5 class="mb-3 mt-3 text-primary">1. Datos Principales del Negocio</h5>

                        <div class="row mb-3">
                            <label for="nombre"
                                class="col-md-4 col-form-label text-md-end">{{ __('Nombre del Negocio') }}</label>
                            <div class="col-md-6">
                                <input id="nombre" type="text"
                                    class="form-control @error('nombre') is-invalid @enderror" name="nombre"
                                    value="{{ old('nombre') }}" required autofocus>
                                @error('nombre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="descripcion"
                                class="col-md-4 col-form-label text-md-end">{{ __('Descripción Corta') }}</label>
                            <div class="col-md-6">
                                <textarea id="descripcion"
                                    class="form-control @error('descripcion') is-invalid @enderror" name="descripcion"
                                    required>{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="categoria"
                                class="col-md-4 col-form-label text-md-end">{{ __('Categoría Principal') }}</label>
                            <div class="col-md-6">
                                <select id="categoria" class="form-select @error('categoria') is-invalid @enderror"
                                    name="categoria" required>
                                    <option value="" disabled selected>Seleccione una categoría</option>
                                    {{-- Opciones dinámicas cargadas desde la base de datos --}}
                                    @foreach($categorias as $cat)
                                    <option value="{{ $cat->nombre }}"
                                        {{ old('categoria') == $cat->nombre ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="es_emprendimiento"
                                        id="es_emprendimiento" {{ old('es_emprendimiento') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="es_emprendimiento">
                                        {{ __('¿Es un emprendimiento local?') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- =================================== --}}
                        {{-- SECCIÓN 2: INFORMACIÓN DE CONTACTO (NEGOCIO_CONTACTO) --}}
                        {{-- =================================== --}}
                        <h5 class="mb-3 mt-4 text-primary">2. Información de Contacto y Ubicación</h5>

                        <div class="row mb-3">
                            <label for="telefono"
                                class="col-md-4 col-form-label text-md-end">{{ __('Teléfono') }}</label>
                            <div class="col-md-6">
                                <input id="telefono" type="text"
                                    class="form-control @error('telefono') is-invalid @enderror" name="telefono"
                                    value="{{ old('telefono') }}" required>
                                @error('telefono')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email_contacto"
                                class="col-md-4 col-form-label text-md-end">{{ __('Email de Contacto') }}</label>
                            <div class="col-md-6">
                                <input id="email_contacto" type="email"
                                    class="form-control @error('email_contacto') is-invalid @enderror"
                                    name="email_contacto" value="{{ old('email_contacto') }}">
                                @error('email_contacto')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="web"
                                class="col-md-4 col-form-label text-md-end">{{ __('Página Web/Red Social') }}</label>
                            <div class="col-md-6">
                                <input id="web" type="url" class="form-control @error('web') is-invalid @enderror"
                                    name="web" value="{{ old('web') }}"
                                    placeholder="Ej: http://www.miempresa.com o https://facebook.com/miempresa">
                                @error('web')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="direccion_manual"
                                class="col-md-4 col-form-label text-md-end">{{ __('Dirección Completa') }}</label>
                            <div class="col-md-6">
                                <textarea id="direccion_manual"
                                    class="form-control @error('direccion_manual') is-invalid @enderror"
                                    name="direccion_manual" required>{{ old('direccion_manual') }}</textarea>
                                @error('direccion_manual')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Campos ocultos con ID para que el sistema los encuentre después --}}
                        <input type="hidden" name="latitud" id="latitud" value="0">
                        <input type="hidden" name="longitud" id="longitud" value="0">
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                {{-- Botón mejorado: Color primario y mejor ubicado --}}
                                <button type="button" id="btn-gps" class="btn btn-primary btn-sm w-100 mb-2">
                                    <i class="bi bi-geo-alt-fill"></i> Usar mi ubicación actual (GPS)
                                </button>

                                <div id="mapa-registro"
                                    style="height: 300px; width: 100%; border: 1px solid #dee2e6; border-radius: 0.375rem;">
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="bi bi-info-circle"></i> Puedes mover el marcador en el mapa para mayor
                                    precisión.
                                </small>
                            </div>
                        </div>


                        {{-- =================================== --}}
                        {{-- BOTÓN DE ENVÍO --}}
                        {{-- =================================== --}}
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrar Negocio') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Coordenadas iniciales (Por defecto: Santa Cruz, Bolivia. Puedes ajustarlas luego)
    var latInicial = -17.7833;
    var lngInicial = -63.1821;

    // 2. Crear el mapa en el contenedor 'mapa-registro'
    var map = L.map('mapa-registro').setView([latInicial, lngInicial], 16);

    // 3. Añadir la capa de diseño del mapa (OpenStreetMap)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // 4. Crear el Marcador (Pin) movible
    var marker = L.marker([latInicial, lngInicial], {
        draggable: true // Esto permite que el usuario lo arrastre
    }).addTo(map);

    // Forzar que el mapa se vea bien al cargar (ajuste de tamaño)
    setTimeout(function() {
        map.invalidateSize();
    }, 400);

    // Función para actualizar los campos ocultos cuando el marcador se mueve
    /*
    function actualizarCampos(lat, lng) {
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
    }
        */
    // 1. Nueva función con búsqueda de dirección
    function actualizarCampos(lat, lng) {
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;

        // Info Cruzada: Buscar dirección basada en coordenadas
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    // Esto llena automáticamente tu campo "Dirección Completa"
                    document.getElementById('direccion_manual').value = data.display_name;
                }
            })
            .catch(error => console.log('Error en info cruzada:', error));
    }

    // 1. Ejecutar al cargar para que no se envíe en 0
    actualizarCampos(latInicial, lngInicial);

    // 2. Escuchar cuando el usuario termina de arrastrar el marcador
    /*
    marker.on('dragend', function(event) {
        var posicion = marker.getLatLng();
        actualizarCampos(posicion.lat, posicion.lng);
    });
    */
    // 2. Asegúrate de que el evento dragend llame a esta nueva lógica
    marker.on('dragend', function(event) {
        var posicion = marker.getLatLng();
        actualizarCampos(posicion.lat, posicion.lng);
    });

    // 3. (Opcional) Si el usuario hace clic en cualquier parte del mapa, mover el pin ahí
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        actualizarCampos(e.latlng.lat, e.latlng.lng);
    });

    // Escuchar cuando el usuario termina de escribir la dirección (al salir del campo)
    document.getElementById('direccion_manual').addEventListener('change', function() {
        var direccion = this.value;

        if (direccion.length > 5) { // Solo buscar si hay texto suficiente
            fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(direccion)}`
                )
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        var nuevaLat = data[0].lat;
                        var nuevaLng = data[0].lon;

                        // 1. Mover el mapa y el Pin a la ubicación encontrada
                        map.setView([nuevaLat, nuevaLng], 16);
                        marker.setLatLng([nuevaLat, nuevaLng]);

                        // 2. Actualizar los campos ocultos de latitud y longitud
                        document.getElementById('latitud').value = nuevaLat;
                        document.getElementById('longitud').value = nuevaLng;
                    }
                })
                .catch(error => console.log('Error al buscar dirección:', error));
        }
    });

    document.getElementById('btn-gps').addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert("Tu navegador no soporta geolocalización.");
            return;
        }

        // Cambiar el texto del botón para dar feedback
        this.innerText = "Localizando...";

        navigator.geolocation.getCurrentPosition((position) => {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            // 1. Mover mapa y pin
            map.setView([lat, lng], 17); // Zoom más cercano para GPS
            marker.setLatLng([lat, lng]);

            // 2. Actualizar campos y buscar dirección
            actualizarCampos(lat, lng);

            this.innerHTML = '<i class="bi bi-geo-alt"></i> Ubicación encontrada';
        }, (error) => {
            console.error(error);
            alert(
                "Nota: El GPS suele requerir una conexión segura (HTTPS) o permisos del navegador. Si estás en PC local, intenta mover el pin manualmente."
                );
            this.innerHTML = '<i class="bi bi-geo-alt-fill"></i> Reintentar GPS';
            this.classList.replace('btn-primary', 'btn-warning'); // Cambia a amarillo si falla
        });
    });

}); /*cierre del DOMContentLoaded */
</script>

@endpush