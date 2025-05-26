<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Punto Fast - Gestión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100">

    <div class="flex h-screen">
        <div class="w-64 bg-green-800 text-white flex flex-col p-4 shadow-lg">
            <div class="text-2xl font-bold mb-8 text-center">Punto fast</div>
            <div class="flex items-center mb-8">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-green-800 font-bold mr-3">
                    <span class="text-sm">UN</span> {{-- Esto puede ser dinámico luego --}}
                </div>
                <span class="text-lg">UsuarioName</span> {{-- Esto también puede ser dinámico --}}
            </div>
            <nav class="flex-1">
                <ul>
                    <li class="mb-2">
                        <h3 class="text-lg font-semibold text-gray-300 mb-2">Menu</h3>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-green-700 transition duration-200
                            {{ request()->routeIs('dashboard') ? 'bg-green-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            Inicio
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM13 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z"></path></svg>
                            Productos
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2zm0 6H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2zm0 6H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2zM15 3h-2a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2zm0 6h-2a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2zm0 6h-2a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2z"></path></svg>
                            Categorías
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                            Proveedores
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v3a1 1 0 00.293.707l2 2a1 1 0 001.414 0l2-2A1 1 0 0014 9V6a4 4 0 00-4-4zm0 14a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"></path></svg>
                            Compras
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.5 14 6.364 14H17a1 1 0 000-2H6.364l1.002-1-.86-2.736L17 4H5.129a.997.997 0 00-.948-.684L3 1z"></path></svg>
                            Ventas
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-green-700 transition duration-200
                            {{ request()->routeIs('users.index') ? 'bg-green-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                            Usuario
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('roles.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-green-700 transition duration-200
                            {{ request()->routeIs('roles.index') ? 'bg-green-700' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V4a1 1 0 00-1-1H3zm2 2a1 1 0 000 2h10a1 1 0 000-2H5zm0 4a1 1 0 000 2h10a1 1 0 000-2H5zm0 4a1 1 0 000 2h10a1 1 0 000-2H5z" clip-rule="evenodd"></path></svg>
                            Roles
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3H5a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2zM5 11h2a2 2 0 002-2v-2a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2zM13 3h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM15 11h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2z"></path></svg>
                            Inventario
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold">
                    {{ $headerTitle ?? '' }}
                </h1>

                <div>
                    </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                {{ $slot }} {{-- Aquí es donde se inyectará el contenido de las vistas Livewire --}}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>