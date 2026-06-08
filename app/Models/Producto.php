<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Con esto liberamos todas las columnas de la tabla al 100%
    protected $guarded = []; 
}