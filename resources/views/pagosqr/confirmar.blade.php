<x-layouts.app title="Roles" :breadcrumbs="['Roles']">
    <div class="text-gray-600 bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-2xl font-bold text-center mb-6">Confirmar Pago</h1>

        @if (session('info_confirmacion'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('info_confirmacion') }}</span>
            </div>
        @endif

        <div class="mb-4 text-gray-600">
            <h2 class="text-lg font-semibold">Detalles del Pedido</h2>
            <p><strong>Nro. Pedido:</strong> {{ $pedido->nro_pedido }}</p>
<p><strong>Cliente:</strong> {{ $pedido->cliente->usuario->nombre ?? 'N/A' }} {{ $pedido->cliente->apellido_paterno ?? '' }}</p> {{-- Asumiendo relación cliente --}}
<p><strong>Total:</strong> {{ number_format($pedido->total, 2) }} Bs</p>
<p><strong>Estado Actual del Pago:</strong> <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $pago->estado)) }}</span></p>
        </div>

        <div class="mb-6">
            <h3 class="text-md font-semibold">Items:</h3>
            <ul class="list-disc list-inside ml-4">
                @if($pedido->detallePedidos)
    @foreach ($pedido->detallePedidos as $detalle)
        <li>{{ $detalle->nombre_producto }} (x{{ $detalle->cantidad }}) - {{ number_format($detalle->precio_unitario, 2) }} Bs c/u</li>
    @endforeach
@endif
            </ul>
        </div>

        @if ($pago->estado === 'Pendiente')
            <form action="{{ route('pago.qr.procesar', ['token' => $token]) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline">
                    Confirmar Pago
                </button>
            </form>
        @else
             <p class="text-center text-green-600 font-semibold">Este pago ya ha sido procesado.</p>
        @endif

        <div class="text-center mt-6">
            <a href="{{ route('dashboard') }}" class="text-blue-500 hover:text-blue-700">Volver al inicio</a>
        </div>
    </div>
</x-layouts.app>