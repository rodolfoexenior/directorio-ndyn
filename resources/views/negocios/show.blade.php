@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>{{ __('Detalles del Negocio: ') . $negocio->nombre }}</div>
                    <a href="{{ route('negocios.index') }}" class="btn btn-sm btn-secondary">
                        {{ __('Volver al Listado') }}
                    </a>
                </div>

                <div class="card-body">

                    {{-- SECCIÓN 1: DATOS PRINCIPALES --}}
                    <h5 class="mb-3 mt-3 text-primary">Información General</h5>
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 25%;">ID Único:</th>
                                <td>{{ $negocio->id }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Categoría:</th>
                                <td>{{ $negocio->categoria }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Descripción:</th>
                                <td>{{ $negocio->descripcion }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Estado:</th>
                                <td>
                                    @if($negocio->estado == 'aprobado')
                                    <span class="badge bg-success">Aprobado</span>
                                    @elseif($negocio->estado == 'pendiente')
                                    <span class="badge bg-warning text-dark">Pendiente de Aprobación</span>
                                    @else
                                    <span class="badge bg-danger">Rechazado</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Es Emprendimiento:</th>
                                <td>{{ $negocio->es_emprendimiento ? 'Sí' : 'No' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>

                    {{-- SECCIÓN 2: DATOS DE CONTACTO --}}
                    <h5 class="mb-3 mt-4 text-primary">Información de Contacto</h5>

                    @if($negocio->contacto)
                    <table class="table table-sm table-borderless">
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 25%;">Teléfono:</th>
                                <td>{{ $negocio->contacto->telefono }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Email de Contacto:</th>
                                <td>{{ $negocio->contacto->email_contacto ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Página Web/Red:</th>
                                <td>
                                    @if($negocio->contacto->web)
                                    <a href="{{ $negocio->contacto->web }}"
                                        target="_blank">{{ $negocio->contacto->web }}</a>
                                    @else
                                    N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Dirección Manual:</th>
                                <td>{{ $negocio->contacto->direccion_manual }}</td>

                            </tr>
                            <tr>
                                <th scope="row">{{ __('Ubicación en Mapa') }}</th>
                                <td>
                                    <div class="col-md-6">
                                        {{-- Contenedor del mapa (Solo lectura) --}}
                                        <div id="mapa-detalle"
                                            style="height: 300px; width: 100%; border: 1px solid #dee2e6; border-radius: 0.375rem;">
                                        </div>
                                    </div>
                                    <a id="btn-google-maps" href="#" target="_blank"
                                        class="btn btn-success w-100 mt-2 shadow-sm">
                                        <i class="bi bi-cursor-fill"></i> {{ __('Ver Cómo Llegar (Google Maps)') }}
                                    </a>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                    @else
                    <p class="alert alert-warning">No se encontró información de contacto asociada a este negocio.</p>
                    @endif

                    <div class="mt-4 text-end">
                        {{-- Botón para ir a completar la información adicional --}}
                        <a href="{{ route('negocios.completar', $negocio->id) }}" class="btn btn-warning me-2">
                            {{ __('Completar') }}
                        </a>
                        <a href="{{ route('negocios.edit', $negocio->id) }}" class="btn btn-primary">
                            {{ __('Editar Información') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Usamos Number() para asegurar que Laravel pase el dato como número puro
    var lat = Number("{{ $negocio->contacto->latitud ?? 0 }}");
    var lng = Number("{{ $negocio->contacto->longitud ?? 0 }}");

    console.log("Coordenadas detectadas:", lat, lng); // Esto es para revisar con F12

    var mapaContenedor = document.getElementById('mapa-detalle');

    if (lat !== 0 && lng !== 0) {
        // Inicializar mapa
        var map = L.map('mapa-detalle').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Añadir marcador
        L.marker([lat, lng]).addTo(map)
            .bindPopup("<b>{{ $negocio->nombre }}</b>")
            .openPopup();


        // Construir la URL universal de Google Maps
        var googleUrl = "https://www.google.com/maps/dir/?api=1&destination=" + lat + "," + lng;

        // Asignar la URL al botón verde
        var btnGoogle = document.getElementById('btn-google-maps');
        if (btnGoogle) {
            btnGoogle.href = googleUrl;
        }

        // Arreglo para problemas de carga en pestañas o tablas
        setTimeout(function() {
            map.invalidateSize();
        }, 300);
    } else {
        mapaContenedor.innerHTML =
            '<div class="p-4 text-center text-muted">No hay coordenadas válidas para este negocio.</div>';
    }
});
</script>
@endpush