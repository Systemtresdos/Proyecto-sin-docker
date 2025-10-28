<div>
    <flux:heading size="xl" class="mb-6">Mis Pedidos</flux:heading>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $estadisticas['total_pedidos'] }}</div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Total Pedidos</div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-500">{{ $estadisticas['pedidos_pendientes'] }}</div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Pendientes</div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-red-600 dark:text-red-500">Bs. {{ number_format($estadisticas['total_gastado'], 2) }}</div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Total Gastado</div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <flux:select wire:model.live="filtroEstado" label="Estado" placeholder="Todos los estados">
                    <option value="">Todos</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Confirmado">Confirmado</option>
                    <option value="Preparando">Preparando</option>
                    <option value="Listo">Listo</option>
                    <option value="Entregado">Entregado</option>
                    <option value="Cancelado">Cancelado</option>
                </flux:select>
            </div>
            
            <div>
                <flux:input wire:model.live="search" label="Buscar" placeholder="ID de pedido..." />
            </div>
            
            <div>
                <flux:input wire:model.live="fechaDesde" type="date" label="Desde" />
            </div>
            
            <div>
                <flux:input wire:model.live="fechaHasta" type="date" label="Hasta" />
            </div>
        </div>
        
        <div class="mt-4">
            <flux:button wire:click="limpiarFiltros" variant="ghost" size="sm">
                <flux:icon.x-mark variant="micro" class="mr-1" /> Limpiar filtros
            </flux:button>
        </div>
    </div>

    <!-- Lista de Pedidos -->
    @if($pedidos->isEmpty())
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-12">
            <div class="text-center">
                <flux:icon.shopping-bag class="mx-auto h-12 w-12 text-zinc-400 mb-4" />
                <flux:heading size="lg" class="mb-2">No tienes pedidos</flux:heading>
                <flux:subheading>Realiza tu primer pedido para verlo aquí</flux:subheading>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($pedidos as $pedido)
                <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <flux:heading size="lg">Pedido #{{ $pedido->id }}</flux:heading>
                                
                                @switch($pedido->estado)
                                    @case('Pendiente')
                                        <flux:badge color="zinc" size="sm">{{ $pedido->estado }}</flux:badge>
                                        @break
                                    @case('Confirmado')
                                        <flux:badge color="yellow" size="sm">{{ $pedido->estado }}</flux:badge>
                                        @break
                                    @case('Preparando')
                                        <flux:badge color="blue" size="sm">{{ $pedido->estado }}</flux:badge>
                                        @break
                                    @case('Listo')
                                        <flux:badge color="cyan" size="sm">{{ $pedido->estado }}</flux:badge>
                                        @break
                                    @case('Entregado')
                                        <flux:badge color="green" size="sm">{{ $pedido->estado }}</flux:badge>
                                        @break
                                    @case('Cancelado')
                                        <flux:badge color="red" size="sm">{{ $pedido->estado }}</flux:badge>
                                        @break
                                @endswitch
                            </div>
                            
                            <flux:subheading class="mb-3">
                                {{ $pedido->created_at->format('d/m/Y H:i') }}
                            </flux:subheading>
                            
                            <!-- Productos del pedido -->
                            <div class="space-y-2 mb-4">
                                @foreach($pedido->detallePedidos as $detalle)
                                    <div class="flex items-center gap-3 text-sm">
                                        <span class="text-zinc-600 dark:text-zinc-400">{{ $detalle->cantidad }}x</span>
                                        <span class="flex-1">{{ $detalle->producto->nombre }}</span>
                                        <span class="font-semibold">Bs. {{ number_format($detalle->subtotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="border-t border-zinc-200 dark:border-zinc-700 pt-3 mt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-lg">Total:</span>
                                    <span class="text-xl font-bold text-red-600 dark:text-red-500">Bs. {{ number_format($pedido->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="ml-4 flex flex-col gap-2">
                            <flux:button variant="ghost" size="sm" :href="route('pedidos.detalle', $pedido->id)" wire:navigate>
                                Ver detalle
                            </flux:button>
                            <flux:button variant="ghost" size="sm" :href="route('pedidos.pdf', $pedido->id)" target="_blank">
                                <flux:icon.document-arrow-down variant="micro" /> PDF
                            </flux:button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Paginación -->
        <div class="mt-6">
            {{ $pedidos->links() }}
        </div>
    @endif
</div>