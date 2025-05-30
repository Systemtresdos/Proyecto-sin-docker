<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class CarritoContador extends Component
{
    public $contador = 0;

    public function mount()
    {
        $this->actualizarContador();
    }

    #[On('carritoActualizado')]
    public function actualizarContador()
    {
        $carrito = session()->get('carrito', []);
        $this->contador = collect($carrito)->sum('cantidad');
    }

    public function render()
    {
        return view('livewire.carrito-contador');
    }
}
