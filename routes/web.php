<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\UserManagement; // Componente Livewire para usuarios
use App\Livewire\RoleManagement; // Componente Livewire para roles

// Ruta principal que carga la vista 'dashboard.blade.php'
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::view('dashboard','dashboard')
    ->middleware(['auth','verifierd'])
    ->name('dashboard');

// Ruta para gestión de usuarios
Route::get('/users', UserManagement::class)->name('users.index');

// Ruta para gestión de roles
Route::get('/roles', RoleManagement::class)->name('roles.index');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
