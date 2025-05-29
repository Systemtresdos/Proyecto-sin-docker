{{-- resources/views/components/layout/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mi Aplicación</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex">

    {{-- Sidebar --}}
    @include('components.layouts.app.sidebar') {{-- <- ✅ Aquí se corrige el problema --}}

    <main class="flex-1 p-4">
        {{ $slot }}
    </main>

</body>
</html>
