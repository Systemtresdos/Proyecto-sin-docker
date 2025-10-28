<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardPedidos extends Component
{
    public $pedidosConfirmados;
    public $pedidosEnPreparacion;
    public $pedidosListos;
    
    public function mount()
    {
        $this->cargarPedidos();
    }

    public function cargarPedidos()
    {
        $this->pedidosConfirmados = Pedido::with(['cliente.usuario', 'detallePedidos.producto', 'pago'])
            ->where('estado', 'Confirmado')
            ->orderBy('created_at', 'asc')
            ->get();
            
        $this->pedidosEnPreparacion = Pedido::with(['cliente.usuario', 'detallePedidos.producto', 'pago'])
            ->where('estado', 'Preparando')
            ->orderBy('created_at', 'asc')
            ->get();
            
        $this->pedidosListos = Pedido::with(['cliente.usuario', 'detallePedidos.producto', 'pago'])
            ->where('estado', 'Listo')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function cambiarEstado($pedidoId, $nuevoEstado)
    {
        if (Auth::user()->rol_id !== 1) {
            session()->flash('error_estado', 'No tiene permisos para realizar esta acción.');
            return;
        }

        $estadosValidos = ['Pendiente', 'Confirmado', 'Preparando', 'Listo', 'Entregado', 'Cancelado'];
        
        if (!in_array($nuevoEstado, $estadosValidos)) {
            session()->flash('error_estado', 'Estado inválido.');
            return;
        }

        try {
            $pedido = Pedido::findOrFail($pedidoId);
            $estadoAnterior = $pedido->estado;
            $pedido->estado = $nuevoEstado;
            $pedido->save();

            Log::info("Pedido #{$pedido->id} cambió de {$estadoAnterior} a {$nuevoEstado} por usuario " . Auth::id());

            $this->cargarPedidos();
            session()->flash('mensaje_estado', "Pedido #{$pedido->id} actualizado a {$nuevoEstado}");

        } catch (\Exception $e) {
            Log::error("Error al cambiar estado del pedido {$pedidoId}: " . $e->getMessage());
            session()->flash('error_estado', 'No se pudo actualizar el estado del pedido.');
        }
    }

    public function cancelarPedido($pedidoId)
    {
        $this->cambiarEstado($pedidoId, 'Cancelado');
    }

    public function render()
    {
        return view('livewire.dashboard-pedidos');
    }
}
