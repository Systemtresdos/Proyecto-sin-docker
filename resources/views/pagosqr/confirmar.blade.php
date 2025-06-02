<x-layouts.app title="ConfirmarQR" :breadcrumbs="['ConfirmarQR']">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-8">
        <!-- Título -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Confirmar Pago</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Verifica los datos antes de confirmar tu pago</p>
        </div>

        <!-- Mensaje de sesión -->
        @if (session('info_confirmacion'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('info_confirmacion') }}</span>
            </div>
        @endif

        <!-- Detalles del Pedido -->
        <div class="mb-6 text-gray-700 dark:text-gray-300">
            <h2 class="text-lg font-semibold mb-2">Detalles del Pedido</h2>
            <p><strong>Nro. Pedido:</strong> {{ $pedido->nro_pedido }}</p>
            <p><strong>Cliente:</strong> {{ $pedido->cliente->usuario->nombre ?? 'N/A' }} {{ $pedido->cliente->apellido_paterno ?? '' }}</p>
            <p><strong>Total:</strong> {{ number_format($pedido->total, 2) }} Bs</p>
            <p><strong>Estado del Pago:</strong> 
                <span class="font-semibold">
                    {{ ucfirst(str_replace('_', ' ', $pago->estado)) }}
                </span>
            </p>
        </div>

        <!-- Items -->
        <div class="mb-6">
            <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-2">Items</h3>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300">
                @foreach ($pedido->detallePedidos as $detalle)
                    <li>{{ $detalle->nombre_producto }} (x{{ $detalle->cantidad }}) - {{ number_format($detalle->precio_unitario, 2) }} Bs c/u</li>
                @endforeach
            </ul>
        </div>

        <!-- Confirmar botón o mensaje -->
        @if ($pago->estado === 'Pendiente')
            <form action="{{ route('pago.qr.procesar', ['token' => $token]) }}" method="POST" class="mb-4">
                @csrf
                <button type="submit"
                    class="w-full bg-[#E63946] hover:bg-[#C1121F] text-white font-medium py-2 px-4 rounded-md transition duration-300">
                    Confirmar Pago
                </button>
            </form>
        @else
            <p class="text-center text-green-600 dark:text-green-400 font-semibold mb-4">
                Este pago ya ha sido procesado.
            </p>
        @endif

        <!-- Enlace al dashboard -->
        <div class="text-center">
            <a href="{{ route('dashboard') }}"
                class="text-[#E63946] hover:text-[#C1121F] text-sm font-medium transition duration-300">
                Volver al inicio
            </a>
        </div>
    </div>
</x-layouts.app>
