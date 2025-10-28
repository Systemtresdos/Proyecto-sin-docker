<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class GestionPedidos extends Component
{
    use WithPagination;

    // Filtros para reportes
    public $filtroEstado = '';
    public $filtroFecha = '';
    public $buscarCliente = '';

    protected $paginationTheme = 'tailwind';
    protected $queryString = ['filtroEstado', 'buscarCliente', 'filtroFecha'];

    public function limpiarFiltros()
    {
        $this->reset(['filtroEstado', 'filtroFecha', 'buscarCliente']);
        $this->resetPage();
    }

    public function generarPdf()
    {
        $params = [];
        
        if ($this->filtroEstado) {
            $params['estado'] = $this->filtroEstado;
        }
        
        if ($this->filtroFecha) {
            $params['fecha_desde'] = $this->filtroFecha;
            $params['fecha_hasta'] = $this->filtroFecha;
        }
        
        $url = '/pedidos/reporte/pdf';
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return redirect($url);
    }

    public function getUrlReportePdfProperty()
    {
        $params = [];
        
        if ($this->filtroEstado) {
            $params['estado'] = $this->filtroEstado;
        }
        
        if ($this->filtroFecha) {
            $params['fecha_desde'] = $this->filtroFecha;
            $params['fecha_hasta'] = $this->filtroFecha;
        }
        
        // Construir URL base
        $url = url('/pedidos/reporte/pdf');
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }

    public function getEstadisticasProperty()
    {
        $totalVentas = Pedido::whereIn('estado', ['Confirmado', 'Preparando', 'Listo', 'Entregado'])
                             ->sum('total');

        $enProceso = Pedido::whereIn('estado', ['Confirmado', 'Preparando', 'Listo'])->count();

        return [
            'total_pedidos' => Pedido::count(),
            'pendientes' => Pedido::where('estado', 'Pendiente')->count(),
            'confirmados' => Pedido::where('estado', 'Confirmado')->count(),
            'preparando' => Pedido::where('estado', 'Preparando')->count(),
            'en_proceso' => $enProceso,
            'listos' => Pedido::where('estado', 'Listo')->count(),
            'entregados' => Pedido::where('estado', 'Entregado')->count(),
            'cancelados' => Pedido::where('estado', 'Cancelado')->count(),
            'total_ventas' => $totalVentas,
            'total_ventas_hoy' => Pedido::whereDate('created_at', today())
                                        ->where('estado', '!=', 'Cancelado')
                                        ->sum('total'),
            'pedidos_hoy' => Pedido::whereDate('created_at', today())->count(),
        ];
    }

    public function render()
    {
        $query = Pedido::with(['cliente.usuario', 'detallePedidos.producto', 'pago'])
                      ->orderBy('created_at', 'desc');

        // Aplicar filtros
        if ($this->filtroEstado) {
            $query->where('estado', $this->filtroEstado);
        }

        if ($this->buscarCliente) {
            $query->whereHas('cliente.usuario', function($q) {
                $q->where('nombre', 'like', '%' . $this->buscarCliente . '%')
                  ->orWhere('email', 'like', '%' . $this->buscarCliente . '%');
            });
        }

        if ($this->filtroFecha) {
            $query->whereDate('created_at', $this->filtroFecha);
        }

        return view('livewire.gestion-pedidos', [
            'pedidos' => $query->paginate(15),
            'estadisticas' => $this->estadisticas,
        ]);
    }
}
