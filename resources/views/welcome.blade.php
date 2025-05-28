<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gestión de Usuarios</title>
        @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Asegúrate de que tienes esto para Tailwind y JS --}}
        @livewireStyles {{-- Importante para estilos Livewire --}}
    </head>
    <body class="antialiased bg-gray-100">
        <div class="container mx-auto p-4">
            @livewire('user-management') {{-- Aquí se carga tu componente Livewire --}}
        </div>

        @livewireScripts {{-- Importante para scripts Livewire --}}
    </body>
</html>