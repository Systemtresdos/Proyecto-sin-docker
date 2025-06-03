<x-layouts.app :title="__('Dashboard')">
    @if(auth()->user()->rol_id == 2)
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold text-red-400 mb-8 text-center">Nuestros Productos</h1>
            <livewire:filtrar-producto />
        </div>
    @else
        <div class="container mx-auto px-4 py-8">
        <livewire:gestion-pedidos />
    </div>
    @endif
</x-layouts.app>