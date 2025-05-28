<?php

use Livewire\Volt\Component;
use App\Models\Producto;
use App\Models\Categoria;

new class extends Component {
    public $productos;
    public $categorias;
    public $producto_id;
    public $nombre;
    public $descripcion;
    public $codigo;
    public $precio_venta;
    public $imagen;
    public $categoria_id;

    public function mount()
    {
        $this->actualizarProductos();   
    }
    public function actualizarProductos()
    {
        $this->productos = Producto::all();
        $this->categorias = Categoria::all();
    }

    public function crear():void
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'codigo' => 'required|string|max:100',
            'precio_venta' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|max:2048',
            'calificacion' => 'nullable|numeric|min:0|max:5',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        if($this->producto_id){
            $producto = Producto::find($this->producto_id);
            if($producto) {
                $producto->update([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'codigo' => $this->codigo,
                    'precio_venta' => $this->precio_venta,
                    'imagen' => $this->imagen ? $this->imagen->store('productos', 'public') : $producto->imagen,
                    'categoria_id' => $this->categoria_id,
                ]);
            }
        } else {
            Producto::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'codigo' => $this->codigo,
                'precio_venta' => $this->precio_venta,
                'imagen' => $this->imagen ? $this->imagen->store('productos', 'public') : null,
                'categoria_id' => $this->categoria_id,
            ]);
        }
        $this->vaciarFormulario();
        $this->actualizarProductos();
    }

    public function editar($id):void
    {
        $producto = Producto::find($id);
        if($producto) {
            $this->producto_id = $producto->id;
            $this->nombre = $producto->nombre;
            $this->descripcion = $producto->descripcion;
            $this->codigo = $producto->codigo;
            $this->precio_venta = $producto->precio_venta;
            $this->imagen = null; // No se carga la imagen al editar
            $this->categoria_id = $producto->categoria_id;
        }
    }
    public function vaciarFormulario():void
    {
        $this->producto_id = null;
        $this->nombre = '';
        $this->descripcion = '';
        $this->codigo = '';
        $this->precio_venta = 0;
        $this->imagen = null;
        $this->categoria_id = null;
    } 

    public function render() : mixed
    {
        return view('livewire.producto', [
            'productos' => $this->productos,
            'categorias' => $this->categorias,
        ]);
    }
}; ?>

<div>
    <div>
        <form wire:submit="crear">
            <flux:input label="Nombre" type="text" wire:model="nombre" placeholder="Ingrese el nombre de la categoría" />
            <flux:textarea label="Descripción" wire:model="descripcion" placeholder="Ingrese una descripción de la categoría" />
            <flux:input label="Código" type="text" wire:model="codigo" placeholder="Ingrese el código del producto" />
            <flux:input label="Precio de Venta" type="number" wire:model="precio_venta" placeholder="Ingrese el precio de venta" step="0.01" />
            <flux:input label="Imagen" type="file" wire:model="imagen" accept="image/*" />
            @if($imagen)
                <div class="mt-2">
                    <img src="{{ $imagen->temporaryUrl() }}" alt="Vista previa de la imagen" class="w-32 h-32 object-cover rounded">
                </div>
            @endif
            <flux:select label="Categoría" wire:model="categoria_id" placeholder="Seleccione una categoría">
                <option value="">Seleccione una categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </flux:select>
            <flux:button class="mt-4" type="submit" variant="primary">
                {{ $categoria_id ? 'Actualizar Producto' : 'Crear Producto' }}
            </flux:button>
            <flux:button wire:click="vaciarFormulario" class="mt-4" variant="ghost">
                Cancelar
            </flux:button>
        </form>
</div>