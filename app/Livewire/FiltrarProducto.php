<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;

class FiltrarProducto extends Component
{
    public $buscar = '';
    public $categoria_id = '';

    public function render()
    {
        $categorias = Categoria::all();
        $consulta = Producto::query()->with('categoria');

        if (filled($this->categoria_id)) {
            $consulta->where('categoria_id', $this->categoria_id);
        }

        if (filled($this->buscar)) {
            $terminoBusqueda = trim($this->buscar);
            $consulta->where('nombre', 'like', '%' . $terminoBusqueda . '%');
        }

        $productos = $consulta->get();

        return view('livewire.filtrar-producto', [
            'productos' => $productos,
            'categorias' => $categorias,
        ]);
    }
}
