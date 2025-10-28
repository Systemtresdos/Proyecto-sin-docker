<?php

use App\Http\Controllers\PagoQrController;
use App\Http\Controllers\PedidoController;
use App\Livewire\UserManagement;
use App\Livewire\RoleManagement;
use App\Models\Producto;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    $productos = Producto::all();
    return view('welcome', compact('productos'));
})->name('home');

// Ruta pública del menú (para clientes)
Route::get('/menu', function () {
    return view('welcome');
})->name('menu.index');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Rutas disponibles para todos los usuarios autenticados
    Route::get('/carrito', function () {
        return view('carrito');
    })->name('carrito.index');

    Route::get('/pedidos', function () {
        return view('pedidos');
    })->name('pedidos.index');
    
    // Rutas del módulo de pedidos (accesibles para todos autenticados)
    // IMPORTANTE: Las rutas específicas deben ir ANTES de las rutas con parámetros
    Route::get('/pedidos/reporte/pdf', [PedidoController::class, 'generarReportePDF'])->name('pedidos.reporte.pdf');
    Route::get('/pedidos/{id}/detalle', [PedidoController::class, 'detalle'])->name('pedidos.detalle');
    Route::get('/pedidos/{id}/pdf', [PedidoController::class, 'generarPDF'])->name('pedidos.pdf');
    
    Route::get('/pago-qr/confirmar/{token}', [PagoQrController::class, 'mostrarPaginaConfirmacion'])->name('pago.qr.confirmar');
    Route::post('/pago-qr/procesar-confirmacion/{token}', [PagoQrController::class, 'procesarConfirmacion'])->name('pago.qr.procesar');
});

// Rutas exclusivas para ADMINISTRADORES (rol_id = 1)
Route::middleware(['auth', 'admin'])->group(function () {
    // Gestión de Categorías
    Route::get('/categorias', function () {
        return view('categorias');
    })->name('categorias.index');
    
    // Gestión de Productos
    Route::get('/productos', function () {
        return view('productos');
    })->name('productos.index');
    
    // Gestión de Roles
    Route::get('/roles', function () {
        return view('roles');
    })->name('roles.index');
    
    // Gestión de Usuarios
    Route::get('/usuarios', function () {
        return view('usuarios');
    })->name('usuarios.index');
    
    Route::get('/users', UserManagement::class)->name('users.index');
    
    // Rutas de reportes PDF adicionales (solo admin)
    Route::post('/pedidos/reporte/fecha', [PedidoController::class, 'reportePorFecha'])->name('pedidos.reporte.fecha');
    Route::get('/pedidos/reporte/diario', [PedidoController::class, 'reporteDiario'])->name('pedidos.reporte.diario');
    Route::get('/pedidos/reporte/mensual', [PedidoController::class, 'reporteMensual'])->name('pedidos.reporte.mensual');
});

require __DIR__.'/auth.php';

/* Route::get('/productos', Index::class)
    ->name('productos.index'); */

/* Route::get('/usuarios', function () {
    return view('usuarios');
})->name('usuarios.index'); */
