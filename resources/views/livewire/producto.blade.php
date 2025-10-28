<?php

use Livewire\Volt\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $productos;
    public $categorias;
    public $producto_id;
    public $nombre;
    public $descripcion;
    public $codigo;
    public $precio_venta;
    public $imagen; // Para el nuevo archivo de imagen
    public $current_imagen_path; // Para la ruta de la imagen existente
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

    public function crear(): void
    {
        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'codigo' => 'required|string|max:100|unique:productos,codigo,' . ($this->producto_id ? $this->producto_id : 'NULL'),
            'precio_venta' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|max:2048', // 2MB Max
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $imagePath = $this->current_imagen_path; // Mantener la imagen existente por defecto

        if ($this->imagen) {
            if ($this->current_imagen_path) {
                Storage::disk('public')->delete($this->current_imagen_path); // Eliminar imagen antigua
            }
            $imagePath = $this->imagen->store('productos', 'public');
        }

        if ($this->producto_id) {
            $producto = Producto::find($this->producto_id);
            if ($producto) {
                $producto->update([
                    'nombre' => $this->nombre,
                    'descripcion' => $this->descripcion,
                    'codigo' => $this->codigo,
                    'precio_venta' => $this->precio_venta,
                    'imagen' => $imagePath,
                    'categoria_id' => $this->categoria_id,
                ]);
            }
        } else {
            Producto::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'codigo' => $this->codigo,
                'precio_venta' => $this->precio_venta,
                'imagen' => $imagePath,
                'categoria_id' => $this->categoria_id,
            ]);
        }
        $this->vaciarFormulario();
        $this->actualizarProductos();
    }

    public function editar($id): void
    {
        $producto = Producto::find($id);
        if ($producto) {
            $this->producto_id = $producto->id;
            $this->nombre = $producto->nombre;
            $this->descripcion = $producto->descripcion;
            $this->codigo = $producto->codigo;
            $this->precio_venta = $producto->precio_venta;
            $this->current_imagen_path = $producto->imagen; // Cargar la ruta de la imagen existente
            $this->imagen = null; // Resetear el input de archivo
            $this->categoria_id = $producto->categoria_id;
        }
    }

    public function eliminar($id): void
    {
        $producto = Producto::find($id);
        if ($producto) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen); // Eliminar imagen del almacenamiento
            }
            $producto->delete();
            $this->actualizarProductos();
        }
    }

    public function vaciarFormulario(): void
    {
        $this->reset(['producto_id', 'nombre', 'descripcion', 'codigo', 'precio_venta', 'imagen', 'current_imagen_path', 'categoria_id']);
    }

    public function render(): mixed
    {
        return view('livewire.producto', [
            'productos' => $this->productos,
            'categorias' => $this->categorias,
        ]);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 p-4 sm:p-6">
    <div class="max-w-6xl w-full mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-6 sm:p-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">Gestión de Productos</h1>
            <p class="text-gray-600 dark:text-gray-300">Administra tus productos, incluyendo detalles, precios e imágenes.</p>
        </div>

        <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-inner">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                {{ $producto_id ? 'Editar Producto' : 'Crear Nuevo Producto' }}
            </h2>
            <form wire:submit="crear" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre
                    </label>
                    <input wire:model="nombre" id="nombre" type="text"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                        placeholder="Nombre del producto">
                    @error('nombre') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Código
                    </label>
                    <input wire:model="codigo" id="codigo" type="text"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                        placeholder="Código único del producto">
                    @error('codigo') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="precio_venta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Precio de Venta
                    </label>
                    <input wire:model="precio_venta" id="precio_venta" type="number" step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                        placeholder="Ej. 99.99">
                    @error('precio_venta') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="sm:col-span-2 md:col-span-3">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción
                    </label>
                    <textarea wire:model="descripcion" id="descripcion" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                        placeholder="Breve descripción del producto"></textarea>
                    @error('descripcion') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="sm:col-span-1 md:col-span-2">
                    <label for="imagen" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Imagen del Producto
                    </label>
                    <input wire:model="imagen" id="imagen" type="file" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#E63946] file:text-white hover:file:bg-[#C1121F] cursor-pointer">
                    @error('imagen') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror

                    @if($imagen)
                        <div class="mt-4 flex items-center space-x-4">
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Vista previa de la nueva imagen:</span>
                            <img src="{{ $imagen->temporaryUrl() }}" alt="Vista previa de la imagen" class="w-24 h-24 object-cover rounded shadow-md">
                        </div>
                    @elseif($current_imagen_path)
                        <div class="mt-4 flex items-center space-x-4">
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Imagen actual:</span>
                            <img src="{{ asset('storage/' . $current_imagen_path) }}" alt="Imagen actual del producto" class="w-24 h-24 object-cover rounded shadow-md">
                        </div>
                    @endif
                </div>

                <div>
                    <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Categoría
                    </label>
                    <select wire:model="categoria_id" id="categoria_id"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="sm:col-span-2 md:col-span-3 flex justify-end gap-3 mt-4">
                    <button type="submit"
                        class="bg-[#E63946] hover:bg-[#C1121F] text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                        {{ $producto_id ? 'Actualizar Producto' : 'Crear Producto' }}
                    </button>
                    <button type="button" wire:click="vaciarFormulario"
                        class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-medium py-2 px-4 rounded-md transition duration-300 ease-in-out transform hover:scale-105">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Listado de Productos</h2>
            <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Nombre</th>
                        <th class="py-3 px-6 text-left">Código</th>
                        <th class="py-3 px-6 text-left">Precio</th>
                        <th class="py-3 px-6 text-center">Imagen</th>
                        <th class="py-3 px-6 text-left">Categoría</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light">
                    @forelse($productos as $producto)
                        <tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $producto->id }}</td>
                            <td class="py-3 px-6 text-left font-medium">{{ $producto->nombre }}</td>
                            <td class="py-3 px-6 text-left">{{ $producto->codigo }}</td>
                            <td class="py-3 px-6 text-left">${{ number_format($producto->precio_venta, 2) }}</td>
                            <td class="py-3 px-6 text-center">
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-16 h-16 object-cover rounded-md mx-auto shadow-sm">
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-left">{{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center space-x-2">
                                    <button wire:click="editar({{ $producto->id }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded text-xs transition duration-300 ease-in-out transform hover:scale-105">
                                        Editar
                                    </button>
                                    <button wire:click="eliminar({{ $producto->id }})"
                                        wire:confirm="¿Estás seguro de que quieres eliminar este producto?"
                                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded text-xs transition duration-300 ease-in-out transform hover:scale-105">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 px-6 text-center text-gray-500 dark:text-gray-400">
                                No hay productos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>