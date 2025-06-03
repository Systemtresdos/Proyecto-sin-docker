<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" @if($esAdmin) wire:poll.15s="cargarPedidos" @endif>

    @if (session()->has('mensaje_estado'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('mensaje_estado') }}
        </div>
    @endif
    @if (session()->has('error_estado'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error_estado') }}
        </div>
    @endif

    @if ($esAdmin)
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Pedidos Pendientes (Confirmados)</h2>
            @if($pedidosConfirmados->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay pedidos pendientes</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Los nuevos pedidos confirmados aparecerán aquí.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pedidosConfirmados as $pedido)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border-l-4 border-yellow-500 @if($loop->first) ring-2 ring-yellow-400 @endif">
                            <div class="p-5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-lg text-gray-800 dark:text-white">Pedido #{{ $pedido->nro_pedido }}</span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $pedido->estado }}</span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                                            {{ $pedido->created_at->format('d/m/Y H:i') }} • {{ $pedido->cliente->usuario->nombre ?? 'Cliente no registrado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tipo Entrega: {{ $pedido->tipo_entrega }}</p>
                                        @if($pedido->tipo_entrega == 'Domicilio')
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Dirección: {{ $pedido->direccion_entrega }}</p>
                                        @endif
                                    </div>
                                    <button wire:click="cambiarEstado({{ $pedido->id }}, 'Preparando')"
                                        class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                        Preparar pedido
                                    </button>
                                </div>
                                @include('livewire.partials.detalle-pedido-card', ['detallePedidos' => $pedido->detallePedidos, 'total' => $pedido->total])
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Pedidos en Preparación</h2>
            @if($pedidosEnPreparacion->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay pedidos en preparación</h3>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pedidosEnPreparacion as $pedido)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border-l-4 border-blue-500">
                            <div class="p-5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-lg text-gray-800 dark:text-white">Pedido #{{ $pedido->nro_pedido }}</span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $pedido->estado }}</span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                                            {{ $pedido->created_at->format('d/m/Y H:i') }} • {{ $pedido->cliente->usuario->nombre ?? 'Cliente no registrado' }}
                                        </p>
                                    </div>
                                    <button wire:click="cambiarEstado({{ $pedido->id }}, 'Listo')"
                                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Marcar como Listo
                                    </button>
                                </div>
                                @include('livewire.partials.detalle-pedido-card', ['detallePedidos' => $pedido->detallePedidos, 'total' => $pedido->total])
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Pedidos Listos para Entrega/Retiro</h2>
            @if($pedidosListos->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay pedidos listos</h3>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pedidosListos as $pedido)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border-l-4 border-cyan-500">
                            <div class="p-5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-lg text-gray-800 dark:text-white">Pedido #{{ $pedido->nro_pedido }}</span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-cyan-100 text-cyan-800">{{ $pedido->estado }}</span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                                            {{ $pedido->created_at->format('d/m/Y H:i') }} • {{ $pedido->cliente->usuario->nombre ?? 'Cliente no registrado' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Tipo Entrega: {{ $pedido->tipo_entrega }}</p>
                                        @if($pedido->tipo_entrega == 'Domicilio')
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Dirección: {{ $pedido->direccion_entrega }}</p>
                                        @endif
                                    </div>
                                    <button wire:click="cambiarEstado({{ $pedido->id }}, 'Entregado')"
                                        class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-md transition flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Marcar como Entregado
                                    </button>
                                </div>
                                @include('livewire.partials.detalle-pedido-card', ['detallePedidos' => $pedido->detallePedidos, 'total' => $pedido->total])
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    @else
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">Estado de Mis Pedidos</h1>

        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Mis Pedidos Activos</h2>
            @php
                $pedidosActivosCliente = collect($pedidosConfirmados)
                                        ->merge($pedidosEnPreparacion)
                                        ->merge($pedidosListos)
                                        ->sortBy('created_at');
            @endphp

            @if($pedidosActivosCliente->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No tienes pedidos activos en este momento.</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Cuando realices un pedido, aparecerá aquí.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pedidosActivosCliente as $pedido)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border-l-4 
                            @if($pedido->estado == 'Confirmado') border-yellow-500 @elseif($pedido->estado == 'Preparando') border-blue-500 @elseif($pedido->estado == 'Listo') border-cyan-500 @endif">
                            <div class="p-5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-lg text-gray-800 dark:text-white">Pedido #{{ $pedido->nro_pedido }}</span>
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($pedido->estado == 'Confirmado') bg-yellow-100 text-yellow-800 @elseif($pedido->estado == 'Preparando') bg-blue-100 text-blue-800 @elseif($pedido->estado == 'Listo') bg-cyan-100 text-cyan-800 @endif">
                                                {{ $pedido->estado }}
                                            </span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                                            Realizado: {{ $pedido->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        
                                        @if($pedido->estado == 'Confirmado')
                                            <p class="mt-2 text-sm text-yellow-600 dark:text-yellow-400 font-semibold">Tu pedido ha sido confirmado y pronto comenzará a prepararse.</p>
                                        @elseif($pedido->estado == 'Preparando')
                                            <p class="mt-2 text-sm text-blue-600 dark:text-blue-400 font-semibold">¡Tu pedido está siendo preparado con mucho cariño! Gracias por tu paciencia.</p>
                                        @elseif($pedido->estado == 'Listo')
                                            @if($pedido->tipo_entrega == 'Domicilio')
                                                <p class="mt-2 text-sm text-cyan-600 dark:text-cyan-400 font-semibold">¡Tu pedido está listo! Nuestro delivery está en camino a: {{ $pedido->direccion_entrega }}.</p>
                                            @else
                                                <p class="mt-2 text-sm text-cyan-600 dark:text-cyan-400 font-semibold">¡Tu pedido está listo! Puedes pasar a retirarlo por nuestro local.</p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @include('livewire.partials.detalle-pedido-card', ['detallePedidos' => $pedido->detallePedidos, 'total' => $pedido->total])
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Mi Historial de Pedidos</h2>
            @if($pedidosEntregados->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No tienes pedidos en tu historial.</h3>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pedidosEntregados as $pedido)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden border-l-4 border-green-500">
                            <div class="p-5">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-lg text-gray-800 dark:text-white">Pedido #{{ $pedido->nro_pedido }}</span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $pedido->estado }}</span>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                                            Entregado: {{ $pedido->updated_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <span class="text-sm text-green-600 dark:text-green-400">Pedido completado</span>
                                </div>
                                @include('livewire.partials.detalle-pedido-card', ['detallePedidos' => $pedido->detallePedidos, 'total' => $pedido->total])
                            </div>
                        </div>
                    @endforeach
                </div>
                @if(!$esAdmin && $pedidosEntregados instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-4">
                    {{ $pedidosEntregados->links() }}
                </div>
                @endif
            @endif
        </section>
    @endif
</div>