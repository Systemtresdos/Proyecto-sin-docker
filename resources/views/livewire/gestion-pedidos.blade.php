<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Gestión de Pedidos</h2>
    </div>

    @if($pedidos->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay pedidos registrados</h3>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Los nuevos pedidos aparecerán aquí automáticamente</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($pedidos as $pedido)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border-l-4 
                    @if($pedido->estado === 'Confirmado') border-yellow-500
                    @elseif($pedido->estado === 'Preparando') border-blue-500
                    @elseif($pedido->estado === 'Entregado') border-green-500
                    @endif
                    @if($loop->first && $pedido->estado !== 'Entregado') ring-2 ring-yellow-400 @endif">
                    
                    <div class="p-5">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-lg text-gray-800 dark:text-white">Pedido #{{ $pedido->nro_pedido }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($pedido->estado === 'Confirmado') bg-yellow-100 text-yellow-800
                                        @elseif($pedido->estado === 'Preparando') bg-blue-100 text-blue-800
                                        @elseif($pedido->estado === 'Listo') bg-blue-100 text-cyan-800
                                        @elseif($pedido->estado === 'Entregado') bg-green-100 text-green-800
                                        @endif">
                                        {{ $pedido->estado }}
                                    </span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">
                                    {{ $pedido->created_at->format('d/m/Y H:i') }} • 
                                    {{ $pedido->cliente->usuario->nombre ?? 'Cliente no registrado' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Total productos: {{ optional($pedido->detallePedidos)->count() ?? 0 }}</p>

                                    @if(optional($pedido->detallePedidos)->isNotEmpty())
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600 dark:text-gray-300 font-semibold">Productos en el pedido:</p>
                                            <ul class="list-disc list-inside ml-4 text-sm text-gray-500 dark:text-gray-400">
                                                @foreach($pedido->detallePedidos as $detalle)
                                                    <li class="flex items-center gap-2 text-red-300 text-xl">
                                                        {{ $detalle->producto->nombre ?? 'Producto no disponible' }}
                                                        @if(isset($detalle->cantidad))
                                                            (x{{ $detalle->cantidad }})
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Total: {{ number_format($pedido->total, 2) }} Bs</p>
                                </div>
                                
                                @if($pedido->estado === 'Confirmado')
                                    <button wire:click="cambiarEstado({{ $pedido->id }}, 'Preparando')" 
                                        class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Preparar pedido
                                    </button>
                                @elseif($pedido->estado === 'Preparando')
                                    <button wire:click="cambiarEstado({{ $pedido->id }}, 'Entregado')" 
                                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Marcar como entregado
                                    </button>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Pedido completado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>