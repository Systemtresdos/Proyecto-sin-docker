<div class="max-w-5xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg min-h-screen">
    <!-- Título -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Gestión de Usuarios</h2>
        <button wire:click="createUser"
            class="bg-[#E63946] hover:bg-[#C1121F] text-white font-semibold px-6 py-2 rounded-md transition shadow">
            Crear Nuevo Usuario
        </button>
    </div>

    <!-- Mensaje de sesión -->
    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <!-- Barra de búsqueda -->
    <div class="mb-6">
        <input type="text" wire:model.live="search" placeholder="Buscar usuarios..."
            class="w-full md:w-1/3 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white shadow-sm">
    </div>

    <!-- Formulario de usuario -->
    @if ($showUserForm)
        <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-xl shadow-lg mb-8">
            <h3 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">
                {{ $editingUser ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}
            </h3>

            <form wire:submit.prevent="saveUser" class="space-y-5">
                @foreach ([
                    'nombre' => 'Nombre',
                    'telefono' => 'Teléfono',
                    'direccion' => 'Dirección',
                    'email' => 'Email',
                    'password' => 'Contraseña (dejar vacío para no cambiar)',
                ] as $field => $label)
                    <div>
                        <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }}</label>
                        <input type="{{ $field === 'email' ? 'email' : ($field === 'password' ? 'password' : 'text') }}"
                            id="{{ $field }}" wire:model="form.{{ $field }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white">
                        @error("form.$field") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                @endforeach

                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                    <select id="estado" wire:model="form.estado"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                        <option value="Bloqueado">Bloqueado</option>
                    </select>
                    @error('form.estado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="rol_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rol</label>
                    <select id="rol_id" wire:model="form.rol_id"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white">
                        <option value="">Selecciona un rol</option>
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                    @error('form.rol_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-between">
                    <button type="submit"
                        class="bg-[#E63946] hover:bg-[#C1121F] text-white px-6 py-2 rounded-md font-semibold shadow">
                        {{ $editingUser ? 'Actualizar' : 'Guardar' }}
                    </button>
                    <button type="button" wire:click="closeUserForm"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-md font-semibold shadow">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Tabla de usuarios -->
    <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl shadow-lg">
        <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800 border-b text-gray-600 dark:text-gray-300 uppercase text-xs">
                    @foreach ([
                        'id' => 'ID',
                        'nombre' => 'Nombre',
                        'telefono' => 'Teléfono',
                        'direccion' => 'Dirección',
                        'email' => 'Email',
                        'estado' => 'Estado',
                        'rol_id' => 'Rol'
                    ] as $field => $label)
                        <th class="py-3 px-5 text-left cursor-pointer" wire:click="sortBy('{{ $field }}')">
                            {{ $label }}
                        </th>
                    @endforeach
                    <th class="py-3 px-5 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($usuarios as $usuario)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-5 py-3">{{ $usuario->id }}</td>
                        <td class="px-5 py-3">{{ $usuario->nombre }}</td>
                        <td class="px-5 py-3">{{ $usuario->telefono ?? 'N/A' }}</td>
                        <td class="px-5 py-3">{{ $usuario->direccion }}</td>
                        <td class="px-5 py-3">{{ $usuario->email }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                {{
                                    $usuario->estado === 'Activo' ? 'bg-green-200 text-green-800' :
                                    ($usuario->estado === 'Inactivo' ? 'bg-yellow-200 text-yellow-800' :
                                    'bg-red-200 text-red-800')
                                }}">
                                {{ $usuario->estado }}
                            </span>
                        </td>
                        <td class="px-5 py-3">{{ $usuario->rol->nombre ?? 'Sin Rol' }}</td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <button wire:click="editUser({{ $usuario->id }})"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold px-3 py-1 rounded shadow">
                                    Editar
                                </button>
                                <button wire:click="deleteUser({{ $usuario->id }})"
                                    class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold px-3 py-1 rounded shadow">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500 dark:text-gray-400">No se encontraron usuarios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $usuarios->links() }}
    </div>
</div>
