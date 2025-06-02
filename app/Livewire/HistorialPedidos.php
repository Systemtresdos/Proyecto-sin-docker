<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class HistorialPedidos extends Component
{
    public function render()
    {
        $usuario = Auth::user();

        $query = Pedido::query()->orderBy('created_at', 'desc');

        if ($usuario->rol_id === 2 && $usuario->cliente) {
    $query->where('cliente_id', $usuario->cliente->id);
}

        $pedidos = $query->get();

        return view('livewire.historial-pedidos', compact('pedidos'));
    }
}