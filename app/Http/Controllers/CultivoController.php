<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CultivoController extends Controller
{
    public function index()
    {
        // Si la tabla de cultivos está en cero tras el migrate:fresh, metemos datos semilla
        if (Cultivo::count() === 0) {
            Cultivo::create([
                'nombre' => 'Yuca Local',
                'tipo' => 'Tubérculo',
                'estado' => 'En desarrollo',
                'area' => 5 // 5 tareas / manzanas / metros
            ]);

            Cultivo::create([
                'nombre' => 'Tomate de Cocina',
                'tipo' => 'Hortaliza',
                'estado' => 'Listo para cosecha',
                'area' => 3
            ]);
        }

        // Retornamos la vista enviando los cultivos reales de MySQL
        return Inertia::render('Cultivos', [
            'cultivos' => Cultivo::latest()->get()
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'nullable|string',
            'estado' => 'nullable|string',
            'area' => 'nullable|integer',
        ]);

        // Guardado manual inmune a bloqueos de asignación masiva
        $cultivo = new Cultivo();
        $cultivo->nombre = $request->nombre;
        $cultivo->tipo = $request->tipo;
        $cultivo->estado = $request->estado ?? 'En desarrollo';
        $cultivo->area = $request->area;
        $cultivo->save();

        return redirect()->route('cultivos');
    
}

    /**
     * Display the specified resource.
     */
    public function show(Cultivo $cultivo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cultivo $cultivo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cultivo $cultivo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cultivo $cultivo)
    {
        //
    }
}
