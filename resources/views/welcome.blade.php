<title>Laravel</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#dc2626">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Pedidos">
    <link rel="manifest" href="/manifest.json">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: sans-serif;
            min-height: 100vh;
            background-color: #000000;
            color: #ffffff;
            overflow-x: hidden;
        }
        
        header {
            position: fixed;
            top: 0;
            right: 0;
            padding: 1.5rem 2rem;
            z-index: 1000;
            background: linear-gradient(to bottom, rgba(0,0,0,0.8), rgba(0,0,0,0));
        }
        
        .nav-links {
            display: flex;
            gap: 1rem;
        }
        
        .nav-links a {
            padding: 0.5rem 1.25rem;
            text-decoration: none;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .nav-links a:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        
        .image-section {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
        }
        
        .image-placeholder {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .image-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }
        
        /* Overlay sutil para mejorar la legibilidad del header */
        .image-placeholder::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(to bottom, rgba(0,0,0,0.4), transparent);
            z-index: 1;
            pointer-events: none;
        }
        
        /* Título centrado */
        .app-title {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            text-align: center;
        }
        
        .app-title h1 {
            font-size: 4rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.8);
            margin: 0;
            letter-spacing: 2px;
        }
        
        .app-title p {
            font-size: 1.5rem;
            font-weight: 400;
            color: #ffffff;
            text-shadow: 1px 2px 4px rgba(0, 0, 0, 0.8);
            margin-top: 1rem;
            letter-spacing: 1px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            header {
                padding: 1rem;
            }
            
            .nav-links {
                gap: 0.5rem;
            }
            
            .nav-links a {
                padding: 0.4rem 1rem;
                font-size: 0.85rem;
            }
            
            .app-title h1 {
                font-size: 2.5rem;
            }
            
            .app-title p {
                font-size: 1.1rem;
            }
        }
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
            <img class="object-cover" src="{{ asset('storage/productos/portada1.jpg') }}" alt="Punto Fast Logo">
            
            <div class="app-title">
                <h1>Punto fast</h1>
                <p>Gustos que dan sustos</p>
            </div>
        </div>
    </div>

    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(function(registration) {
                    console.log('Service Worker registrado con éxito:', registration);
                })
                .catch(function(error) {
                    console.log('Error al registrar Service Worker:', error);
                });
        }
    </script>
</body>
