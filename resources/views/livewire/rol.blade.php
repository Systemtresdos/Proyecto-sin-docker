<?php

use Livewire\Volt\Component;
use App\Models\Rol;

new class extends Component {
    public $rol_id;
    public $nombre;
    public $descripcion;
    public $roles;

    public function mount()
    {
        $this->actualizarRoles();
    }

    public function actualizarRoles()
    {
        $this->roles = Rol::all();
    }
    public function crear():void
    {
        $this->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:500'],
        ]);

        if($this->rol_id) {
            $rol = Rol::find($this->rol_id);
            if($rol) {
                $rol->update([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
            ]);
        }
            } else {
                Rol::create([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                ]);
            }
        $this->vaciarFormulario();
        $this->actualizarRoles();
    }

    public function editar($id):void
    {
        $rol = Rol::find($id);
        if($rol) {
            $this->rol_id = $rol->id;
            $this->nombre = $rol->nombre;
            $this->descripcion = $rol->descripcion;
        }
    }

    public function eliminar($id):void{
        $rol = Rol::find($id);
        $rol->delete();
        $this->actualizarRoles();
    }

    public function vaciarFormulario():void
    {
        $this->reset(['rol_id', 'nombre', 'descripcion']);
    }
}; ?>

<div>
    <div>
        <form wire:submit="crear">
            <flux:input label="Nombre" type="text" wire:model="nombre" placeholder="Ingrese el nombre de la categoría" />
            <flux:textarea label="Descripción" wire:model="descripcion" placeholder="Ingrese una descripción del rol" />
            <flux:button class="mt-4" type="submit" variant="primary">
                {{ $rol_id ? 'Actualizar Rol' : 'Crear Rol' }}
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
                @foreach($roles as $rol)
                    <tr>
                        <td>{{ $rol->id }}</td>
                        <td>{{ $rol->nombre }}</td>
                        <td>{{ $rol->descripcion }}</td>
                        <td>
                            <flux:button wire:click="editar({{ $rol->id }})" variant="filled">Editar</flux:button>
                            <flux:button wire:click="eliminar({{ $rol->id }})" variant="danger">Eliminar</flux:button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
