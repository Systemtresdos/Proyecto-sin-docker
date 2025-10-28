<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Mostrar detalle de un pedido
     */
    public function detalle($id)
    {
        $pedido = Pedido::with(['cliente.usuario', 'detallePedidos.producto', 'pago'])
                       ->findOrFail($id);

        // Verificar permisos
        $usuario = Auth::user();
        if ($usuario->rol_id === 2) {
            // Si es cliente, solo puede ver sus propios pedidos
            if (!$usuario->cliente || $pedido->cliente_id !== $usuario->cliente->id) {
                abort(403, 'No tienes permiso para ver este pedido.');
            }
        }

        return view('pedidos.detalle', compact('pedido'));
    }

    /**
     * Generar PDF de un pedido específico
     */
    public function generarPDF($id)
    {
        $pedido = Pedido::with(['cliente.usuario', 'detallePedidos.producto', 'pago'])
                       ->findOrFail($id);

        // Verificar permisos
        $usuario = Auth::user();
        if ($usuario->rol_id === 2) {
            if (!$usuario->cliente || $pedido->cliente_id !== $usuario->cliente->id) {
                abort(403, 'No tienes permiso para generar este PDF.');
            }
        }

        $pdf = PDF::loadView('pdf.pedido', compact('pedido'));
        return $pdf->download("pedido_{$pedido->id}.pdf");
    }

    /**
     * Generar reporte PDF de todos los pedidos (solo admin)
     */
    public function generarReportePDF(Request $request)
    {
        // Solo admin puede generar reportes
        if (Auth::user()->rol_id !== 1) {
            abort(403, 'No tienes permiso para generar reportes.');
        }

        $query = Pedido::with(['cliente.usuario', 'detallePedidos.producto', 'pago'])
                      ->orderBy('created_at', 'desc');

        // Aplicar filtros si existen
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $pedidos = $query->get();

        // Calcular totales
        $totalVentas = $pedidos->where('estado', '!=', 'Cancelado')->sum('total');
        $totalPedidos = $pedidos->count();

        $estadisticas = [
            'total_ventas' => $totalVentas,
            'total_pedidos' => $totalPedidos,
            'pendientes' => $pedidos->where('estado', 'Pendiente')->count(),
            'confirmados' => $pedidos->where('estado', 'Confirmado')->count(),
            'preparando' => $pedidos->where('estado', 'Preparando')->count(),
            'listos' => $pedidos->where('estado', 'Listo')->count(),
            'entregados' => $pedidos->where('estado', 'Entregado')->count(),
            'cancelados' => $pedidos->where('estado', 'Cancelado')->count(),
            'fecha_desde' => $request->fecha_desde ?? 'Todas',
            'fecha_hasta' => $request->fecha_hasta ?? 'Todas',
        ];

        $pdf = PDF::loadView('pdf.reporte-pedidos', compact('pedidos', 'estadisticas'));
        return $pdf->download('reporte_pedidos_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Generar reporte PDF por rango de fechas
     */
    public function reportePorFecha(Request $request)
    {
        if (Auth::user()->rol_id !== 1) {
            abort(403, 'No tienes permiso para generar reportes.');
        }

        $request->validate([
            'fecha_desde' => 'required|date',
            'fecha_hasta' => 'required|date|after_or_equal:fecha_desde',
        ]);

        return $this->generarReportePDF($request);
    }

    /**
     * Generar reporte diario
     */
    public function reporteDiario()
    {
        if (Auth::user()->rol_id !== 1) {
            abort(403, 'No tienes permiso para generar reportes.');
        }

        $request = new Request([
            'fecha_desde' => today()->format('Y-m-d'),
            'fecha_hasta' => today()->format('Y-m-d'),
        ]);

        return $this->generarReportePDF($request);
    }

    /**
     * Generar reporte mensual
     */
    public function reporteMensual()
    {
        if (Auth::user()->rol_id !== 1) {
            abort(403, 'No tienes permiso para generar reportes.');
        }

        $request = new Request([
            'fecha_desde' => now()->startOfMonth()->format('Y-m-d'),
            'fecha_hasta' => now()->endOfMonth()->format('Y-m-d'),
        ]);

        return $this->generarReportePDF($request);
    }
}
