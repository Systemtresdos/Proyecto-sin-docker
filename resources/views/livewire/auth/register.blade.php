<?php

use App\Models\Usuario;
use App\Models\Cliente;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $nombre = '';
    public string $telefono = '';
    public string $direccion = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $rol_id = '2';

    /**
     * Handle an incoming registration request.
     */
    public function registrarCliente(): void
    {
        $validated = $this->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:10'],
            'direccion' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Usuario::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'rol_id' => ['required', 'integer', 'exists:roles,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $usuario = Usuario::create($validated);

        Cliente::create([
            'usuario_id' => $usuario->id,
        ]);

        event(new Registered($usuario));

        Auth::login($usuario);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
};
?>

<div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-8">
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Regístrate</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Llena los siguientes campos para crear tu cuenta</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="registrarCliente" class="flex flex-col gap-6">
            <!-- Name -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nombre completo
                </label>
                <input wire:model="nombre" id="nombre" type="text" required autofocus autocomplete="nombre"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                    placeholder="Nombre completo">
            </div>

            <!-- Phone -->
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Teléfono
                </label>
                <input wire:model="telefono" id="telefono" type="text" required autocomplete="telefono"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                    placeholder="Número de teléfono">
            </div>

            <!-- Address -->
            <div>
                <label for="direccion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Dirección
                </label>
                <input wire:model="direccion" id="direccion" type="text" required autocomplete="direccion"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                    placeholder="Dirección completa">
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Correo electrónico
                </label>
                <input wire:model="email" id="email" type="email" required autocomplete="email"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                    placeholder="email@example.com">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Contraseña
                </label>
                <div class="relative">
                    <input wire:model="password" id="password" type="password" required autocomplete="new-password"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                        placeholder="••••••••">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        onclick="togglePasswordVisibility('password')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Confirmar contraseña
                </label>
                <div class="relative">
                    <input wire:model="password_confirmation" id="password_confirmation" type="password" required
                        autocomplete="new-password"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                        placeholder="••••••••">
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        onclick="togglePasswordVisibility('password_confirmation')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-[#E63946] hover:bg-[#C1121F] text-white font-medium py-2 px-4 rounded-md transition duration-300">
                Crear cuenta
            </button>
        </form>

        <div class="text-center text-sm text-gray-600 dark:text-gray-400">
            ¿Ya tienes cuenta? 
            <a href="{{ route('login') }}" wire:navigate class="text-[#E63946] hover:text-[#C1121F] font-medium">
                Inicia sesión
            </a>
        </div>
    </div>
</div>