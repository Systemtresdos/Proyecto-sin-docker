<div>
    <h2 class="text-2xl font-bold mb-4 dark:text-white">Tu Carrito de Compras</h2>

    @if (empty($carritoProductos))
        <p class="dark:text-gray-300">Tu carrito está vacío.</p>
        <a href="{{ route('dashboard') }}" class="text-orange-500 hover:text-orange-600 font-medium">Seguir comprando</a>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-zinc-800 shadow-md rounded-lg">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Producto</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @foreach ($carritoProductos as $productoId => $producto)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700">
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if(!empty($producto['imagen']))
                                        <img src="{{ asset('storage/' . $producto['imagen']) }}" alt="{{ $producto['nombre'] ?? 'Producto' }}" class="w-10 h-10 object-cover rounded mr-3">
                                    @endif
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $producto['nombre'] ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($producto['precio_venta'] ?? 0, 2) }} Bs</td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <input type="number" value="{{ $producto['cantidad'] ?? 1 }}" min="1"
                                        wire:change="actualizarCantidad('{{ $productoId }}', $event.target.value)"
                                        class="w-20 text-center border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-md shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format(($producto['precio_venta'] ?? 0) * ($producto['cantidad'] ?? 0), 2) }} Bs</td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <button wire:click="quitarProductos('{{ $productoId }}')" class="text-red-500 hover:text-red-700 font-medium text-sm">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right font-bold py-3 px-4 text-sm text-gray-700 dark:text-gray-200 uppercase">
                            Total
                        </td>
                        <td class="font-bold py-3 px-4 text-sm text-gray-900 dark:text-white">{{ number_format($total, 2) }} Bs</td>
                        <td class="py-3 px-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6 text-right">
            <a href="#" class="text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-6 py-3 text-center transition-colors duration-200">
                Proceder al Pago
            </a>
        </div>
    @endif
</div>