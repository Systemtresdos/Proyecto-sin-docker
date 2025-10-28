<div>
    <!-- Título y Estadísticas -->
    <flux:heading size="xl" class="mb-6">Reportes de Pedidos</flux:heading>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $estadisticas['total_pedidos'] }}</div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Total Pedidos</div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $estadisticas['en_proceso'] }}</div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">En Proceso</div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600">{{ $estadisticas['entregados'] }}</div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Entregados</div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-red-600">Bs. {{ number_format($estadisticas['total_ventas'], 2) }}</div>
                <div class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Ventas Totales</div>
            </div>
        </div>
    </div>

    <!-- Controles y Filtros -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <flux:heading size="lg">Filtros de Búsqueda</flux:heading>
            <div class="flex gap-2">
                <flux:button wire:click="limpiarFiltros" variant="ghost" size="sm">
                    <flux:icon.x-mark variant="micro" class="mr-1" /> Limpiar
                </flux:button>
                @php
                    $params = [];
                    if ($filtroEstado) $params[] = 'estado=' . urlencode($filtroEstado);
                    if ($filtroFecha) {
                        $params[] = 'fecha_desde=' . $filtroFecha;
                        $params[] = 'fecha_hasta=' . $filtroFecha;
                    }
                    $queryString = !empty($params) ? '?' . implode('&', $params) : '';
                @endphp
                <a href="{{ route('pedidos.reporte.pdf') }}{{ $queryString }}" 
                   target="_blank"
                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-600 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Generar PDF
                </a>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:select wire:model.live="filtroEstado" label="Estado" placeholder="Todos">
                <option value="">Todos los estados</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Confirmado">Confirmado</option>
                <option value="Preparando">Preparando</option>
                <option value="Listo">Listo</option>
                <option value="Entregado">Entregado</option>
                <option value="Cancelado">Cancelado</option>
            </flux:select>
            
            <flux:input wire:model.live="buscarCliente" label="Buscar cliente" placeholder="Nombre o email..." />
            
            <flux:input wire:model.live="filtroFecha" type="date" label="Fecha" />
        </div>
    </div>

    <!-- Tabla de Pedidos -->
    <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Tipo Entrega</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($pedidos as $pedido)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-white">
                                #{{ $pedido->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $pedido->cliente->usuario->nombre ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $pedido->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">
                                Bs. {{ number_format($pedido->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $colorEstado = match($pedido->estado) {
                                        'Pendiente' => 'zinc',
                                        'Confirmado' => 'yellow',
                                        'Preparando' => 'blue',
                                        'Listo' => 'green',
                                        'Entregado' => 'lime',
                                        'Cancelado' => 'red',
                                        default => 'zinc'
                                    };
                                @endphp
                                <flux:badge :color="$colorEstado" size="sm">{{ $pedido->estado }}</flux:badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $pedido->tipo_entrega ?? 'No especificado' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-2">
                                    <a href="{{ route('pedidos.detalle', $pedido->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <flux:icon.eye class="w-5 h-5" />
                                    </a>
                                    <a href="{{ route('pedidos.pdf', $pedido->id) }}" 
                                       target="_blank"
                                       class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        <flux:icon.document-text class="w-5 h-5" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <flux:icon.inbox class="w-12 h-12 text-zinc-400 mb-3" />
                                    <flux:heading size="lg">No hay pedidos</flux:heading>
                                    <flux:subheading>Los pedidos aparecerán aquí</flux:subheading>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pedidos->hasPages())
            <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-700">
                {{ $pedidos->links() }}
            </div>
        @endif
    </div>
</div>
