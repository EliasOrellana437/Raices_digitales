<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CultivoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// 1. Página de Bienvenida
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

// 2. Rutas Protegidas (Solo para usuarios logueados)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // CULTIVOS (Aquí usamos el controlador para que cargue los datos de MySQL)
    Route::get('/cultivos', [CultivoController::class, 'index'])->name('cultivos');
    Route::post('/cultivos', [CultivoController::class, 'store'])->name('cultivos.store');

    // Catálogo y Ventas
    Route::get('/catalogo', function () {
        return Inertia::render('Catalogo');
    })->name('catalogo');

    Route::get('/ventas', function () {
        return Inertia::render('Ventas');
    })->name('ventas');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';