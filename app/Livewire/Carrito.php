<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Carrito extends Component
{
    public $carritoProductos = [];
    public $mostrarModalPagoQr = false;

    public function mount()
    {
        $this->cargarProductos();
    }
    #[On('carritoActualizado')]
    #[On('pagoQrCompletado')]
    public function cargarProductos()
    {
        $this->carritoProductos = session()->get('carrito', []);
        if (request()->routeIs('livewire.message') && session()->has('pago_qr_completado_flag')) {
            $this->mostrarModalPagoQr = false;
            session()->forget('pago_qr_completado_flag');
        }
    }
    public function quitarProductos($producto_id)
    {
        $carrito = session()->get('carrito', []);
        if (isset($carrito[$producto_id])) {
            unset($carrito[$producto_id]);
            session()->put('carrito', $carrito);
            $this->cargarProductos();
            $this->dispatch('carritoActualizado');
        }
    }
    public function actualizarCantidad($producto_id, $cantidad)
    {
        $carrito = session()->get('carrito', []);
        if (isset($carrito[$producto_id])) {
            $cantidad = (int) $cantidad;
            if ($cantidad > 0) {
                $carrito[$producto_id]['cantidad'] = $cantidad;
            } else {
                unset($carrito[$producto_id]);
            }
            session()->put('carrito', $carrito);
            $this->cargarProductos();
            $this->dispatch('carritoActualizado');
        }
    }
    public function calcularTotal()
    {
        return collect($this->carritoProductos)->sum(function ($producto) {
            $precio = $producto['precio_venta'] ?? 0;
            $cantidad = $producto['cantidad'] ?? 0;
            return $precio * $cantidad;
        });
    }

    public function iniciarPagoConQr()
    {
        if (empty($this->carritoProductos)) {
            session()->flash('error_carrito', 'Tu carrito está vacío.');
            return;
        }
        $this->mostrarModalPagoQr = true;
    }

    public function cerrarModalPagoQr()
    {
        $this->mostrarModalPagoQr = false;
    }
    
    public function render()
    {
        $totalCalculado = $this->calcularTotal();
        return view('livewire.carrito', ['total' => $totalCalculado]);
    }
}
