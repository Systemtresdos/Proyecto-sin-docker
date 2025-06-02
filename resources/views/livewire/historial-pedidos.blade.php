<div>
    <h2>Historial de Pedidos</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->nro_pedido }}</td>
                    <td>{{ $pedido->cliente->usuario->nombre ?? 'Sin nombre' }}</td>
                    <td>{{ $pedido->estado }}</td>
                    <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                    <td>${{ number_format($pedido->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay pedidos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>