
<div>
    <div class="mb-6 flex flex-col md:flex-row gap-4 items-center">
       <flux:select wire:model="categoria_id" wire:model.live="categoria_id" class="w-full md:w-1/3">
            <flux:select.option value="">Todas las categorías</flux:select.option>
            @foreach ($categorias as $categoria)
                <flux:select.option value="{{ $categoria->id }}">{{ $categoria->nombre }}</flux:select.option>
            @endforeach
        </flux:select>
        <div class="relative w-full md:w-1/3">
            <flux:input wire:model.live="buscar" icon="magnifying-glass" placeholder="Buscar" />
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach ($productos as $producto)
            <div wire:key="producto-{{ $producto->id }}"  class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <div class="h-48 overflow-hidden">
                    <img 
                        src="{{ asset('storage/' . $producto->imagen) }}" 
                        alt="" 
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                    >
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h2 class="text-xl font-semibold text-gray-800">{{$producto->nombre}}</h2>
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded">
                            {{$producto->categoria->nombre}}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4">{{$producto->descripcion}}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-900">{{$producto->precio_venta}}Bs</span>
                        <livewire:btn-agregar-carrito :producto_id="$producto->id" :producto_nombre="$producto->nombre" :key="'btn-agregar-'.$producto->id"/>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>