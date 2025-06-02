<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;

class GestionPedidos extends Component
{
    public function cambiarEstado($pedidoId, $nuevoEstado)
    {
        $pedido = Pedido::find($pedidoId);
        if ($pedido && in_array($nuevoEstado, ['Preparando','Listo', 'Entregado'])) {
            $pedido->estado = $nuevoEstado;
            $pedido->save();
        }
    }

    public function render()
    {
        $pedidos = Pedido::where('estado', '!=', 'Entregado')
            ->orderByRaw("FIELD(estado, 'Pendiente', 'Confirmado', 'Listo' 'Preparando')")
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.gestion-pedidos', compact('pedidos'));
    }
}