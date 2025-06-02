<x-layouts.app :title="__('Dashboard')">
    @if(auth()->user()->rol_id !== 1)
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-red-400 mb-8 text-center">Nuestros Productos</h1>
            <livewire:filtrar-producto />
        </div>
    @endif
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-red-400 mb-8 text-center">Pedidos</h1>
        <livewire:gestion-pedidos />
    </div>
</x-layouts.app>