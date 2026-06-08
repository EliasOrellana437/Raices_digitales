<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VentaController extends Controller
{
    // Carga las ventas reales de MySQL Workbench
    public function index()
    {
        return Inertia::render('Ventas', [
            'ventas' => Venta::latest()->get()
        ]);
    }

    // Procesa y guarda la compra
public function store(Request $request)
{
    $request->validate([
        'total' => 'required|numeric',
    ]);

    // Guardamos usando los campos EXACTOS de tu migración real
    $venta = new Venta();
    $venta->user_id = auth()->id(); // 🔹 Captura automáticamente el ID del usuario logueado
    $venta->total = $request->total;
    $venta->estado = 'Completado'; // O 'pagado', según prefieras
    $venta->save();

    return redirect()->route('ventas');
}
}