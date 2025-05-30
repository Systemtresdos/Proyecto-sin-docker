<x-layouts.app :title="__('Dashboard')">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-red-400 mb-8 text-center">Nuestros Productos</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($productos as $producto)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Imagen del producto -->
                    <div class="h-48 overflow-hidden">
                        <img 
                            src="{{ asset('storage/' . $producto->imagen) }}" 
                            alt="" 
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                        >
                    </div>
                    
                    <!-- Contenido del producto -->
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
</x-layouts.app>
