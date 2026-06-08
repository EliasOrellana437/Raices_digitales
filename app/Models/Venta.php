<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    // Le decimos explícitamente que la tabla se llama 'ventas' (en español)
    // para que Laravel no intente buscarla en plural en inglés ('ventas')
    protected $table = 'ventas';

    // Habilitamos los campos para que Laravel permita llenarlos desde el controlador
protected $fillable = [
    'user_id',
    'total',
    'estado',
];
}