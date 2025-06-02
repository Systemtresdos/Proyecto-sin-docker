<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>

<div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden p-8">
    <!-- Logo PuntoFast -->
    <div class="flex justify-center mb-6">
        <x-app-logo-icon/>
    </div>

    <div class="flex flex-col gap-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Iniciar Sesión</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Ingresa tus credenciales para acceder a tu cuenta</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="login" class="flex flex-col gap-6">
            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Correo electrónico
                </label>
                <input wire:model="email" id="email" type="email" required autofocus autocomplete="email"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                    placeholder="email@example.com">
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Contraseña
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate
                            class="text-sm text-[#E63946] hover:text-[#C1121F]">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>
                <input wire:model="password" id="password" type="password" required autocomplete="current-password"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-[#E63946] focus:border-[#E63946] dark:bg-gray-700 dark:text-white"
                    placeholder="••••••••">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input wire:model="remember" id="remember" type="checkbox"
                    class="h-4 w-4 text-[#E63946] focus:ring-[#E63946] border-gray-300 rounded dark:border-gray-600">
                <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    Recuérdame
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-[#E63946] hover:bg-[#C1121F] text-white font-medium py-2 px-4 rounded-md transition duration-300">
                Ingresar
            </button>
        </form>

        @if (Route::has('register'))
            <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                ¿No tienes cuenta? 
                <a href="{{ route('register') }}" wire:navigate class="text-[#E63946] hover:text-[#C1121F] font-medium">
                    Regístrate
                </a>
            </div>
        @endif
    </div>
</div>
