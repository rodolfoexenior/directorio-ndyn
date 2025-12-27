@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h2>{{ __('Completar Detalles de: ') . $negocio->nombre }}</h2>
                </div>
                <div class="card-body">
                    <p class="mb-4">
                        {{ __('Añade palabras clave, selecciona características y sube fotos para completar el perfil de tu negocio.') }}
                    </p>

                    {{-- Formulario Principal con soporte para Archivos (enctype) --}}
                    {{-- NOTA: El formulario ahora enviará el texto y la foto en una sola solicitud. --}}
                    <form method="POST" action="{{ route('negocios.guardar-completar', $negocio->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- ================================================= --}}
                        {{-- SECCIÓN 1: PALABRAS CLAVE (SEO) --}}
                        {{-- ================================================= --}}
                        <h4 class="mt-4 mb-3 text-secondary">{{ __('1. Palabras Clave y Tags') }}</h4>
                        <div class="mb-3">
                            <label for="palabras_clave"
                                class="form-label">{{ __('Palabras Clave (Separadas por coma)') }}</label>
                            <textarea id="palabras_clave"
                                class="form-control @error('palabras_clave') is-invalid @enderror" name="palabras_clave"
                                rows="3">{{ old('palabras_clave', $negocio->palabras_clave) }}</textarea>
                            <div class="form-text">Ej: Cafetería, Restaurante, Desayunos, Comida Vegana.</div>
                            @error('palabras_clave')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <hr class="mt-4 mb-4">

                        {{-- ================================================= --}}
                        {{-- SECCIÓN 2: CARACTERÍSTICAS (Relación M:M) --}}
                        {{-- ================================================= --}}
                        <h4 class="mt-4 mb-3 text-secondary">{{ __('2. Características del Lugar') }}</h4>
                        <div class="row mb-3">
                            @forelse ($caracteristicas as $caracteristica)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    {{-- El array 'caracteristicas_ids[]' permitirá recibir múltiples valores --}}
                                    <input class="form-check-input" type="checkbox" name="caracteristicas_ids[]"
                                        value="{{ $caracteristica->id }}" id="caracteristica_{{ $caracteristica->id }}"
                                        {{ in_array($caracteristica->id, old('caracteristicas_ids', $caracteristicasActuales ?? [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="caracteristica_{{ $caracteristica->id }}">
                                        {{ $caracteristica->nombre }}
                                    </label>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted">{{ __('No hay características definidas en el sistema.') }}</p>
                            @endforelse

                            @error('caracteristicas_ids')
                            <div class="text-danger mt-2">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>

                        <hr class="mt-4 mb-4">

                        {{-- ================================================= --}}
                        {{-- SECCIÓN 3: SUBIDA DE FOTOS (NUEVO: UNO A UNO) --}}
                        {{-- ================================================= --}}
                        <h4 class="mt-4 mb-3 text-secondary">{{ __('3. Galería de Fotos') }}</h4>

                        {{-- Obtenemos el conteo actual de fotos --}}
                        @php
                        $fotosActuales = $negocio->imagenes->count();
                        $limite = $negocio->limite_fotos ?? 5; // Usamos la nueva columna o 5 por defecto
                        @endphp

                        <p class="text-info">
                            Fotos subidas: **{{ $fotosActuales }}** / **{{ $limite }}**
                        </p>

                        @if ($fotosActuales < $limite) <div class="mb-3 border p-3 rounded bg-light">
                            <label for="foto" class="form-label d-block fw-bold">
                                {{ __('Subir Nueva Foto (Sube una por vez)') }}
                            </label>

                            {{-- CLAVE: Input ahora es singular: name="foto" y sin 'multiple' --}}
                            <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto"
                                name="foto" accept="image/jpeg,image/png,image/jpg">

                            <div class="form-text">
                                Formatos permitidos: jpg, jpeg, png. Tamaño máximo sugerido: 2MB.
                            </div>

                            {{-- El error ahora se verifica en 'foto', no en 'fotos.*' --}}
                            @error('foto')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                </div>
                @else
                <div class="alert alert-success">
                    {{ __('¡Has alcanzado el límite máximo de ') . $limite . __(' fotos para este negocio!') }}
                </div>
                @endif

                {{-- Sección para mostrar las fotos actuales (Opcional, pero muy recomendado) --}}
                @if ($fotosActuales > 0)
                <h5 class="mt-4">{{ __('Fotos Actuales:') }}</h5>
                <div class="row">
                    @foreach ($negocio->imagenes as $imagen)
                    <div class="col-4 col-md-2 mb-3">
                        {{-- Asegúrate de que tienes un asset para Storage::url --}}
                        <img src="{{ Storage::url($imagen->ruta_archivo) }}" class="img-fluid rounded shadow"
                            alt="Foto de {{ $negocio->nombre }}">
                        @if ($imagen->es_principal)
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-1">Principal</span>
                        @endif
                        {{-- Aquí se podría añadir un botón de eliminar o cambiar principal --}}
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="d-grid gap-2 mt-5">
                    <button type="submit" class="btn btn-warning btn-lg">
                        {{ __('Guardar y Completar Perfil') }}
                    </button>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection