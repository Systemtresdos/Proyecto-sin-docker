<?php

use App\Http\Controllers\Controller;
use App\Http\Livewire\Productos\Create;
use App\Models\Producto;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\UserManagement; // Componente Livewire para usuarios
use App\Livewire\RoleManagement; // Componente Livewire para roles

// Ruta principal que carga la vista 'dashboard.blade.php'
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $productos = Producto::all();
    return view('dashboard', compact('productos'));
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';

Route::get('/categorias', function () {
    return view('categorias');
})->name('categorias.index');

Route::get('/productos', function () {
    return view('productos');
})->name('productos.index');

Route::get('/roles', function () {
    return view('roles');
})->name('roles.index');

Route::get('/usuarios', function () {
    return view('usuarios');
})->name('usuarios.index');

Route::get('/carrito', function () {
    return view('carrito');
})->name('carrito.index');

// Ruta para gestión de usuarios
Route::get('/users', UserManagement::class)->name('users.index');

// Ruta para gestión de roles
Route::get('/roles', RoleManagement::class)->name('roles.index');

/* Route::get('/productos', Index::class)
    ->name('productos.index'); */

/* Route::get('/usuarios', function () {
    return view('usuarios');
})->name('usuarios.index'); */

