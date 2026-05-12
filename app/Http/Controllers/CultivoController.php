<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- ASEGÚRATE DE QUE ESTA LÍNEA ESTÉ AQUÍ
use Inertia\Inertia;
class CultivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    // Traemos los cultivos del usuario
    $cultivos = Cultivo::where('user_id', Auth::id())->get();

    // SOLO escribe 'Cultivos', sin el 'Pages/'
    return Inertia::render('Cultivos', [
        'cultivos' => $cultivos
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
            'fecha_siembra' => 'required|date',
        ]);

        // Aquí es donde le pasamos el ID del usuario logueado
        Cultivo::create([
            'user_id' => Auth::id(), 
            'nombre' => $request->nombre,
            'fecha_siembra' => $request->fecha_siembra,
            'estado' => 'Creciendo',
            'descripcion' => $request->descripcion,
        ]);

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
