<div>
    <h2>Gestión de Pedidos</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $pedido)
                <tr @if($loop->first && $pedido->estado !== 'Entregado') style="background:#ffe082;font-weight:bold;" @endif>
                    <td>{{ $pedido->nro_pedido }}</td>
                    <td>{{ $pedido->cliente->usuario->nombre ?? 'Sin nombre' }}</td>
                    <td>{{ $pedido->estado }}</td>
                    <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($pedido->estado === 'Confirmado')
                            <button wire:click="cambiarEstado({{ $pedido->id }}, 'Preparando')">Preparar</button>
                        @elseif($pedido->estado === 'Preparando')
                            <button wire:click="cambiarEstado({{ $pedido->id }}, 'Entregado')">Entregar</button>
                        @else
                            <span>-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay pedidos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>