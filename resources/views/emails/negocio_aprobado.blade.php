<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
    /* Estilos básicos para asegurar compatibilidad en correos */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        line-height: 1.6;
    }

    .container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    .header {
        background-color: #198754;
        color: white;
        padding: 20px;
        text-align: center;
        border-radius: 8px 8px 0 0;
    }

    .content {
        padding: 20px;
    }

    .footer {
        text-align: center;
        font-size: 12px;
        color: #777;
        margin-top: 20px;
    }

    .btn {
        background-color: #0d6efd;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>¡Tu negocio ha sido aprobado!</h2>
        </div>
        <div class="content">
            <p>Hola, <strong>{{ $negocio->user->name }}</strong>.</p>
            <p>Nos complace informarte que tu negocio <strong>"{{ $negocio->nombre }}"</strong> ha pasado nuestra
                revisión y ya se encuentra activo en el directorio.</p>
            <p>A partir de ahora, los usuarios podrán encontrar tus servicios y productos en nuestra plataforma.</p>
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/negocios/' . $negocio->id) }}" class="btn" style="color: white;">Ver mi
                    publicación</a>
            </div>
            <p style="margin-top: 30px;">Gracias por confiar en nosotros.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Directorio NDYN. Todos los derechos reservados.</p>
        </div>
    </div>
</body>

</html>