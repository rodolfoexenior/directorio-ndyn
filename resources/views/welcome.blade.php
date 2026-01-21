<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NDYN - Encuentra lo que necesitas, día y noche</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,600,700" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
    body {
        font-family: 'Instrument Sans', sans-serif;
        background-color: #f8f9fa;
    }

    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1374&q=80');
        background-size: cover;
        background-position: center;
        height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-align: center;
    }

    .search-container {
        background: white;
        padding: 10px;
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        max-width: 800px;
        width: 100%;
    }

    .search-input {
        border: none;
        padding: 15px 25px;
        width: 100%;
    }

    .search-input:focus {
        outline: none;
    }

    .btn-search {
        border-radius: 50px;
        padding: 12px 35px;
        background-color: #0d6efd;
        font-weight: 600;
    }

    .category-icon {
        font-size: 2rem;
        color: #0d6efd;
        margin-bottom: 10px;
        transition: transform 0.3s;
    }

    .category-card:hover .category-icon {
        transform: translateY(-5px);
    }

    .category-card {
        border: none;
        background: transparent;
        cursor: pointer;
    }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">NDYN</a>
            <div class="ms-auto">
                @if (Route::has('login'))
                <div class="d-flex gap-3">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-light btn-sm">Panel Control</a>
                    @else
                    <a href="{{ route('login') }}"
                        class="text-light text-decoration-none small align-self-center">Entrar</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Registrar Negocio</a>
                    @endif
                    @endauth
                </div>
                @endif
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">Lo que buscas, día y noche</h1>
            <p class="lead mb-5">Encuentra los mejores negocios y servicios en tu ciudad.</p>

            <div class="search-container mx-auto">
                <form action="{{ route('front.buscar') }}" method="GET" class="row g-0 align-items-center">
                    <div class="col-md-5 border-end">
                        <input type="text" name="buscar" class="search-input"
                            placeholder="¿Qué buscas? (Ej: Pizza, Abogado)">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="ciudad" class="search-input" placeholder="¿En qué ciudad?">
                    </div>
                    <div class="col-md-3 p-1">
                        <button type="submit" class="btn btn-primary btn-search w-100">
                            <i class="bi bi-search me-2"></i>Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <section class="py-5 container text-center">
        <h2 class="mb-5 fw-semibold">Explora categorías populares</h2>
        <div class="row row-cols-2 row-cols-md-4 g-4">
            <div class="col">
                <div class="category-card p-3">
                    <i class="bi bi-shop category-icon"></i>
                    <h5>Comercios</h5>
                </div>
            </div>
            <div class="col">
                <div class="category-card p-3">
                    <i class="bi bi-cup-hot category-icon"></i>
                    <h5>Restaurantes</h5>
                </div>
            </div>
            <div class="col">
                <div class="category-card p-3">
                    <i class="bi bi-tools category-icon"></i>
                    <h5>Servicios</h5>
                </div>
            </div>
            <div class="col">
                <div class="category-card p-3">
                    <i class="bi bi-hospital category-icon"></i>
                    <h5>Salud</h5>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} NDYN - Directorio Profesional. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>