<x-layouts.app :title="__('Pedidos')">
        @if(auth()->user()->rol_id === 2)
            {{-- Vista para clientes --}}
            <livewire:historial-pedidos />
        @else
            {{-- Vista para administradores/encargados --}}
            <livewire:gestion-pedidos />
        @endif
</x-layouts.app>