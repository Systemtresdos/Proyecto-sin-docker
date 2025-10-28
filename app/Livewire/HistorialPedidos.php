<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class HistorialPedidos extends Component
{
    use WithPagination;

    public $filtroEstado = '';
    public $search = '';
    public $fechaDesde = '';
    public $fechaHasta = '';

    protected $queryString = ['filtroEstado', 'search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->filtroEstado = '';
        $this->search = '';
        $this->fechaDesde = '';
        $this->fechaHasta = '';
        $this->resetPage();
    }

    public function verDetalle($pedidoId)
    {
        return redirect()->route('pedidos.detalle', $pedidoId);
    }

    public function render()
    {
        $usuario = Auth::user();

        $query = Pedido::with(['cliente.usuario', 'detallePedidos.producto', 'pago'])
            ->orderBy('created_at', 'desc');

        // Filtrar por cliente si es rol cliente
        if ($usuario->rol_id === 2 && $usuario->cliente) {
            $query->where('cliente_id', $usuario->cliente->id);
        }

        // Filtro por estado
        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        // Búsqueda por ID o total
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhere('total', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro por rango de fechas
        if ($this->fechaDesde) {
            $query->whereDate('created_at', '>=', $this->fechaDesde);
        }
        if ($this->fechaHasta) {
            $query->whereDate('created_at', '<=', $this->fechaHasta);
        }

        $pedidos = $query->paginate(10);

        // Estadísticas del cliente
        $estadisticas = null;
        if ($usuario->rol_id === 2 && $usuario->cliente) {
            $estadisticas = [
                'total_pedidos' => Pedido::where('cliente_id', $usuario->cliente->id)->count(),
                'pedidos_pendientes' => Pedido::where('cliente_id', $usuario->cliente->id)->where('estado', 'Pendiente')->count(),
                'pedidos_confirmados' => Pedido::where('cliente_id', $usuario->cliente->id)->where('estado', 'Confirmado')->count(),
                'pedidos_entregados' => Pedido::where('cliente_id', $usuario->cliente->id)->where('estado', 'Entregado')->count(),
                'total_gastado' => Pedido::where('cliente_id', $usuario->cliente->id)->where('estado', '!=', 'Cancelado')->sum('total'),
            ];
        }

        return view('livewire.historial-pedidos', compact('pedidos', 'estadisticas'));
    }
}