<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductoController extends Controller
{
    // Mostrar el catálogo con datos persistidos de MySQL
    public function index()
    {
        // Si la tabla de productos no tiene registros, inyectamos los datos base
        if (Producto::count() === 0) {
            Producto::create([
                'nombre' => 'Aguacate Hass',
                'vendedor' => "Finca 'Los Olivos' (Carlos Pérez)",
                'precio' => 5.50,
                'stock' => 50,
                'imagen' => 'https://images.unsplash.com/photo-1523049673857-eb18f1d7b578?w=500&q=80',
                'descripcion' => 'Caja de 2kg de aguacates Hass maduros y listos para consumo.'
            ]);

            Producto::create([
                'nombre' => 'Café de Altura (500g)',
                'vendedor' => 'Cooperativa Café Real El Salvador',
                'precio' => 6.50,
                'stock' => 20,
                'imagen' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=500&q=80',
                'descripcion' => 'Café arábica de estricta altura, cultivado en la zona de Santa Ana.'
            ]);

            Producto::create([
                'nombre' => 'Miel de Abeja Pura',
                'vendedor' => 'Apicultor Méndez - San Miguel',
                'precio' => 8.00,
                'stock' => 15,
                'imagen' => 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=500&q=80',
                'descripcion' => 'Frasco de 1kg de miel 100% pura y orgánica de abejas de monte.'
            ]);
        }

        // Retornamos todos los productos actualizados de la BD ordenados por el más reciente
        return Inertia::render('Catalogo', [
            'productos' => Producto::latest()->get()
        ]);
    }

    // Almacenar publicación en MySQL
public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'precio' => 'required|numeric|min:0.10',
        'descripcion' => 'nullable|string',
        'imagen' => 'nullable|string',
    ]);

    // 🛠️ SOLUCIÓN REAL E INFALIBLE: Instanciamos el objeto de forma manual
    // Esto se salta COMPLETAMENTE las reglas del fillable y de la caché
    $producto = new \App\Models\Producto();
    $producto->nombre = $request->nombre;
    $producto->vendedor = auth()->user()->name . ' (Tú)';
    $producto->precio = $request->precio;
    $producto->stock = 10; // Asignación directa e inmune a errores de asignación masiva
    $producto->imagen = $request->imagen ?? 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=500&q=80';
    $producto->descripcion = $request->descripcion;
    
    // Guardamos directo a MySQL
    $producto->save();

    return redirect()->route('catalogo');
}
    // Dar de baja una publicación en MySQL
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return redirect()->route('catalogo');
    }
}