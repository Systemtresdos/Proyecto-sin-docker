<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination; // Para la paginación

class GestionPedidos extends Component
{
    use WithPagination; // Usar el trait de paginación

    public $pedidosConfirmados;
    public $pedidosEnPreparacion;
    public $pedidosListos;
    public $pedidosEntregados; // Para el cliente, será una colección. Para el admin, los datos se pasarán desde render.

    public $esAdmin = false;

    // Para asegurar que los estilos de paginación de Tailwind se usen por defecto
    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        if (Auth::check()) {
            // Ajusta el '1' si el rol_id para Administrador es diferente.
            // Usamos '==' para una comparación más flexible si rol_id es string '1' o int 1.
            // Si estás seguro del tipo y valor, puedes usar '==='
            $this->esAdmin = Auth::user()->rol_id == 1; 
        }
        $this->cargarDatosNoPaginados();
    }

    public function cargarDatosNoPaginados()
    {
        $usuario = Auth::user();
        $clienteId = null;

        if ($usuario && !$this->esAdmin && $usuario->cliente) {
            $clienteId = $usuario->cliente->id;
        }

        $queryConfirmados = Pedido::with(['cliente.usuario', 'detallePedidos.producto'])
                                ->where('estado', 'Confirmado')
                                ->orderBy('created_at', 'asc');
        $queryEnPreparacion = Pedido::with(['cliente.usuario', 'detallePedidos.producto'])
                                  ->where('estado', 'Preparando')
                                  ->orderBy('created_at', 'asc');
        $queryListos = Pedido::with(['cliente.usuario', 'detallePedidos.producto'])
                           ->where('estado', 'Listo')
                           ->orderBy('created_at', 'asc');

        if (!$this->esAdmin && $clienteId) {
            $queryConfirmados->where('cliente_id', $clienteId);
            $queryEnPreparacion->where('cliente_id', $clienteId);
            $queryListos->where('cliente_id', $clienteId);
        }

        $this->pedidosConfirmados = $queryConfirmados->get();
        $this->pedidosEnPreparacion = $queryEnPreparacion->get();
        $this->pedidosListos = $queryListos->get();

        if (!$this->esAdmin) {
            $queryEntregadosCliente = Pedido::with(['cliente.usuario', 'detallePedidos.producto'])
                                   ->where('estado', 'Entregado')
                                   ->orderBy('updated_at', 'desc');
            if ($clienteId) {
                $queryEntregadosCliente->where('cliente_id', $clienteId);
            }
            $this->pedidosEntregados = $queryEntregadosCliente->get();
        } else {
            $this->pedidosEntregados = collect(); // Para admin, se carga paginado en render()
        }
    }
    
    // Si usas wire:poll para llamar a un método de recarga, asegúrate que sea este o uno que llame a este.
    public function cargarPedidos() 
    {
        $this->cargarDatosNoPaginados();
        // Si la recarga por polling afecta a los datos paginados, podrías necesitar $this->resetPage();
    }

    public function cambiarEstado($pedidoId, $nuevoEstado)
    {
        if (!$this->esAdmin) {
            session()->flash('error_estado', 'No tiene permisos para realizar esta acción.');
            return;
        }

        try {
            $pedido = Pedido::findOrFail($pedidoId);
            $pedido->estado = $nuevoEstado;
            $pedido->save();

            $this->cargarDatosNoPaginados(); // Recargar datos no paginados
            $this->resetPage(); // Resetea la paginación para el admin
            session()->flash('mensaje_estado', 'Estado del pedido #' . $pedido->nro_pedido . ' actualizado a ' . $nuevoEstado);

        } catch (\Exception $e) {
            Log::error("Error al cambiar estado del pedido {$pedidoId} por admin: " . $e->getMessage());
            session()->flash('error_estado', 'No se pudo actualizar el estado del pedido.');
        }
    }

    public function render()
    {
        $viewData = [
            'pedidosConfirmados' => $this->pedidosConfirmados,
            'pedidosEnPreparacion' => $this->pedidosEnPreparacion,
            'pedidosListos' => $this->pedidosListos,
        ];

        if ($this->esAdmin) {
            $queryEntregadosAdmin = Pedido::with(['cliente.usuario', 'detallePedidos.producto'])
                                       ->where('estado', 'Entregado')
                                       ->orderBy('updated_at', 'desc');
            // Aquí se aplica la paginación para el administrador
            $viewData['pedidosEntregados'] = $queryEntregadosAdmin->paginate(10);
        } else {
            // El cliente usa la colección completa cargada en $this->pedidosEntregados
            $viewData['pedidosEntregados'] = $this->pedidosEntregados;
        }

        return view('livewire.gestion-pedidos', $viewData);
    }
}