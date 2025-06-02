<x-layouts.app title="Qr Confirmado" :breadcrumbs="['Qr Confirmado']">
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-8 text-center text-gray-800 dark:text-white">

        <!-- Ícono de éxito -->
        <div class="text-[#38A169] mb-6">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <!-- Mensaje principal -->
        <h1 class="text-2xl font-bold mb-4">
            {{ $mensaje ?? '¡Pago Confirmado Exitosamente (Simulación)!' }}
        </h1>

        <!-- Detalles -->
        <div class="text-left text-gray-700 dark:text-gray-300 mb-6">
            <p><strong>Nro. Pedido:</strong> {{ $pedido->nro_pedido }}</p>
            <p><strong>Estado del Pedido:</strong>
                <span class="font-semibold">
                    {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                </span>
            </p>
            <p><strong>Estado del Pago:</strong>
                <span class="font-semibold">
                    {{ ucfirst(str_replace('_', ' ', $pago->estado)) }}
                </span>
            </p>
            <p><strong>Fecha de Confirmación:</strong>
                {{ \Carbon\Carbon::parse($pago->fecha_pago ?? now())->format('d/m/Y H:i:s') }}
            </p>
        </div>

        <!-- Botón de regreso -->
        <a href="{{ route('dashboard') }}"
            class="w-full bg-[#E63946] hover:bg-[#C1121F] text-white font-medium py-2 px-4 rounded-md transition duration-300 inline-block">
            Volver al Inicio
        </a>
    </div>
</x-layouts.app>
