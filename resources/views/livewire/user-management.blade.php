
    <div> 
        <h2 class="text-2xl font-bold mb-4">Gestión de Usuarios</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <input type="text" wire:model.live="search" placeholder="Buscar usuarios..."
                    class="shadow appearance-none border rounded w-1/3 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <button wire:click="createUser"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Crear Nuevo Usuario
            </button>
        </div>

        @if ($showUserForm)
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h3 class="text-xl font-bold mb-4">{{ $editingUser ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}</h3>
                <form wire:submit.prevent="saveUser">
                    <div class="mb-4">
                        <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                        <input type="text" id="nombre" wire:model="form.nombre"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.nombre') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="telefono" class="block text-gray-700 text-sm font-bold mb-2">Teléfono:</label>
                        <input type="text" id="telefono" wire:model="form.telefono"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.telefono') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="direccion" class="block text-gray-700 text-sm font-bold mb-2">Dirección:</label>
                        <input type="text" id="direccion" wire:model="form.direccion"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.direccion') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" id="email" wire:model="form.email"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.email') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña (dejar vacío para no cambiar):</label>
                        <input type="password" id="password" wire:model="form.password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.password') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="estado" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                        <select id="estado" wire:model="form.estado"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Bloqueado">Bloqueado</option>
                        </select>
                        @error('form.estado') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="rol_id" class="block text-gray-700 text-sm font-bold mb-2">Rol:</label>
                        <select id="rol_id" wire:model="form.rol_id"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Selecciona un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                        @error('form.rol_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ $editingUser ? 'Actualizar' : 'Guardar' }}
                        </button>
                        <button type="button" wire:click="closeUserForm"
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
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('telefono')">Teléfono</th>
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('direccion')">Dirección</th>
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('email')">Email</th>
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('estado')">Estado</th>
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('rol_id')">Rol</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse ($usuarios as $usuario)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $usuario->id }}</td>
                            <td class="py-3 px-6 text-left">{{ $usuario->nombre }}</td>
                            <td class="py-3 px-6 text-left">{{ $usuario->telefono ?? 'N/A' }}</td>
                            <td class="py-3 px-6 text-left">{{ $usuario->direccion }}</td>
                            <td class="py-3 px-6 text-left">{{ $usuario->email }}</td>
                            <td class="py-3 px-6 text-left">
                                <span class="py-1 px-3 rounded-full text-xs
                                    @if ($usuario->estado == 'Activo') bg-green-200 text-green-600
                                    @elseif ($usuario->estado == 'Inactivo') bg-yellow-200 text-yellow-600
                                    @else bg-red-200 text-red-600 @endif">
                                    {{ $usuario->estado }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-left">{{ $usuario->rol->nombre ?? 'Sin Rol' }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <button wire:click="editUser({{ $usuario->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs mr-2">
                                        Editar
                                    </button>
                                    <button wire:click="deleteUser({{ $usuario->id }})"
                                            wire:confirm="¿Estás seguro de que quieres eliminar a este usuario?"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-3 px-6 text-center">No se encontraron usuarios.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $usuarios->links() }}
        </div>
    </div>



    <div> 
        <h2 class="text-2xl font-bold mb-4">Gestión de Usuarios</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <input type="text" wire:model.live="search" placeholder="Buscar usuarios..."
                   class="shadow appearance-none border rounded w-1/3 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <button wire:click="createUser"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Crear Nuevo Usuario
            </button>
        </div>

        @if ($showUserForm)
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h3 class="text-xl font-bold mb-4">{{ $editingUser ? 'Editar Usuario' : 'Crear Nuevo Usuario' }}</h3>
                <form wire:submit.prevent="saveUser">
                    <div class="mb-4">
                        <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                        <input type="text" id="nombre" wire:model="form.nombre"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.nombre') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="telefono" class="block text-gray-700 text-sm font-bold mb-2">Teléfono:</label>
                        <input type="text" id="telefono" wire:model="form.telefono"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.telefono') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="direccion" class="block text-gray-700 text-sm font-bold mb-2">Dirección:</label>
                        <input type="text" id="direccion" wire:model="form.direccion"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.direccion') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" id="email" wire:model="form.email"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.email') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña (dejar vacío para no cambiar):</label>
                        <input type="password" id="password" wire:model="form.password"
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('form.password') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="estado" class="block text-gray-700 text-sm font-bold mb-2">Estado:</label>
                        <select id="estado" wire:model="form.estado"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Bloqueado">Bloqueado</option>
                        </select>
                        @error('form.estado') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="rol_id" class="block text-gray-700 text-sm font-bold mb-2">Rol:</label>
                        <select id="rol_id" wire:model="form.rol_id"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Selecciona un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                        @error('form.rol_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            {{ $editingUser ? 'Actualizar' : 'Guardar' }}
                        </button>
                        <button type="button" wire:click="closeUserForm"
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
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('telefono')">Teléfono</th>
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('direccion')">Dirección</th>
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('email')">Email</th>
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('estado')">Estado</th>
                        <th class="py-3 px-6 text-left cursor-pointer" wire:click="sortBy('rol_id')">Rol</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse ($usuarios as $usuario)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $usuario->id }}</td>
                            <td class="py-3 px-6 text-left">{{ $usuario->nombre }}</td>
                            <td class="py-3 px-6 text-left">{{ $usuario->telefono ?? 'N/A' }}</td>
                            <td class="py-3 px-6 text-left">{{ $usuario->direccion }}</td>
                            <td class="py-3 px-6 text-left">{{ $usuario->email }}</td>
                            <td class="py-3 px-6 text-left">
                                <span class="py-1 px-3 rounded-full text-xs
                                    @if ($usuario->estado == 'Activo') bg-green-200 text-green-600
                                    @elseif ($usuario->estado == 'Inactivo') bg-yellow-200 text-yellow-600
                                    @else bg-red-200 text-red-600 @endif">
                                    {{ $usuario->estado }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-left">{{ $usuario->rol->nombre ?? 'Sin Rol' }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <button wire:click="editUser({{ $usuario->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs mr-2">
                                        Editar
                                    </button>
                                    <button wire:click="deleteUser({{ $usuario->id }})"
                                            wire:confirm="¿Estás seguro de que quieres eliminar a este usuario?"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-3 px-6 text-center">No se encontraron usuarios.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $usuarios->links() }}
        </div>
    </div>

