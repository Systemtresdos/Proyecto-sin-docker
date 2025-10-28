<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pedidos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #E63946;
            padding-bottom: 15px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #E63946;
        }
        .estadisticas {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        .stat-row {
            display: table-row;
        }
        .stat-cell {
            display: table-cell;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #E63946;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
        }
        th {
            background-color: #E63946;
            color: white;
            padding: 8px 5px;
            text-align: left;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
        }
        .status-pendiente { background-color: #ffc107; color: #000; }
        .status-confirmado { background-color: #17a2b8; color: #fff; }
        .status-preparando { background-color: #fd7e14; color: #fff; }
        .status-listo { background-color: #28a745; color: #fff; }
        .status-entregado { background-color: #28a745; color: #fff; }
        .status-cancelado { background-color: #dc3545; color: #fff; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .page-break {
            page-break-after: always;
        }
        .summary-box {
            border: 2px solid #E63946;
            padding: 15px;
            margin: 20px 0;
            background-color: #fff5f5;
        }
        .summary-title {
            font-size: 14px;
            font-weight: bold;
            color: #E63946;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">PuntoFast</div>
        <p style="margin: 5px 0;">Reporte de Pedidos</p>
        <p style="margin: 5px 0;">Generado: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p style="margin: 5px 0;">Período: {{ $estadisticas['fecha_desde'] }} - {{ $estadisticas['fecha_hasta'] }}</p>
    </div>

    <div class="summary-box">
        <div class="summary-title">RESUMEN EJECUTIVO</div>
        <div class="estadisticas">
            <div class="stat-row">
                <div class="stat-cell">
                    <div class="stat-value">{{ $estadisticas['total_pedidos'] }}</div>
                    <div class="stat-label">Total Pedidos</div>
                </div>
                <div class="stat-cell">
                    <div class="stat-value">Bs. {{ number_format($estadisticas['total_ventas'], 2) }}</div>
                    <div class="stat-label">Total Ventas</div>
                </div>
                <div class="stat-cell">
                    <div class="stat-value">{{ $estadisticas['entregados'] }}</div>
                    <div class="stat-label">Entregados</div>
                </div>
                <div class="stat-cell">
                    <div class="stat-value">{{ $estadisticas['cancelados'] }}</div>
                    <div class="stat-label">Cancelados</div>
                </div>
            </div>
        </div>
    </div>

    <h3 style="margin-top: 20px; color: #E63946;">DETALLE DE PEDIDOS</h3>
    
    @if($pedidos->isEmpty())
        <p style="text-align: center; padding: 40px; color: #666;">
            No se encontraron pedidos para el período seleccionado.
        </p>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 20%;">Cliente</th>
                    <th style="width: 15%;">Fecha</th>
                    <th style="width: 12%;">Estado</th>
                    <th style="width: 12%;">Estado Pago</th>
                    <th style="width: 15%; text-align: right;">Total</th>
                    <th style="width: 8%;">Items</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidos as $index => $pedido)
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->cliente->usuario->nombre ?? 'N/A' }}</td>
                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($pedido->estado) }}">
                                {{ $pedido->estado }}
                            </span>
                        </td>
                        <td>
                            @if($pedido->pago)
                                <span class="status-badge status-{{ strtolower($pedido->pago->estado) }}">
                                    {{ $pedido->pago->estado }}
                                </span>
                            @else
                                <span style="color: #999;">Sin pago</span>
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: bold;">
                            Bs. {{ number_format($pedido->total, 2) }}
                        </td>
                        <td style="text-align: center;">
                            {{ $pedido->detallePedidos->count() }}
                        </td>
                    </tr>
                    
                    {{-- Salto de página cada 30 pedidos --}}
                    @if(($index + 1) % 30 === 0 && !$loop->last)
                        </tbody>
                        </table>
                        <div class="page-break"></div>
                        <table>
                        <thead>
                            <tr>
                                <th style="width: 8%;">#</th>
                                <th style="width: 20%;">Cliente</th>
                                <th style="width: 15%;">Fecha</th>
                                <th style="width: 12%;">Estado</th>
                                <th style="width: 12%;">Estado Pago</th>
                                <th style="width: 15%; text-align: right;">Total</th>
                                <th style="width: 8%;">Items</th>
                            </tr>
                        </thead>
                        <tbody>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="summary-box" style="margin-top: 30px;">
        <div class="summary-title">DISTRIBUCIÓN POR ESTADO</div>
        <table style="margin: 10px 0;">
            <tr>
                <td style="width: 50%; font-weight: bold;">Pendientes:</td>
                <td style="width: 50%; text-align: right;">{{ $estadisticas['pendientes'] }} pedidos</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Confirmados:</td>
                <td style="text-align: right;">{{ $estadisticas['confirmados'] }} pedidos</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">En Preparación:</td>
                <td style="text-align: right;">{{ $estadisticas['preparando'] }} pedidos</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Listos:</td>
                <td style="text-align: right;">{{ $estadisticas['listos'] }} pedidos</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Entregados:</td>
                <td style="text-align: right;">{{ $estadisticas['entregados'] }} pedidos</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #dc3545;">Cancelados:</td>
                <td style="text-align: right; color: #dc3545;">{{ $estadisticas['cancelados'] }} pedidos</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p><strong>PuntoFast</strong> - Sistema de Gestión de Pedidos</p>
        <p>Este reporte es confidencial y está destinado exclusivamente para uso interno.</p>
        <p>Generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i:s') }}</p>
    </div>
</body>
</html>
