<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CultivoController extends Controller
{
    public function index()
    {
        // Retornamos la vista enviando los cultivos reales de MySQL
        return Inertia::render('Cultivos', [
            'cultivos' => Cultivo::latest()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_siembra' => 'required|date',
            'estado' => 'required|string',
            'tiempo_estimado' => 'nullable|integer',
            'descripcion' => 'nullable|string',
        ]);

        // Guardado usando create con asignación masiva (Asegúrate de tener el $fillable en el modelo)
        Cultivo::create($request->all());

        return redirect()->route('cultivos');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) 
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_siembra' => 'required|date',
            'estado' => 'required|string',
            'tiempo_estimado' => 'nullable|integer',
            'descripcion' => 'nullable|string',
        ]);

        $cultivo = Cultivo::findOrFail($id);
        $cultivo->update($request->all());
        
        return redirect()->route('cultivos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cultivo = Cultivo::findOrFail($id);
        $cultivo->delete();
        
        return redirect()->route('cultivos');
    }
}