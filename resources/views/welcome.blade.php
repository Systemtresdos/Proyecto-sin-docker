<title>Laravel</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="[https://fonts.bunny.net](https://fonts.bunny.net)">
    <link href="[https://fonts.bunny.net/css?family=instrument-sans:400,500,600](https://fonts.bunny.net/css?family=instrument-sans:400,500,600)" rel="stylesheet" />

    <style>
        body {
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Alineamos al inicio para el header */
            align-items: center;
            min-height: 100vh;
            background-color: #000000;
            margin: 0;
            color: #ffffff;
            padding-top: 2rem; /* Espacio para el header */
            box-sizing: border-box; /* Incluir padding en el cálculo del tamaño */
        }
        header {
            position: fixed; /* Header fijo en la parte superior derecha */
            top: 0;
            right: 0;
            padding: 1rem 2rem;
            z-index: 10; /* Asegurar que el header esté por encima del contenido */
        }
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        .nav-links a {
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #ffffff;
            border: 1px solid #777;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .nav-links a:hover {
            background-color: #333333;
        }
        .image-section {
            flex-grow: 1; /* La sección de la imagen ocupa el espacio restante */
            display: flex;
            justify-content: center; /* Centrar horizontalmente */
            align-items: center; /* Centrar verticalmente */
            width: 50%; /* Ocupar todo el ancho disponible */
            padding: 2rem;
            box-sizing: border-box;
        }
        .image-placeholder {
            background-color: transparent; /* Fondo transparente para que se vea el negro */
            border: none; /* Sin borde */
            width: 90%; /* La imagen puede ocupar hasta el 90% del ancho */
            max-width: 1200px; /* Ancho máximo para la imagen */
            height: auto; /* La altura se ajusta automáticamente */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .image-placeholder img {
            display: block; /* Eliminar espacio extra debajo de la imagen */
            max-width: 100%; /* La imagen no excederá el ancho de su contenedor */
            height: auto; /* Mantener la proporción de aspecto */
            object-fit: contain; /* Asegurar que toda la imagen sea visible */
        }
        /* Opcional: Si la imagen tiene mucho espacio en blanco alrededor y quieres que lo ocupe más: */
        /*.image-placeholder img {*/
        /* display: block;*/
        /* width: auto; !* Ancho automático basado en la imagen *!*/
        /* max-height: 80vh; !* Altura máxima para no ocupar toda la pantalla *!*/
        /* object-fit: contain;*/
        /*}*/
    </style>
</head>
<body>
    <header>
        @if (Route::has('login'))
            <nav class="nav-links">
                @auth
                    <a href="{{ url('/dashboard') }}">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <div class="image-section">
        <div class="image-placeholder">
            <img src="{{ asset('storage/images/puntofast.jpg') }}" alt="Punto Fast Logo">
        </div>
    </div>
</body>