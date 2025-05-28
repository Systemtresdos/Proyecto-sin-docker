<?php

use Livewire\Volt\Component;
use App\Models\Categoria;

new class extends Component {
    //
    public $categoria_id;
    public $nombre;
    public $descripcion;
    public $categorias;

    public function mount()
    {
        $this->actualizarCategorias();
    }

    public function actualizarCategorias()
    {
        $this->categorias = Categoria::all();
    }
    public function crear():void
    {
        $this->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ]);

        if($this->categoria_id) {
            $categoria = Categoria::find($this->categoria_id);
            if($categoria) {
                $categoria->update([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
            ]);
        }
            } else {
                Categoria::create([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                ]);
            }
        $this->vaciarFormulario();
        $this->actualizarCategorias();
    }

    public function editar($id):void
    {
        $categoria = Categoria::find($id);
        if($categoria) {
            $this->categoria_id = $categoria->id;
            $this->nombre = $categoria->nombre;
            $this->descripcion = $categoria->descripcion;
        }
    }

    public function eliminar($id):void{
        $categoria = Categoria::find($id);
        $categoria->delete();
        $this->actualizarCategorias();
    }

    public function vaciarFormulario():void
    {
        $this->reset(['categoria_id', 'nombre', 'descripcion']);
    }
}; ?>

<div>
    <div>
        <form wire:submit="crear">
            <flux:input label="Nombre" type="text" wire:model="nombre" placeholder="Ingrese el nombre de la categoría" />
            <flux:textarea label="Descripción" wire:model="descripcion" placeholder="Ingrese una descripción de la categoría" />
            <flux:button class="mt-4" type="submit" variant="primary">
                {{ $categoria_id ? 'Actualizar Categoría' : 'Crear Categoría' }}
            </flux:button>
            <flux:button wire:click="vaciarFormulario" class="mt-4" variant="ghost">
                Cancelar
            </flux:button>
        </form>
    </div>
    <div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->id }}</td>
                        <td>{{ $categoria->nombre }}</td>
                        <td>{{ $categoria->descripcion }}</td>
                        <td>
                            <flux:button wire:click="editar({{ $categoria->id }})" variant="filled">Editar</flux:button>
                            <flux:button wire:click="eliminar({{ $categoria->id }})" variant="danger">Eliminar</flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>