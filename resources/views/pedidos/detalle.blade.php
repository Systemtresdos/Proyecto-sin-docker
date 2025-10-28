<x-layouts.app :title="'Detalle Pedido #' . $pedido->id">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <flux:heading size="xl" class="font-semibold mb-4">Detalle del Pedido #{{ $pedido->id }}</flux:heading>
            <!-- Botones de Acción -->
            <div class="mb-6 flex gap-3">
                <flux:button variant="primary" wire:navigate href="{{ route('pedidos.index') }}">
                    <flux:icon.arrow-left variant="micro" class="mr-2" /> Volver
                </flux:button>
                <flux:button variant="ghost" href="{{ route('pedidos.pdf', $pedido->id) }}" target="_blank">
                    <flux:icon.document-arrow-down variant="micro" class="mr-2" /> Descargar PDF
                </flux:button>
            </div>

            <!-- Grid de 2 columnas -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna Principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Información del Pedido -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                        <div class="border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
                            <flux:heading size="lg">Información del Pedido</flux:heading>
                        </div>

                        <div class="grid grid-cols-2 gap-4 p-6">
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Número de Pedido</p>
                                <p class="text-lg font-semibold">#{{ $pedido->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Fecha</p>
                                <p class="text-lg">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Estado</p>
                                <div class="mt-1">
                                    @switch($pedido->estado)
                                        @case('Pendiente')
                                            <flux:badge color="yellow" size="sm">{{ $pedido->estado }}</flux:badge>
                                            @break
                                        @case('Confirmado')
                                            <flux:badge color="blue" size="sm">{{ $pedido->estado }}</flux:badge>
                                            @break
                                        @case('Preparando')
                                            <flux:badge color="orange" size="sm">{{ $pedido->estado }}</flux:badge>
                                            @break
                                        @case('Listo')
                                            <flux:badge color="green" size="sm">{{ $pedido->estado }}</flux:badge>
                                            @break
                                        @case('Entregado')
                                            <flux:badge color="green" size="sm">{{ $pedido->estado }}</flux:badge>
                                            @break
                                        @case('Cancelado')
                                            <flux:badge color="red" size="sm">{{ $pedido->estado }}</flux:badge>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Total</p>
                                <p class="text-2xl font-bold text-red-600">Bs. {{ number_format($pedido->total, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                        <div class="border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
                            <flux:heading size="lg">Productos</flux:heading>
                        </div>

                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($pedido->detallePedidos as $detalle)
                                    <div class="flex items-center gap-4 pb-4 border-b last:border-b-0">
                                        @if($detalle->producto->imagen)
                                            <img src="{{ asset('storage/' . $detalle->producto->imagen) }}" 
                                                 alt="{{ $detalle->producto->nombre }}" 
                                                 class="w-16 h-16 object-cover rounded">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                <flux:icon.photo class="text-gray-400" />
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1">
                                            <p class="font-semibold">{{ $detalle->producto->nombre }}</p>
                                            <p class="text-sm text-gray-500">Cantidad: {{ $detalle->cantidad }}</p>
                                        </div>
                                        
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">Bs. {{ number_format($detalle->precio_unitario, 2) }}</p>
                                            <p class="font-semibold">Bs. {{ number_format($detalle->subtotal, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 pt-4 border-t">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-semibold">Total:</span>
                                    <span class="text-2xl font-bold text-red-600">Bs. {{ number_format($pedido->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Lateral -->
                <div class="space-y-6">
                    <!-- Información del Cliente -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                        <div class="border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
                            <flux:heading size="lg">Cliente</flux:heading>
                        </div>

                        <div class="p-6 space-y-3">
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Nombre</p>
                                <p class="font-semibold">{{ $pedido->cliente->usuario->nombre ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Email</p>
                                <p>{{ $pedido->cliente->usuario->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Teléfono</p>
                                <p>{{ $pedido->cliente->usuario->telefono ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Pago -->
                    @if($pedido->pago)
                        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                            <div class="border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
                                <flux:heading size="lg">Pago</flux:heading>
                            </div>

                            <div class="p-6 space-y-3">
                                <div>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Método</p>
                                    <p class="font-semibold">{{ $pedido->pago->metodo_pago }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Estado</p>
                                    <div class="mt-1">
                                        @if($pedido->pago->estado === 'Pagado')
                                            <flux:badge color="green" size="sm">{{ $pedido->pago->estado }}</flux:badge>
                                        @elseif($pedido->pago->estado === 'Pendiente')
                                            <flux:badge color="yellow" size="sm">{{ $pedido->pago->estado }}</flux:badge>
                                        @else
                                            <flux:badge color="red" size="sm">{{ $pedido->pago->estado }}</flux:badge>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Monto</p>
                                    <p class="text-xl font-bold text-green-600">Bs. {{ number_format($pedido->pago->monto, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">Fecha de Pago</p>
                                    <p>{{ $pedido->pago->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                            <div class="border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
                                <flux:heading size="lg">Pago</flux:heading>
                            </div>

                            <div class="p-6">
                                <p class="text-zinc-500 dark:text-zinc-400 text-center">Sin información de pago</p>
                            </div>
                        </div>
                    @endif

                    <!-- Timeline del Pedido -->
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                        <div class="border-b border-zinc-200 dark:border-zinc-700 px-6 py-4">
                            <flux:heading size="lg">Estado del Pedido</flux:heading>
                        </div>

                        <div class="p-6">
                            <div class="space-y-4">
                                @php
                                    $estados = ['Pendiente', 'Confirmado', 'Preparando', 'Listo', 'Entregado'];
                                    $estadoActualIndex = array_search($pedido->estado, $estados);
                                    $cancelado = $pedido->estado === 'Cancelado';
                                @endphp

                                @if($cancelado)
                                    <div class="flex items-center gap-3 text-red-600">
                                        <div class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center">
                                            <flux:icon.x-mark class="text-white" variant="micro" />
                                        </div>
                                        <div>
                                            <p class="font-semibold">Cancelado</p>
                                            <p class="text-xs text-gray-500">El pedido fue cancelado</p>
                                        </div>
                                    </div>
                                @else
                                    @foreach($estados as $index => $estado)
                                        @php
                                            $completado = $estadoActualIndex !== false && $index <= $estadoActualIndex;
                                        @endphp
                                        <div class="flex items-center gap-3 {{ $completado ? 'text-green-600' : 'text-gray-400' }}">
                                            <div class="w-8 h-8 rounded-full {{ $completado ? 'bg-green-600' : 'bg-gray-300' }} flex items-center justify-center">
                                                @if($completado)
                                                    <flux:icon.check class="text-white" variant="micro" />
                                                @else
                                                    <span class="text-xs text-white">{{ $index + 1 }}</span>
                                                @endif
                                            </div>
                                            <div class="{{ $completado ? '' : 'opacity-50' }}">
                                                <p class="font-semibold">{{ $estado }}</p>
                                                @if($completado && $estado === $pedido->estado)
                                                    <p class="text-xs text-gray-500">Estado actual</p>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <div class="ml-4 w-0.5 h-4 {{ $completado ? 'bg-green-600' : 'bg-gray-300' }}"></div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
