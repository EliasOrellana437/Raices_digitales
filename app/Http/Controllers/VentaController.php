<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB; // 🔹 Importante para usar transacciones

class VentaController extends Controller
{
    public function index()
    {
        return Inertia::render('Ventas', [
            'ventas' => Venta::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'total' => 'required|numeric',
        ]);

        // Usamos una transacción para asegurar que todo se guarde bien o nada se guarde
        DB::beginTransaction();

        try {
            // 1. Guardar la venta principal
            $venta = new Venta();
            $venta->user_id = auth()->id();
            $venta->total = $request->total;
            $venta->estado = 'Completado';
            $venta->save();

            // 2. Descontar stock de cada producto
            foreach ($request->items as $item) {
                $producto = Producto::findOrFail($item['id']);
                
                // Verificamos stock antes de restar
                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para: " . $producto->nombre);
                }

                $producto->stock -= $item['cantidad'];
                $producto->save();
            }

            DB::commit(); // Todo salió bien, guardamos cambios

            return redirect()->route('ventas');

        } catch (\Exception $e) {
            DB::rollBack(); // Algo falló, revertimos todo
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}