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

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Registrate')" :description="__('Llena los siguientes campos para crear tu cuenta')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="registrarCliente" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input wire:model="nombre" :label="__('Nombre')" type="text" required autofocus autocomplete="nombre"
            :placeholder="__('Nombre completo')" />

        <flux:input wire:model="telefono" :label="__('Telefono')" type="text" required autocomplete="telefono"
            :placeholder="__('Telefono')" />

        <flux:input wire:model="direccion" :label="__('Direccion')" type="text" required autocomplete="direccion"
            :placeholder="__('Direccion')" />

        <!-- Email Address -->
        <flux:input wire:model="email" :label="__('Correo electronico')" type="email" required autocomplete="email"
            placeholder="email@example.com" />

        <!-- Password -->
        <flux:input wire:model="password" :label="__('Contraseña')" type="password" required autocomplete="new-password"
            :placeholder="__('Contraseña')" viewable />

        <!-- Confirm Password -->
        <flux:input wire:model="password_confirmation" :label="__('Confirmar contraseña')" type="password" required
            autocomplete="new-password" :placeholder="__('Confirmar contraseña')" viewable />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Crear cuenta') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Ya tienes cuenta?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Inicia Session') }}</flux:link>
    </div>
</div>
