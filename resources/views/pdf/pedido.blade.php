<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #{{ $pedido->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #E63946;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #E63946;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            background-color: #f8f9fa;
            padding: 8px;
            margin: 0 0 10px 0;
            border-left: 4px solid #E63946;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px;
            width: 30%;
        }
        .info-value {
            display: table-cell;
            padding: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th {
            background-color: #E63946;
            color: white;
            padding: 10px;
            text-align: left;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .total-row {
            padding: 5px 0;
        }
        .total-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }
        .total-value {
            display: inline-block;
            width: 100px;
            text-align: right;
        }
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #E63946;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-pendiente { background-color: #ffc107; color: #000; }
        .status-confirmado { background-color: #17a2b8; color: #fff; }
        .status-preparando { background-color: #fd7e14; color: #fff; }
        .status-listo { background-color: #28a745; color: #fff; }
        .status-entregado { background-color: #28a745; color: #fff; }
        .status-cancelado { background-color: #dc3545; color: #fff; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">PuntoFast</div>
        <p style="margin: 5px 0;">Sistema de Gestión de Pedidos</p>
        <p style="margin: 5px 0;">Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info-section">
        <h3>INFORMACIÓN DEL PEDIDO</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Número de Pedido:</div>
                <div class="info-value">#{{ $pedido->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha:</div>
                <div class="info-value">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado:</div>
                <div class="info-value">
                    <span class="status-badge status-{{ strtolower($pedido->estado) }}">
                        {{ $pedido->estado }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3>INFORMACIÓN DEL CLIENTE</h3>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nombre:</div>
                <div class="info-value">{{ $pedido->cliente->usuario->nombre ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">{{ $pedido->cliente->usuario->email ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Teléfono:</div>
                <div class="info-value">{{ $pedido->cliente->usuario->telefono ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Dirección:</div>
                <div class="info-value">{{ $pedido->cliente->usuario->direccion ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3>DETALLE DE PRODUCTOS</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">#</th>
                    <th style="width: 40%;">Producto</th>
                    <th style="width: 15%; text-align: center;">Cantidad</th>
                    <th style="width: 15%; text-align: right;">Precio Unit.</th>
                    <th style="width: 20%; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido->detallePedidos as $index => $detalle)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detalle->producto->nombre ?? 'Producto N/A' }}</td>
                        <td style="text-align: center;">{{ $detalle->cantidad }}</td>
                        <td style="text-align: right;">Bs. {{ number_format($detalle->precio_unitario, 2) }}</td>
                        <td style="text-align: right;">Bs. {{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Subtotal:</span>
            <span class="total-value">Bs. {{ number_format($pedido->total, 2) }}</span>
        </div>
        <div class="total-row grand-total">
            <span class="total-label">TOTAL:</span>
            <span class="total-value">Bs. {{ number_format($pedido->total, 2) }}</span>
        </div>
    </div>

    @if($pedido->pago)
        <div class="info-section">
            <h3>INFORMACIÓN DE PAGO</h3>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Estado de Pago:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ strtolower($pedido->pago->estado) }}">
                            {{ $pedido->pago->estado }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Método de Pago:</div>
                    <div class="info-value">{{ $pedido->pago->metodo_pago ?? 'QR' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Monto Pagado:</div>
                    <div class="info-value">Bs. {{ number_format($pedido->pago->monto, 2) }}</div>
                </div>
            </div>
        </div>
    @endif

    <div class="footer">
        <p><strong>PuntoFast</strong> - Sistema de Gestión de Pedidos</p>
        <p>Este es un documento generado automáticamente. Para consultas, contacte con nuestro servicio al cliente.</p>
        <p>Documento generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i:s') }}</p>
    </div>
</body>
</html>
