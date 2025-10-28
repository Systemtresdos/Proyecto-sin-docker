<div wire:poll.15s="cargarPedidos">
    <!-- Mensajes -->
    @if (session()->has('mensaje_estado'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 rounded-lg">
            {{ session('mensaje_estado') }}
        </div>
    @endif
    
    @if (session()->has('error_estado'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-lg">
            {{ session('error_estado') }}
        </div>
    @endif

    <flux:heading size="xl" class="mb-6">Gestión de Pedidos en Tiempo Real</flux:heading>

    <!-- Pedidos Confirmados -->
    <section class="mb-12">
        <flux:heading size="lg" class="mb-4">Pedidos Pendientes (Confirmados)</flux:heading>
        
        @if($pedidosConfirmados->isEmpty())
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-12 text-center">
                <flux:icon.inbox class="mx-auto h-12 w-12 text-zinc-400 mb-4" />
                <flux:heading size="lg">No hay pedidos confirmados</flux:heading>
                <flux:subheading>Los nuevos pedidos aparecerán aquí</flux:subheading>
            </div>
        @else
            <div class="space-y-4">
                @foreach($pedidosConfirmados as $pedido)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border-l-4 border-yellow-500 shadow-sm overflow-hidden @if($loop->first) ring-2 ring-yellow-400 @endif">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <flux:heading size="lg">Pedido #{{ $pedido->id }}</flux:heading>
                                        <flux:badge color="yellow" size="sm">{{ $pedido->estado }}</flux:badge>
                                    </div>
                                    <flux:subheading>
                                        {{ $pedido->created_at->format('d/m/Y H:i') }} • {{ $pedido->cliente->usuario->nombre ?? 'Cliente no registrado' }}
                                    </flux:subheading>
                                    @if($pedido->tipo_entrega)
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Tipo Entrega: {{ $pedido->tipo_entrega }}</p>
                                    @endif
                                    @if($pedido->tipo_entrega == 'Domicilio' && $pedido->direccion_entrega)
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Dirección: {{ $pedido->direccion_entrega }}</p>
                                    @endif
                                </div>
                                <flux:button wire:click="cambiarEstado({{ $pedido->id }}, 'Preparando')" variant="primary">
                                    <flux:icon.play variant="micro" class="mr-1" /> Preparar pedido
                                </flux:button>
                            </div>
                            
                            <!-- Productos -->
                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                                <div class="space-y-2">
                                    @foreach($pedido->detallePedidos as $detalle)
                                        <div class="flex items-center justify-between text-sm">
                                            <div>
                                                <span class="font-medium">{{ $detalle->cantidad }}x</span>
                                                <span>{{ $detalle->producto->nombre }}</span>
                                            </div>
                                            <span class="font-semibold">Bs. {{ number_format($detalle->subtotal, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="border-t border-zinc-200 dark:border-zinc-700 mt-3 pt-3 flex justify-between items-center">
                                    <span class="font-bold text-lg">Total:</span>
                                    <span class="text-2xl font-bold text-red-600">Bs. {{ number_format($pedido->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Pedidos en Preparación -->
    <section class="mb-12">
        <flux:heading size="lg" class="mb-4">Pedidos en Preparación</flux:heading>
        
        @if($pedidosEnPreparacion->isEmpty())
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-12 text-center">
                <flux:icon.clock class="mx-auto h-12 w-12 text-zinc-400 mb-4" />
                <flux:heading size="lg">No hay pedidos en preparación</flux:heading>
            </div>
        @else
            <div class="space-y-4">
                @foreach($pedidosEnPreparacion as $pedido)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border-l-4 border-blue-500 shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <flux:heading size="lg">Pedido #{{ $pedido->id }}</flux:heading>
                                        <flux:badge color="blue" size="sm">{{ $pedido->estado }}</flux:badge>
                                    </div>
                                    <flux:subheading>
                                        {{ $pedido->created_at->format('d/m/Y H:i') }} • {{ $pedido->cliente->usuario->nombre ?? 'Cliente no registrado' }}
                                    </flux:subheading>
                                </div>
                                <flux:button wire:click="cambiarEstado({{ $pedido->id }}, 'Listo')" variant="primary">
                                    <flux:icon.check variant="micro" class="mr-1" /> Marcar como Listo
                                </flux:button>
                            </div>
                            
                            <!-- Productos -->
                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                                <div class="space-y-2">
                                    @foreach($pedido->detallePedidos as $detalle)
                                        <div class="flex items-center justify-between text-sm">
                                            <div>
                                                <span class="font-medium">{{ $detalle->cantidad }}x</span>
                                                <span>{{ $detalle->producto->nombre }}</span>
                                            </div>
                                            <span class="font-semibold">Bs. {{ number_format($detalle->subtotal, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="border-t border-zinc-200 dark:border-zinc-700 mt-3 pt-3 flex justify-between items-center">
                                    <span class="font-bold text-lg">Total:</span>
                                    <span class="text-2xl font-bold text-red-600">Bs. {{ number_format($pedido->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- Pedidos Listos -->
    <section>
        <flux:heading size="lg" class="mb-4">Pedidos Listos para Entrega/Retiro</flux:heading>
        
        @if($pedidosListos->isEmpty())
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-12 text-center">
                <flux:icon.check-circle class="mx-auto h-12 w-12 text-zinc-400 mb-4" />
                <flux:heading size="lg">No hay pedidos listos</flux:heading>
            </div>
        @else
            <div class="space-y-4">
                @foreach($pedidosListos as $pedido)
                    <div class="bg-white dark:bg-zinc-800 rounded-lg border-l-4 border-green-500 shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <flux:heading size="lg">Pedido #{{ $pedido->id }}</flux:heading>
                                        <flux:badge color="green" size="sm">{{ $pedido->estado }}</flux:badge>
                                    </div>
                                    <flux:subheading>
                                        {{ $pedido->created_at->format('d/m/Y H:i') }} • {{ $pedido->cliente->usuario->nombre ?? 'Cliente no registrado' }}
                                    </flux:subheading>
                                    @if($pedido->tipo_entrega)
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">Tipo Entrega: {{ $pedido->tipo_entrega }}</p>
                                    @endif
                                    @if($pedido->tipo_entrega == 'Domicilio' && $pedido->direccion_entrega)
                                        <p class="text-sm text-zinc-600 dark:text-zinc-400">Dirección: {{ $pedido->direccion_entrega }}</p>
                                    @endif
                                </div>
                                <flux:button wire:click="cambiarEstado({{ $pedido->id }}, 'Entregado')" variant="primary">
                                    <flux:icon.check variant="micro" class="mr-1" /> Marcar como Entregado
                                </flux:button>
                            </div>
                            
                            <!-- Productos -->
                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                                <div class="space-y-2">
                                    @foreach($pedido->detallePedidos as $detalle)
                                        <div class="flex items-center justify-between text-sm">
                                            <div>
                                                <span class="font-medium">{{ $detalle->cantidad }}x</span>
                                                <span>{{ $detalle->producto->nombre }}</span>
                                            </div>
                                            <span class="font-semibold">Bs. {{ number_format($detalle->subtotal, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="border-t border-zinc-200 dark:border-zinc-700 mt-3 pt-3 flex justify-between items-center">
                                    <span class="font-bold text-lg">Total:</span>
                                    <span class="text-2xl font-bold text-red-600">Bs. {{ number_format($pedido->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
