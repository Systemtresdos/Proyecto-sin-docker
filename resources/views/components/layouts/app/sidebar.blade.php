<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    @if (auth()->user()->rol_id !== 2)
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Menu')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Inicio') }}
                    </flux:navlist.item>
                    <flux:navlist.item href="{{route('pedidos.index')}}" icon="list-bullet">Pedidos</flux:navlist.item>
                </flux:navlist.group>
                <flux:navlist.group heading="Productos" expandable :expanded="false">
                    <flux:navlist.item href="{{ route('productos.index') }}" icon="hamburger">Lista de productos
                    </flux:navlist.item>
                    <flux:navlist.item href="#" icon="plus">Nuevo producto</flux:navlist.item>
                </flux:navlist.group>
                <flux:navlist.group heading="Categorias" expandable :expanded="false">
                    <flux:navlist.item href="{{ route('categorias.index') }}" icon="salad">Lista de categorias
                    </flux:navlist.item>
                    <flux:navlist.item href="#" icon="plus">Nueva categoria</flux:navlist.item>
                </flux:navlist.group>
                <flux:navlist.group heading="Roles" expandable :expanded="false">
                    <flux:navlist.item href="{{route('roles.index')}}" icon="person-standing">Lista de roles</flux:navlist.item>
                    <flux:navlist.item href="#" icon="plus">Nueva rol</flux:navlist.item>
                </flux:navlist.group>
                <flux:navlist.group heading="Usuarios" expandable :expanded="false">
                    <flux:navlist.item href="{{route('usuarios.index')}}" icon="users">Lista de usuarios</flux:navlist.item>
                    <flux:navlist.item href="#" icon="user-plus">Nuevo usuario</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile :name="auth()->user()->nombre" :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down" />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->nombre }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                            {{ __('Configuracion') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                            class="w-full">
                            {{ __('Cerrar sesion') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>
    @endif
    <!-- Mobile User Menu -->
    <flux:header
        class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        @if (auth()->user()->rol_id !== 2)
            <flux:sidebar.toggle class="lg:hidden" icon="bars-3" inset="left" />
        @endif
        {{-- boton de menu --}}
        @if (auth()->user()->rol_id == 2)
            <flux:dropdown>
                <flux:button class="lg:hidden" icon="bars-3" inset="left"></flux:button>

                <flux:menu>
                    <flux:menu.group heading="Menu">
                        <flux:menu.item href="{{ route('dashboard') }}" icon="home">Inicio</flux:menu.item>
                        <flux:menu.item href="{{route('pedidos.index')}}" icon="list-bullet">Pedidos</flux:menu.item>
                    </flux:menu.group>

                    <flux:menu.group heading="Cuenta">
                        <div class="px-4 py-2 text-sm">
                            <div class="font-semibold truncate">{{ auth()->user()->nombre }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
                        </div>
                        <flux:menu.item :href="route('settings.profile')" icon="cog">Configuracion</flux:menu.item>
                    </flux:menu.group>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:navmenu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                            variant="danger">Cerrar sesion</flux:navmenu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>

        <flux:navbar>
            <flux:navbar.item class="hidden lg:flex" href="{{ route('dashboard') }}" icon="home"
                :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Inicio') }}
            </flux:navbar.item>
            <flux:navbar.item class="hidden lg:flex" href="{{route('pedidos.index')}}" icon="list-bullet">Pedidos</flux:navbar.item>
        </flux:navbar>
        @endif
        <flux:spacer />

        {{-- Carrito de compras --}}
        @if (auth()->user()->rol_id == 2)
        <flux:dropdown position="bottom" align="end">
            <flux:button variant="ghost" class="relative">
                <flux:icon variant="solid" name="shopping-cart" class="text-red-500 dark:text-amber-300" />
                <livewire:carrito-contador />
            </flux:button>

            <flux:menu class="w-[220px]">
                <flux:menu.item href="{{ route('carrito.index') }}" icon="shopping-cart">Ver carrito</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
        @endif

        <flux:dropdown position="bottom" align="end" class="ml-4 hidden lg:flex">
            <flux:profile :name="auth()->user()->nombre" :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down" />
            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->nombre }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        {{ __('Configuracion') }}</flux:menu.item>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full">
                        {{ __('Cerrar sesion') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>

    </flux:header>
    {{ $slot }}

    @fluxScripts
</body>

</html>