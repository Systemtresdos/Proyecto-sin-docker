<?php

use App\Http\Controllers\Controller;
use App\Http\Livewire\Productos\Create;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
/* Route::get('/productos', Index::class)
    ->name('productos.index'); */

// Volt::route('/categorias', 'categoria')->name('categorias.index');