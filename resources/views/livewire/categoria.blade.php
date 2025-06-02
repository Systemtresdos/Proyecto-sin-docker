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

<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 p-4 sm:p-6">
    <div class="max-w-4xl w-full mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-6 sm:p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">Gestión de Categorías</h1>
            <p class="text-gray-600 dark:text-gray-300">Administra tus categorías de productos o servicios.</p>
        </div>

        <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-inner">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                {{ $categoria_id ? 'Editar Categoría' : 'Crear Nueva Categoría' }}
            </h2>
            <form wire:submit="crear" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre
                    </label>
                    <input wire:model="nombre" id="nombre" type="text"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                        placeholder="Ingrese el nombre de la categoría">
                    @error('nombre') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción
                    </label>
                    <textarea wire:model="descripcion" id="descripcion" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                        placeholder="Ingrese una descripción de la categoría"></textarea>
                    @error('descripcion') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2 flex justify-end gap-3 mt-2">
                    <button type="submit"
                        class="bg-[#E63946] hover:bg-[#C1121F] text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                        {{ $categoria_id ? 'Actualizar Categoría' : 'Crear Categoría' }}
                    </button>
                    <button type="button" wire:click="vaciarFormulario"
                        class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Listado de Categorías</h2>
            <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Nombre</th>
                        <th class="py-3 px-6 text-left">Descripción</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light">
                    @forelse($categorias as $categoria)
                        <tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $categoria->id }}</td>
                            <td class="py-3 px-6 text-left">{{ $categoria->nombre }}</td>
                            <td class="py-3 px-6 text-left max-w-xs overflow-hidden text-ellipsis">{{ $categoria->descripcion }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-2">
                                    <button wire:click="editar({{ $categoria->id }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded text-xs transition duration-300 ease-in-out transform hover:scale-105">
                                        Editar
                                    </button>
                                    <button wire:click="eliminar({{ $categoria->id }})"
                                        wire:confirm="¿Estás seguro de que quieres eliminar esta categoría?"
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs transition duration-300 ease-in-out transform hover:scale-105">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                                No hay categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>