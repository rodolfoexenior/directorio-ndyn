<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultados de Búsqueda - NDYN</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
    .business-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .business-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .img-container {
        height: 200px;
        background-color: #eee;
        overflow: hidden;
    }

    .img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .badge-category {
        background-color: #e7f1ff;
        color: #0d6efd;
        font-weight: 600;
    }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">NDYN</a>
            <form action="{{ route('front.buscar') }}" method="GET" class="d-flex ms-auto w-50">
                <input class="form-control me-2 rounded-pill" type="search" name="buscar" placeholder="Buscar otro..."
                    value="{{ request('buscar') }}">
                <button class="btn btn-primary rounded-pill" type="submit">Buscar</button>
            </form>
        </div>
    </nav>

    <div class="container mb-5">
        <h2 class="mb-4">Resultados para: <span class="text-primary">"{{ request('buscar') }}"</span></h2>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            @forelse($negocios as $negocio)
            <div class="col">
                <div class="card h-100 business-card shadow-sm">
                    <div class="img-container">
                        @if($negocio->imagenes->where('es_principal', true)->first())
                        <img src="{{ asset('storage/' . $negocio->imagenes->where('es_principal', true)->first()->ruta_archivo) }}"
                            alt="{{ $negocio->nombre }}">
                        @else
                        <div class="d-flex align-items-center justify-content-center h-100 bg-secondary text-white">
                            <i class="bi bi-shop fs-1"></i>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge badge-category px-3 py-2 rounded-pill small">
                                {{ $negocio->categoria }}
                            </span>
                            @if($negocio->es_destacado)
                            <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Destacado</span>
                            @endif
                        </div>
                        <h5 class="card-title fw-bold">{{ $negocio->nombre }}</h5>
                        <p class="card-text text-muted small">
                            <i class="bi bi-geo-alt-fill text-danger"></i>
                            {{ $negocio->contacto->direccion_manual ?? 'Ubicación no disponible' }}
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0 pb-3">
                        <a href="{{ route('negocios.show', $negocio->id) }}"
                            class="btn btn-outline-primary w-100 rounded-pill">Ver detalles</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-search fs-1 text-muted"></i>
                <h4 class="mt-3">No encontramos resultados para tu búsqueda</h4>
                <p class="text-muted">Intenta con otras palabras o revisa la ciudad.</p>
                <a href="/" class="btn btn-primary mt-3">Volver al inicio</a>
            </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $negocios->appends(request()->input())->links() }}
        </div>
    </div>

</body>

</html>