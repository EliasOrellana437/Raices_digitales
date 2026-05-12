<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    use HasFactory;

    // Esta línea es la que falta:
protected $fillable = ['user_id', 'nombre', 'fecha_siembra', 'estado', 'descripcion'];
}