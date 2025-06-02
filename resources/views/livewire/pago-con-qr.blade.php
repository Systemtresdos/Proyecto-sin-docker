<div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-6">
    <!-- Logo PuntoFast -->
    <div class="flex justify-center mb-4">
        <div class="text-xl font-bold">
            <span class="text-gray-800 dark:text-white">Punto</span>
            <span class="text-[#E63946]">Fast</span>
        </div>
    </div>

    <!-- Mensajes de error/success -->
    @if (session()->has('error_pago_qr'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded" role="alert">
            <p class="text-red-700">{{ session('error_pago_qr') }}</p>
        </div>
    @endif

    @if (session()->has('pago_confirmado_mensaje'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded" role="alert">
            <strong class="font-bold text-green-700">¡Éxito!</strong>
            <span class="block text-green-700">{{ session('pago_confirmado_mensaje') }}</span>
        </div>
    @endif

    <!-- Sección de QR -->
    @if ($codigoQrSvg)
        <div class="text-center py-4">
            <h4 class="text-lg font-medium mb-2 dark:text-white">Escanea este QR con tu móvil</h4>
            
            <div class="bg-white p-4 rounded-lg inline-block shadow-md">
                <p class="text-sm text-gray-600 mb-1">Pedido Nro: {{ $pedidoParaVista->nro_pedido ?? 'N/A' }}</p>
                <p class="text-lg font-bold text-[#E63946] mb-3">{{ number_format($total, 2) }} Bs</p>
                
                <div class="flex justify-center my-4">
                    {!! $codigoQrSvg !!}
                </div>
                
                <p class="text-xs text-gray-500 mt-2 max-w-xs mx-auto">
                    El QR contiene la URL: <br> 
                    <span class="break-all text-gray-700">{{ $urlConfirmacion }}</span>
                </p>
            </div>
            
            <p class="mt-4 text-sm text-gray-700 dark:text-gray-300">
                Al escanear, serás redirigido a una página para confirmar el pago.
            </p>
        </div>
    @endif

    <!-- Sección de detalles del pedido -->
    @if ($mostrarDetalles)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <h4 class="text-lg font-medium mb-3 dark:text-white">Resumen del Pedido</h4>
            
            <div class="mb-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <ul class="space-y-2">
                    @foreach ($carritoProductos as $item)
                        <li class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>{{ $item['nombre'] }} (x{{ $item['cantidad'] }})</span>
                            <span>{{ number_format($item['precio_venta'] * $item['cantidad'], 2) }} Bs</span>
                        </li>
                    @endforeach
                </ul>
                
                <div class="border-t border-gray-200 dark:border-gray-600 mt-3 pt-3">
                    <p class="flex justify-between font-bold text-lg dark:text-white">
                        <span>Total a Pagar:</span>
                        <span class="text-[#E63946]">{{ number_format($total, 2) }} Bs</span>
                    </p>
                </div>
            </div>
            
            <!-- Opciones de entrega -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Entrega del pedido
                </label>
                <flux:select wire:model.live="tipo_entrega" placeholder="Entrega del pedido" class="mt-2 w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-300">
                <flux:select.option value="Domicilio">Domicilio</flux:select.option>
                <flux:select.option value="Retiro_local">Retiro en el local</flux:select.option>
            </flux:select>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Direccion de entrega
            </label>
            <flux:input wire:key="direccion_{{ $tipo_entrega }}" placeholder="Direccion de entrega"
                        wire:model="direccion_entrega" value=""/>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Notas adicionales
                        </label>
            <flux:input placeholder="Notas adicionales"
                        wire:model="notas_adicionales" />
            <flux:select wire:model="metodo_pago" placeholder="Selecione un metodo de pago" class="mt-2 w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-300">
                <flux:select.option value="Qr">Qr</flux:select.option>
            </flux:select>
            <button wire:click="generarPedidoYQrConGuardado"
                    wire:loading.attr="disabled"
                    class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <span wire:loading wire:target="generarPedidoYQrConGuardado">Generando...</span>
                <span wire:loading.remove wire:target="generarPedidoYQrConGuardado">Generar QR para Pagar</span>
            </button>
        </div>
    @endif
</div>