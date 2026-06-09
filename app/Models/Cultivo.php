<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    use HasFactory;

    // Con esto le decimos a Laravel que puede guardar cualquier campo que le enviemos
    protected $guarded = []; 
}