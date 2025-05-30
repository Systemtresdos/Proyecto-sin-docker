<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;

class BtnAgregarCarrito extends Component
{
    public $producto_id;
    public $producto_nombre;

    public function mount($producto_id, $producto_nombre)
    {
        $this->producto_id = $producto_id;
        $this->producto_nombre = $producto_nombre;
    }

    public function agregarAlCarrito()
    {
        $carrito = session()->get('carrito', []);

        if (isset($carrito[$this->producto_id])) {
            $carrito[$this->producto_id]['cantidad']++;
        } else {
            $producto = Producto::find($this->producto_id);
            $carrito[$this->producto_id] = [
                'producto_id' => $this->producto_id,
                'nombre' => $producto->nombre,
                'cantidad' => 1,
                'precio_venta' => $producto->precio_venta,
                'imagen' => $producto->imagen,
            ];
        }
        session()->put('carrito', $carrito);
        $this->dispatch('carritoActualizado');
    }

    public function render()
    {
        return <<<'BLADE'
            <button 
                wire:click="agregarAlCarrito"
                class="text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200"
            >
                Añadir al carrito
            </button>
        BLADE;
    }
}
