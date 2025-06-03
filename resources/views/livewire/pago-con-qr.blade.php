<div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-6">
    <div class="flex justify-center mb-4">
        <div class="text-xl font-bold">
            <span class="text-gray-800 dark:text-white">Punto</span>
            <span class="text-[#E63946]">Fast</span>
        </div>
    </div>

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

    @if ($mostrarDetalles)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <h4 class="text-lg font-medium mb-3 dark:text-white">Resumen del Pedido</h4>
            
            <div class="mb-4 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <ul class="space-y-2">
                    @foreach ($carritoProductos as $item)
                        <li class="flex justify-between text-gray-700 dark:text-gray-300">
                            <span>{{ $item['nombre'] }} (x{{ $item['cantidad'] }})</span>
                            <span>{{ number_format(($item['precio_venta'] ?? $item['precio']) * $item['cantidad'], 2) }} Bs</span>
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
            
            <div class="mb-4">
                @if(Auth::check() && Auth::user()->rol_id == 1)
                    <div class="mb-3 p-3 bg-gray-100 dark:bg-gray-700 rounded-md">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Entrega:
                            <span class="font-normal">Retiro en local (Automático)</span>
                        </p>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-1">Dirección de Entrega:
                            <span class="font-normal">Pedido en local (Automático)</span>
                        </p>
                    </div>

                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 mt-4">
                        Notas adicionales (Opcional)
                    </label>
                    <flux:input placeholder="Notas adicionales para el pedido"
                                wire:model.defer="notas_adicionales" class="w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-300" />

                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 mt-4">
                        Método de pago <span class="text-red-500">*</span>
                    </label>
                    <flux:select wire:model.defer="metodo_pago" placeholder="Seleccione un método de pago" class="w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-300">
                        <flux:select.option value="Efectivo">Efectivo</flux:select.option>
                        <flux:select.option value="Qr">Qr</flux:select.option>
                    </flux:select>

                @else
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Entrega del pedido <span class="text-red-500">*</span>
                    </label>
                    <flux:select wire:model.live="tipo_entrega" placeholder="Seleccione tipo de entrega" class="w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-300">
                        <flux:select.option value="Domicilio">Domicilio</flux:select.option>
                        <flux:select.option value="Retiro_local">Retiro en el local</flux:select.option>
                    </flux:select>

                    @if($tipo_entrega === 'Domicilio')
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 mt-4">
                            Dirección de entrega <span class="text-red-500">*</span>
                        </label>
                        <flux:input placeholder="Ingrese su dirección de entrega"
                                    wire:model.defer="direccion_entrega" class="w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-300" />
                    @elseif($tipo_entrega === 'Retiro_local')
                        <div class="mt-4 mb-3 p-3 bg-gray-100 dark:bg-gray-700 rounded-md">
                             <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Dirección de Entrega:
                                <span class="font-normal">Retiro en local</span>
                            </p>
                        </div>
                    @endif

                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 mt-4">
                        Notas adicionales (Opcional)
                    </label>
                    <flux:input placeholder="Notas adicionales para el pedido"
                                wire:model.defer="notas_adicionales" class="w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-300" />

                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 mt-4">
                        Método de pago
                    </label>
                    <flux:select wire:model.defer="metodo_pago" placeholder="Seleccione un método de pago" class="w-full p-2 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-300">
                        <flux:select.option value="Qr">Pago con QR</flux:select.option>
                    </flux:select>
                @endif
            </div>
            
            <button wire:click="procesarPedido"
                    wire:loading.attr="disabled"
                    class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                <span wire:loading wire:target="procesarPedido">Generando...</span>
                <span wire:loading.remove wire:target="procesarPedido">Procesar pedido</span>
            </button>
        </div>
    @else
        @if (!empty($codigoQrSvg))
            <div class="text-center border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-2xl font-semibold mb-4 dark:text-white">Escanea para Pagar</h3>
                <div class="flex justify-center my-6 p-4 bg-white rounded-lg shadow-md inline-block">
                    {!! $codigoQrSvg !!}
                </div>
                <p class="text-gray-700 dark:text-gray-300 mb-2">
                    Una vez realizado el pago, tu pedido será confirmado automáticamente.
                </p>
                @if($pedidoGuardado)
                <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-md text-sm text-gray-600 dark:text-gray-300">
                    <p>Número de Pedido: <strong class="text-gray-800 dark:text-white">{{ $pedidoGuardado->nro_pedido }}</strong></p>
                    <p>Total a Pagar: <strong class="text-gray-800 dark:text-white">{{ number_format($pedidoGuardado->total, 2) }} Bs</strong></p>
                    @if($pagoGuardado && $pagoGuardado->token_confirmacion && isset($urlConfirmacion))
                        <p class="mt-3">Si tienes problemas con el QR, puedes usar el siguiente enlace para verificar tu pago:</p>
                        <input type="text" value="{{ $urlConfirmacion }}" readonly 
                               class="w-full p-2 mt-1 border border-gray-300 rounded dark:bg-gray-600 dark:text-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               onclick="this.select(); document.execCommand('copy'); alert('Enlace copiado al portapapeles');">
                        <small class="text-xs text-gray-500 dark:text-gray-400">Haz clic en el enlace para copiarlo.</small>
                    @endif
                </div>
                @endif
                <a href="{{ route('home') }}" class="mt-8 inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg text-lg focus:outline-none focus:shadow-outline">
                    Volver al Inicio
                </a>
            </div>
        @elseif ($pedidoGuardado && Auth::check() && Auth::user()->rol_id == 1 && $pedidoGuardado->metodo_pago !== 'Qr')
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 text-center">
                <div class="bg-green-100 dark:bg-green-700 border-l-4 border-green-500 dark:border-green-300 text-green-700 dark:text-green-200 p-6 mb-6 rounded-lg shadow-md" role="alert">
                    <strong class="font-bold text-xl block mb-2">¡Pedido Registrado!</strong>
                    <span class="block sm:inline text-base">El pedido ha sido registrado con éxito.</span>
                    <p class="mt-2">Número de Pedido: <strong class="text-gray-800 dark:text-white">{{ $pedidoGuardado->nro_pedido }}</strong></p>
                    <p>Método de pago: <strong class="text-gray-800 dark:text-white">{{ $pedidoGuardado->metodo_pago }}</strong></p>
                </div>
                <a href="{{ route('dashboard') ?? url('/dashboard') }}" class="mt-6 inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg text-lg focus:outline-none focus:shadow-outline">
                    Ir al Dashboard
                </a>
            </div>
        @elseif ($pedidoGuardado) 
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 text-center">
                <div class="bg-green-100 dark:bg-green-700 border-l-4 border-green-500 dark:border-green-300 text-green-700 dark:text-green-200 p-6 mb-6 rounded-lg shadow-md" role="alert">
                    <strong class="font-bold text-xl block mb-2">¡Pedido Registrado!</strong>
                    <span class="block sm:inline text-base">Tu pedido ha sido registrado con éxito.</span>
                    <p class="mt-2">Número de Pedido: <strong class="text-gray-800 dark:text-white">{{ $pedidoGuardado->nro_pedido }}</strong></p>
                    <p>Método de pago: <strong class="text-gray-800 dark:text-white">{{ $pedidoGuardado->metodo_pago }}</strong></p>
                </div>
                <a href="{{ route('dashboard') }}" class="mt-6 inline-block bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg text-lg focus:outline-none focus:shadow-outline">
                    Volver al Inicio
                </a>
            </div>
        @else
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 text-center">
                <p class="text-red-600 dark:text-red-400 text-lg p-4 bg-red-50 dark:bg-red-800 rounded-md">
                    Algo salió mal al procesar tu pedido. Por favor, inténtalo de nuevo o contacta a soporte.
                </p>
                <a href="{{ url()->previous() }}" class="mt-6 inline-block bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg text-lg focus:outline-none focus:shadow-outline">
                    Intentar de Nuevo
                </a>
            </div>
        @endif
    @endif
</div>