<div>
    @if (session()->has('error_pago_qr'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error_pago_qr') }}</span>
        </div>
    @endif

    @if ($codigoQrSvg)
    <div class="text-center">
        <h4 class="text-lg font-medium mb-2 dark:text-white">Escanea este QR con tu móvil</h4>
        {{-- Usar el nro_pedido del objeto Pedido real --}}
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Pedido Nro: {{ $pedidoParaVista->nro_pedido ?? 'N/A' }}</p>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Total: {{ number_format($total, 2) }} Bs</p>
            <div class="flex justify-center my-4">
                {!! $codigoQrSvg !!}
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                El QR contiene la URL: <br> <span class="break-all">{{ $urlConfirmacion }}</span>
            </p>
            <p class="mt-4 text-sm text-gray-700 dark:text-gray-300">
                Al escanear, serás redirigido a una página para confirmar el pago.
            </p>
        </div>
    @elseif($mostrarDetalles)
        <div class="mb-4">
            <h4 class="text-lg font-medium mb-2 dark:text-white">Resumen del Pedido (Simulado)</h4>
            <p class="dark:text-gray-300"><strong>Total a Pagar:</strong> {{ number_format($total, 2) }} Bs</p>
            <ul class="list-disc list-inside mt-2 dark:text-gray-300">
                @foreach ($carritoProductos as $item)
                    <li>{{ $item['nombre'] }} (x{{ $item['cantidad'] }})</li>
                @endforeach
            </ul>
            <button wire:click="generarPedidoYQrConGuardado"
                    wire:loading.attr="disabled"
                    class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <span wire:loading wire:target="generarPedidoYQrConGuardado">Generando...</span>
                <span wire:loading.remove wire:target="generarPedidoYQrConGuardado">Generar QR para Pagar</span>
            </button>
        </div>
    @endif

    @if (session()->has('pago_confirmado_mensaje'))
         <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="block sm:inline">{{ session('pago_confirmado_mensaje') }}</span>
        </div>
    @endif
</div>