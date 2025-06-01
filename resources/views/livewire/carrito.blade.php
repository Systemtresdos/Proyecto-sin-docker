<div>
    <h2 class="text-2xl font-bold mb-4 dark:text-white">Tu Carrito de Compras</h2>

    @if (session()->has('error_carrito'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error_carrito') }}</span>
        </div>
    @endif

    @if (empty($carritoProductos) && !$mostrarModalPagoQr)
        <p class="dark:text-gray-300">Tu carrito está vacío.</p>
        <a href="{{ route('dashboard') }}" class="text-orange-500 hover:text-orange-600 font-medium">Seguir comprando</a>
    @elseif (!$mostrarModalPagoQr)
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
                            <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($producto['precio_venta'] ?? $producto['precio'] ?? 0, 2) }} Bs</td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <input type="number" value="{{ $producto['cantidad'] ?? 1 }}" min="1"
                                       wire:change="actualizarCantidad('{{ $productoId }}', $event.target.value)"
                                       class="w-20 text-center border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white rounded-md shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format(($producto['precio_venta'] ?? $producto['precio'] ?? 0) * ($producto['cantidad'] ?? 0), 2) }} Bs</td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <button wire:click="quitarProductos('{{ $productoId }}')" class="text-red-500 hover:text-red-700 font-medium text-sm">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right font-bold py-3 px-4 text-sm text-gray-700 dark:text-gray-200 uppercase">Total:</td>
                        <td class="font-bold py-3 px-4 text-sm text-gray-900 dark:text-white">{{ number_format($total, 2) }} Bs</td>
                        <td class="py-3 px-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-6 text-right">
            {{-- Botón para iniciar el pago con QR --}}
            <button wire:click="iniciarPagoConQr"
                    class="text-white bg-orange-500 hover:bg-orange-600 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-6 py-3 text-center transition-colors duration-200">
                Proceder al Pago con QR
            </button>
        </div>
    @endif

    {{-- Modal para el Pago con QR --}}
    @if ($mostrarModalPagoQr)
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-lg shadow-xl w-full max-w-lg max-h-full overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold dark:text-white">Procesar Pago con QR</h3>
                    <button wire:click="cerrarModalPagoQr" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">&times;</button>
                </div>
                {{-- Aquí se cargará el componente PagoConQr --}}
                @livewire('pago-con-qr', ['carritoProductos' => $carritoProductos, 'total' => $total])
            </div>
        </div>
    @endif
</div>