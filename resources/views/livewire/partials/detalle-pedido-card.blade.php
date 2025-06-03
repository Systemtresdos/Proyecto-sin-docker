<div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Total productos: {{ optional($detallePedidos)->count() ?? 0 }}</p>

            @if(optional($detallePedidos)->isNotEmpty())
                <div class="mt-2">
                    <p class="text-sm text-gray-600 dark:text-gray-300 font-semibold">Productos en el pedido:</p>
                    <ul class="list-disc list-inside ml-4 text-sm text-gray-500 dark:text-gray-400">
                        @foreach($detallePedidos as $detalle)
                            <li>
                                {{ $detalle->producto->nombre ?? $detalle->nombre_producto ?? 'Producto no disponible' }}
                                @if(isset($detalle->cantidad))
                                    (x{{ $detalle->cantidad }})
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-2">Total: {{ number_format($total, 2) }} Bs</p>
        </div>
    </div>
</div>