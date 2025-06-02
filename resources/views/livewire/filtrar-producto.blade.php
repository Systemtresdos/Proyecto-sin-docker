<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Filtros y búsqueda -->
    <div class="mb-8 flex flex-col md:flex-row gap-4 items-center">
        <!-- Selector de categoría -->
        <div class="w-full md:w-1/3">
            <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Categoría
            </label>
            <select 
                wire:model.live="categoria_id"
                id="categoria_id"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
            >
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>

        <!-- Barra de búsqueda -->
        <div class="w-full md:w-1/3">
            <label for="buscar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Buscar productos
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input 
                    wire:model.live="buscar"
                    id="buscar"
                    type="text"
                    placeholder="Buscar productos..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                >
            </div>
        </div>
    </div>

    <!-- Grid de productos -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
        @foreach ($productos as $producto)
            <div 
                wire:key="producto-{{ $producto->id }}"
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-700"
            >
                <!-- Imagen del producto -->
                <div class="h-48 overflow-hidden relative">
                    <img 
                        src="{{ asset('storage/' . $producto->imagen) }}" 
                        alt="{{ $producto->nombre }}"
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                    >
                    <!-- Badge de categoría -->
                    <span class="absolute top-2 right-2 bg-[#E63946] text-white text-xs font-semibold px-2.5 py-0.5 rounded">
                        {{ $producto->categoria->nombre }}
                    </span>
                </div>

                <!-- Contenido del producto -->
                <div class="p-5">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                        {{ $producto->nombre }}
                    </h2>
                    
                    <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
                        {{ $producto->descripcion }}
                    </p>

                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($producto->precio_venta, 2) }} Bs
                        </span>
                        
                        <livewire:btn-agregar-carrito 
                            :producto_id="$producto->id" 
                            :producto_nombre="$producto->nombre" 
                            :key="'btn-agregar-'.$producto->id"
                        />
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>