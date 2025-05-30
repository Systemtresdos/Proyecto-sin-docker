
    <div>
        <h2 class="text-2xl font-bold mb-4">Gestión de Roles</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <input type="text" wire:model.live="search" placeholder="Buscar roles..."
                   class="shadow appearance-none border rounded w-1/3 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <button wire:click="createRole"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Crear Nuevo Rol
            </button>
        </div>

        @if ($showRoleForm)
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h3 class="text-xl font-bold mb-4">{{ $editingRole ? 'Editar Rol' : 'Crear Nuevo Rol' }}</h3>
                <form wire:submit.prevent="saveRole">
                    <div class="mb-4">
                        <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre del Rol:</label>
                        <input type="text" id="nombre" wire:model="form.nombre"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.nombre') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                        <textarea id="descripcion" wire:model="form.descripcion" rows="3"
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        @error('form.descripcion') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ $editingRole ? 'Actualizar' : 'Guardar' }}
                        </button>
                        <button type="button" wire:click="closeRoleForm"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <div class="bg-white shadow-md rounded my-6 overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('id')">ID</th>
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('nombre')">Nombre</th>
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('descripcion')">Descripción</th> {{-- Nueva columna --}}
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse ($roles as $rol)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $rol->id }}</td>
                            <td class="py-3 px-6 text-left">{{ $rol->nombre }}</td>
                            <td class="py-3 px-6 text-left">{{ $rol->descripcion ?? 'N/A' }}</td> {{-- Mostrar descripción --}}
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <button wire:click="editRole({{ $rol->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs mr-2">
                                        Editar
                                    </button>
                                    <button wire:click="deleteRole({{ $rol->id }})"
                                            wire:confirm="¿Estás seguro de que quieres eliminar este rol? Esto podría afectar a los usuarios asociados."
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-3 px-6 text-center">No se encontraron roles.</td> {{-- Colspan ajustado --}}
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Enlaces de paginación --}}
        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </div>
