<x-layouts.app title="Roles" :breadcrumbs="['Roles']">
    <div class=" text-gray-700 bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
        <div class="text-green-500 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h1 class="text-2xl font-bold mb-4">{{ $mensaje ?? '¡Pago Confirmado Exitosamente (Simulación)!' }}</h1>
        <div class="text-left mb-6">
            <p><strong>Nro. Pedido:</strong> {{ $pedido->nro_pedido }}</p>
<p><strong>Estado del Pedido:</strong> <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}</span></p>
<p><strong>Estado del Pago:</strong> <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $pago->estado)) }}</span></p>
<p><strong>Fecha de Confirmación:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago ?? now())->format('d/m/Y H:i:s') }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline inline-block">
            Volver al Inicio
        </a>
    </div>
</x-layouts.app>