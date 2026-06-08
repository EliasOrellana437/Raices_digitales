<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CultivoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// 1. Página de Bienvenida (Pública)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

// 2. Rutas Protegidas (Solo para usuarios logueados y verificados)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard / Inicio
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // MÓDULO: CULTIVOS
    Route::get('/cultivos', [CultivoController::class, 'index'])->name('cultivos');
    Route::post('/cultivos', [CultivoController::class, 'store'])->name('cultivos.store');

    // MÓDULO: CATÁLOGO / MERCADO
    Route::get('/catalogo', [ProductoController::class, 'index'])->name('catalogo');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->name('productos.destroy');

    // MÓDULO: VENTAS (Conexión real a MySQL Workbench)
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas');
    Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');

    // MÓDULO: CONFIRMACIÓN DE COMPRA / CARRITO
    Route::get('/compra', function () {
        return Inertia::render('DetalleCompra');
    })->name('compra.confirmacion');

    // PERFIL DE USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';